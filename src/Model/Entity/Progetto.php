<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Progetto Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|resource|null $DescrizioneProgetto
 * @property int|null $area_id
 * @property float|null $PercentualeIVA
 * @property string|null $Nota
 *
 * @property \App\Model\Entity\Area $area
 * @property \App\Model\Entity\Attivita[] $attivita
 */
class Progetto extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected array $_accessible = [
        'name' => true,
        'DescrizioneProgetto' => true,
        'area_id' => true,
        'PercentualeIVA' => true,
        'Nota' => true,
        'area' => true,
        'attivita' => true,
    ];
}
