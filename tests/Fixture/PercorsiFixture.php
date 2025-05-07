<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PercorsiFixture
 */
class PercorsiFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'percorsi';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'title' => 'Lorem ipsum dolor sit amet',
                'slug' => 'Lorem ipsum dolor sit amet',
                'descr' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'comune' => 'Lorem ipsum dolor sit amet',
                'centroid_lon' => 1,
                'centroid_lat' => 1,
                'created' => '2025-04-29 15:49:04',
                'modified' => '2025-04-29 15:49:04',
                'user_id' => 1,
                'tags' => 'Lorem ipsum dolor sit amet',
                'occhiello' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'namespace' => 'Lorem ipsum dolor sit amet',
                'altimetria_min' => 1,
                'altimetria_max' => 1,
                'difficolta' => 1,
                'parent_id' => 1,
                'tempo_percorrenza' => 1,
                'lunghezza' => 'Lorem ipsum dolor sit amet',
                'colore' => 'Lorem ipsum dolor sit amet',
                'tipo_id' => 1,
                'published' => 1,
                'codice' => 'Lorem ipsum dolor sit amet',
                'calorie' => 1,
                'a_partire_da_prezzo' => 1.5,
                'seo_description' => 'Lorem ipsum dolor sit amet',
                'seo_keywords' => 'Lorem ipsum dolor sit amet',
                'destination_id' => 1,
                'cosa_comprende' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            ],
        ];
        parent::init();
    }
}
