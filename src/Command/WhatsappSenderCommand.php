<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use App\Model\Table\WaQueueTable;
use App\Chat\WhatsappService;

class WhatsappSenderCommand extends Command
{
    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser(): ConsoleOptionParser
    {
        $parser = parent::getOptionParser();
        $parser
            ->setDescription('Sends queued emails in a batch')
            ->addOption(
                'limit',
                [
                'short' => 'l',
                'help' => 'How many emails should be sent in this batch?',
                'default' => '50',
                ]
            )
            ->addOption(
                'template',
                [
                'short' => 't',
                'help' => 'Name of the template to be used to render email',
                'default' => 'default',
                ]
            )
            ->addOption(
                'layout',
                [
                'short' => 'w',
                'help' => 'Name of the layout to be used to wrap template',
                'default' => 'default',
                ]
            )
            ->addOption(
                'stagger',
                [
                'short' => 's',
                'help' => 'Seconds to maximum wait randomly before proceeding (useful for parallel executions)',
                'default' => false,
                ]
            )
            ->addOption(
                'config',
                [
                'short' => 'c',
                'help' => 'Name of email settings to use as defined in email.php',
                'default' => 'default',
                ]
            );
            // ->addSubcommand(
            //     'clearLocks',
            //     [
            //     'help' => 'Clears all locked emails in the queue, useful for recovering from crashes',
            //     ]
            // );

        return $parser;
    }

    /**
     * Sends queued emails.
     *
     * @return void
     */
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $stagger = (int)$args->getOption('stagger');
        if ($stagger) {
            sleep(rand(0, $stagger));
        }

        Configure::write('App.baseUrl', '/');
        $waQueue = TableRegistry::getTableLocator()->get('WaQueue', ['className' => WaQueueTable::class]);
        $limit = (int)$args->getOption('limit');
        $wamessages = $waQueue->getBatch($limit);

        $count = count($wamessages);

        foreach ($wamessages as $wa) {

            $sent = false;
            $errorMessage = null;

            $session = $wa['wa_session'];
            if ($session != null && $session != '') {
                $user = $wa['phone'];
                $message = $wa['body'];
                $respData = WhatsappService::getInstance()->sendMessage($session, $user, $message);
                $sent = $respData['success'];
                if (!$sent) {
                    $errorMessage = $respData['error'];
                    $io->err($errorMessage);
                }
            }

            if ($sent) {
                $waQueue->success($wa->id);
                $io->out('<success>WA Message ' . $wa->id . ' was sent</success>');
            } else {
                $waQueue->fail($wa->id, $errorMessage);
                $io->out('<error>WA Message ' . $wa->id . ' was not sent</error>');
            }
        }

        if ($count > 0) {
            $locks = collection($wamessages)->extract('id')->toList();
            $waQueue->releaseLocks($locks);
        }

        return static::CODE_SUCCESS;
    }

    /**
     * Clears all locked emails in the queue, useful for recovering from crashes.
     *
     * @return void
     */
    public function clearLocks(): void
    {
        TableRegistry::getTableLocator()
            ->get('WaQueue', ['className' => WaQueueTable::class])
            ->clearLocks();
    }
}
