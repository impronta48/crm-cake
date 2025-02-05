<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Attivitum Entity
 *
 * @property int $id
 * @property string $name
 * @property int|null $progetto_id
 * @property int|null $cliente_id
 * @property \Cake\I18n\Date|null $DataPresentazione
 * @property \Cake\I18n\Date|null $DataApprovazione
 * @property \Cake\I18n\Date|null $DataInizio
 * @property \Cake\I18n\Date|null $DataFine
 * @property \Cake\I18n\Date|null $DataFinePrevista
 * @property float|null $NumOre
 * @property int|null $NumOreConsuntivo
 * @property string|null $OffertaAlCliente
 * @property string|null $ImportoAcquisito
 * @property string|null $NettoOra
 * @property int|null $OreUfficio
 * @property string|null $MotivazioneRit
 * @property float|null $Utile
 * @property string|null $Note
 * @property int|null $area_id
 * @property int|null $azienda_id
 * @property bool|null $chiusa
 * @property string|null $alias
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Progetto $progetto
 * @property \App\Model\Entity\Cliente $cliente
 * @property \App\Model\Entity\Area $area
 * @property \App\Model\Entity\Azienda $azienda
 */
class Attivitum extends Entity
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
        'progetto_id' => true,
        'cliente_id' => true,
        'DataPresentazione' => true,
        'DataApprovazione' => true,
        'DataInizio' => true,
        'DataFine' => true,
        'DataFinePrevista' => true,
        'NumOre' => true,
        'NumOreConsuntivo' => true,
        'OffertaAlCliente' => true,
        'ImportoAcquisito' => true,
        'NettoOra' => true,
        'OreUfficio' => true,
        'MotivazioneRit' => true,
        'Utile' => true,
        'Note' => true,
        'area_id' => true,
        'azienda_id' => true,
        'chiusa' => true,
        'alias' => true,
        'created' => true,
        'modified' => true,
        'progetto' => true,
        'cliente' => true,
        'area' => true,
        'azienda' => true,
    ];
}
