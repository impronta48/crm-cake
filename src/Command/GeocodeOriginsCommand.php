<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Exception;

class GeocodeOriginsCommand extends Command
{
  protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
  {
    $parser
      ->addArgument('company_id', [
        'help' => "id dell'azienda che si vuole geocodificare",
        'required' => true
      ])
      ->addArgument('redo', [
        'help' => 'se redo=1 vengono ri-geocodificati tutti gli indirizzi',
      ]);

    return $parser;
  }

  public function execute(Arguments $args, ConsoleIo $io)
  {
    $company_id = (int)$args->getArgument('company_id');
    $redo = $args->getArgument('redo');

    $this->loadModel('Companies');
    $this->loadModel('Origins');
    $company = $this->Companies->get($company_id);
    if (!$company) {
      throw new Exception("Company $company_id not found");
    }

    if ($redo) {
      $originIds = $this->Origins->getAll($company_id);
    } else {
      $originIds = $this->Origins->getAllNotGeocoded($company_id);
    }


    foreach ($originIds as $originId) {
      $io->out("Geocoding origin # $originId");
      $this->Origins->geocode($originId);
    }
  }
}
