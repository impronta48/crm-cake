<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Persone Entity
 *
 * @property int $id
 * @property string|null $Nome
 * @property string|null $Cognome
 * @property string|null $Indirizzo
 * @property string|null $Citta
 * @property string|null $Provincia
 * @property string|null $Nazione
 * @property string|null $CAP
 * @property string|null $TelefonoDomicilio
 * @property string|null $TelefonoUfficio
 * @property \Cake\I18n\Date|null $DataDiNascita
 * @property \Cake\I18n\DateTime|null $UltimoContatto
 * @property string|null $Nota
 * @property string|null $Titolo
 * @property string|null $Carica
 * @property string|null $Societa
 * @property string|null $SitoWeb
 * @property string|null $ModificatoDa
 * @property string|null $EMail
 * @property string|null $Fax
 * @property string|null $Cellulare
 * @property string|null $IM
 * @property string|null $Categorie
 * @property string|null $DisplayName
 * @property string|null $piva
 * @property string|null $cf
 * @property \Cake\I18n\DateTime $modified
 * @property \Cake\I18n\DateTime $created
 * @property string|null $iban
 * @property string|null $NomeBanca
 * @property string|null $altroIndirizzo
 * @property string|null $altraCitta
 * @property string|null $altroCap
 * @property string|null $altraProv
 * @property string|null $altraNazione
 * @property bool|null $EntePubblico
 * @property string|null $codiceIPA
 * @property string $indirizzoPEC
 */
class Persone extends Entity
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
        'Nome' => true,
        'Cognome' => true,
        'Indirizzo' => true,
        'Citta' => true,
        'Provincia' => true,
        'Nazione' => true,
        'CAP' => true,
        'TelefonoDomicilio' => true,
        'TelefonoUfficio' => true,
        'DataDiNascita' => true,
        'UltimoContatto' => true,
        'Nota' => true,
        'Titolo' => true,
        'Carica' => true,
        'Societa' => true,
        'SitoWeb' => true,
        'ModificatoDa' => true,
        'EMail' => true,
        'Fax' => true,
        'Cellulare' => true,
        'IM' => true,
        'Categorie' => true,
        'DisplayName' => true,
        'piva' => true,
        'cf' => true,
        'modified' => true,
        'created' => true,
        'iban' => true,
        'NomeBanca' => true,
        'altroIndirizzo' => true,
        'altraCitta' => true,
        'altroCap' => true,
        'altraProv' => true,
        'altraNazione' => true,
        'EntePubblico' => true,
        'codiceIPA' => true,
        'indirizzoPEC' => true,
    ];
}
