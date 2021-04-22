<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Attivita $attivita
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Attivita'), ['action' => 'edit', $attivita->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Attivita'), ['action' => 'delete', $attivita->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attivita->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Attivita'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Attivita'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="attivita view content">
            <h3><?= h($attivita->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($attivita->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Progetto') ?></th>
                    <td><?= $attivita->has('progetto') ? $this->Html->link($attivita->progetto->name, ['controller' => 'Progetti', 'action' => 'view', $attivita->progetto->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Area') ?></th>
                    <td><?= $attivita->has('area') ? $this->Html->link($attivita->area->name, ['controller' => 'Aree', 'action' => 'view', $attivita->area->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($attivita->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cliente Id') ?></th>
                    <td><?= $this->Number->format($attivita->cliente_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('NumOre') ?></th>
                    <td><?= $this->Number->format($attivita->NumOre) ?></td>
                </tr>
                <tr>
                    <th><?= __('NumOreConsuntivo') ?></th>
                    <td><?= $this->Number->format($attivita->NumOreConsuntivo) ?></td>
                </tr>
                <tr>
                    <th><?= __('OffertaAlCliente') ?></th>
                    <td><?= $this->Number->format($attivita->OffertaAlCliente) ?></td>
                </tr>
                <tr>
                    <th><?= __('ImportoAcquisito') ?></th>
                    <td><?= $this->Number->format($attivita->ImportoAcquisito) ?></td>
                </tr>
                <tr>
                    <th><?= __('NettoOra') ?></th>
                    <td><?= $this->Number->format($attivita->NettoOra) ?></td>
                </tr>
                <tr>
                    <th><?= __('OreUfficio') ?></th>
                    <td><?= $this->Number->format($attivita->OreUfficio) ?></td>
                </tr>
                <tr>
                    <th><?= __('Utile') ?></th>
                    <td><?= $this->Number->format($attivita->Utile) ?></td>
                </tr>
                <tr>
                    <th><?= __('Azienda Id') ?></th>
                    <td><?= $this->Number->format($attivita->azienda_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('DataPresentazione') ?></th>
                    <td><?= h($attivita->DataPresentazione) ?></td>
                </tr>
                <tr>
                    <th><?= __('DataApprovazione') ?></th>
                    <td><?= h($attivita->DataApprovazione) ?></td>
                </tr>
                <tr>
                    <th><?= __('DataInizio') ?></th>
                    <td><?= h($attivita->DataInizio) ?></td>
                </tr>
                <tr>
                    <th><?= __('DataFine') ?></th>
                    <td><?= h($attivita->DataFine) ?></td>
                </tr>
                <tr>
                    <th><?= __('DataFinePrevista') ?></th>
                    <td><?= h($attivita->DataFinePrevista) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($attivita->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($attivita->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Chiusa') ?></th>
                    <td><?= $attivita->chiusa ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('MotivazioneRit') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($attivita->MotivazioneRit)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Note') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($attivita->Note)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Alias') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($attivita->alias)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Aliases') ?></h4>
                <?php if (!empty($attivita->aliases)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Attivita Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($attivita->aliases as $aliases) : ?>
                        <tr>
                            <td><?= h($aliases->id) ?></td>
                            <td><?= h($aliases->name) ?></td>
                            <td><?= h($aliases->attivita_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Aliases', 'action' => 'view', $aliases->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Aliases', 'action' => 'edit', $aliases->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Aliases', 'action' => 'delete', $aliases->id], ['confirm' => __('Are you sure you want to delete # {0}?', $aliases->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Cespiticalendario') ?></h4>
                <?php if (!empty($attivita->cespiticalendario)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Cespite Id') ?></th>
                            <th><?= __('Event Type Id') ?></th>
                            <th><?= __('Utilizzatore Esterno') ?></th>
                            <th><?= __('Start') ?></th>
                            <th><?= __('End') ?></th>
                            <th><?= __('Repeated') ?></th>
                            <th><?= __('Note') ?></th>
                            <th><?= __('EventGroup') ?></th>
                            <th><?= __('Attivita Id') ?></th>
                            <th><?= __('Faseattivita Id') ?></th>
                            <th><?= __('Prezzo Affitto') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($attivita->cespiticalendario as $cespiticalendario) : ?>
                        <tr>
                            <td><?= h($cespiticalendario->id) ?></td>
                            <td><?= h($cespiticalendario->user_id) ?></td>
                            <td><?= h($cespiticalendario->cespite_id) ?></td>
                            <td><?= h($cespiticalendario->event_type_id) ?></td>
                            <td><?= h($cespiticalendario->utilizzatore_esterno) ?></td>
                            <td><?= h($cespiticalendario->start) ?></td>
                            <td><?= h($cespiticalendario->end) ?></td>
                            <td><?= h($cespiticalendario->repeated) ?></td>
                            <td><?= h($cespiticalendario->note) ?></td>
                            <td><?= h($cespiticalendario->eventGroup) ?></td>
                            <td><?= h($cespiticalendario->attivita_id) ?></td>
                            <td><?= h($cespiticalendario->faseattivita_id) ?></td>
                            <td><?= h($cespiticalendario->prezzo_affitto) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Cespiticalendario', 'action' => 'view', $cespiticalendario->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Cespiticalendario', 'action' => 'edit', $cespiticalendario->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Cespiticalendario', 'action' => 'delete', $cespiticalendario->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cespiticalendario->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Ddt') ?></h4>
                <?php if (!empty($attivita->ddt)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Attivita Id') ?></th>
                            <th><?= __('Data Inizio Trasporto') ?></th>
                            <th><?= __('Destinatario') ?></th>
                            <th><?= __('Destinatario Via') ?></th>
                            <th><?= __('Destinatario Cap') ?></th>
                            <th><?= __('Destinatario Citta') ?></th>
                            <th><?= __('Destinatario Provincia') ?></th>
                            <th><?= __('Luogo') ?></th>
                            <th><?= __('Luogo Via') ?></th>
                            <th><?= __('Luogo Cap') ?></th>
                            <th><?= __('Luogo Citta') ?></th>
                            <th><?= __('Luogo Provincia') ?></th>
                            <th><?= __('Legenda Causale Trasporto Id') ?></th>
                            <th><?= __('Legenda Porto Id') ?></th>
                            <th><?= __('N Colli') ?></th>
                            <th><?= __('Vettore Id') ?></th>
                            <th><?= __('Note') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($attivita->ddt as $ddt) : ?>
                        <tr>
                            <td><?= h($ddt->id) ?></td>
                            <td><?= h($ddt->attivita_id) ?></td>
                            <td><?= h($ddt->data_inizio_trasporto) ?></td>
                            <td><?= h($ddt->destinatario) ?></td>
                            <td><?= h($ddt->destinatario_via) ?></td>
                            <td><?= h($ddt->destinatario_cap) ?></td>
                            <td><?= h($ddt->destinatario_citta) ?></td>
                            <td><?= h($ddt->destinatario_provincia) ?></td>
                            <td><?= h($ddt->luogo) ?></td>
                            <td><?= h($ddt->luogo_via) ?></td>
                            <td><?= h($ddt->luogo_cap) ?></td>
                            <td><?= h($ddt->luogo_citta) ?></td>
                            <td><?= h($ddt->luogo_provincia) ?></td>
                            <td><?= h($ddt->legenda_causale_trasporto_id) ?></td>
                            <td><?= h($ddt->legenda_porto_id) ?></td>
                            <td><?= h($ddt->n_colli) ?></td>
                            <td><?= h($ddt->vettore_id) ?></td>
                            <td><?= h($ddt->note) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Ddt', 'action' => 'view', $ddt->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Ddt', 'action' => 'edit', $ddt->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Ddt', 'action' => 'delete', $ddt->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ddt->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Faseattivita') ?></h4>
                <?php if (!empty($attivita->faseattivita)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Attivita Id') ?></th>
                            <th><?= __('Descrizione') ?></th>
                            <th><?= __('Qta') ?></th>
                            <th><?= __('Um') ?></th>
                            <th><?= __('Costou') ?></th>
                            <th><?= __('Vendutou') ?></th>
                            <th><?= __('Legenda Stato Attivita Id') ?></th>
                            <th><?= __('Persona Id') ?></th>
                            <th><?= __('Note') ?></th>
                            <th><?= __('Legenda Codici Iva Id') ?></th>
                            <th><?= __('Sc1') ?></th>
                            <th><?= __('Sc2') ?></th>
                            <th><?= __('Sc3') ?></th>
                            <th><?= __('Entrata') ?></th>
                            <th><?= __('Data') ?></th>
                            <th><?= __('QtaUtilizzata') ?></th>
                            <th><?= __('Cespite Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($attivita->faseattivita as $faseattivita) : ?>
                        <tr>
                            <td><?= h($faseattivita->id) ?></td>
                            <td><?= h($faseattivita->attivita_id) ?></td>
                            <td><?= h($faseattivita->Descrizione) ?></td>
                            <td><?= h($faseattivita->qta) ?></td>
                            <td><?= h($faseattivita->um) ?></td>
                            <td><?= h($faseattivita->costou) ?></td>
                            <td><?= h($faseattivita->vendutou) ?></td>
                            <td><?= h($faseattivita->legenda_stato_attivita_id) ?></td>
                            <td><?= h($faseattivita->persona_id) ?></td>
                            <td><?= h($faseattivita->note) ?></td>
                            <td><?= h($faseattivita->legenda_codici_iva_id) ?></td>
                            <td><?= h($faseattivita->sc1) ?></td>
                            <td><?= h($faseattivita->sc2) ?></td>
                            <td><?= h($faseattivita->sc3) ?></td>
                            <td><?= h($faseattivita->entrata) ?></td>
                            <td><?= h($faseattivita->data) ?></td>
                            <td><?= h($faseattivita->qtaUtilizzata) ?></td>
                            <td><?= h($faseattivita->cespite_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Faseattivita', 'action' => 'view', $faseattivita->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Faseattivita', 'action' => 'edit', $faseattivita->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Faseattivita', 'action' => 'delete', $faseattivita->id], ['confirm' => __('Are you sure you want to delete # {0}?', $faseattivita->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Fattureemesse') ?></h4>
                <?php if (!empty($attivita->fattureemesse)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Attivita Id') ?></th>
                            <th><?= __('Progressivo') ?></th>
                            <th><?= __('AnnoFatturazione') ?></th>
                            <th><?= __('Motivazione') ?></th>
                            <th><?= __('Provenienzasoldi Id') ?></th>
                            <th><?= __('ScadPagamento') ?></th>
                            <th><?= __('Data') ?></th>
                            <th><?= __('AnticipoFatture') ?></th>
                            <th><?= __('CondPagamento') ?></th>
                            <th><?= __('FineMese') ?></th>
                            <th><?= __('Competenza') ?></th>
                            <th><?= __('Soddisfatta') ?></th>
                            <th><?= __('Serie') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('IdFattureInCloud') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($attivita->fattureemesse as $fattureemesse) : ?>
                        <tr>
                            <td><?= h($fattureemesse->id) ?></td>
                            <td><?= h($fattureemesse->attivita_id) ?></td>
                            <td><?= h($fattureemesse->Progressivo) ?></td>
                            <td><?= h($fattureemesse->AnnoFatturazione) ?></td>
                            <td><?= h($fattureemesse->Motivazione) ?></td>
                            <td><?= h($fattureemesse->provenienzasoldi_id) ?></td>
                            <td><?= h($fattureemesse->ScadPagamento) ?></td>
                            <td><?= h($fattureemesse->data) ?></td>
                            <td><?= h($fattureemesse->AnticipoFatture) ?></td>
                            <td><?= h($fattureemesse->CondPagamento) ?></td>
                            <td><?= h($fattureemesse->FineMese) ?></td>
                            <td><?= h($fattureemesse->Competenza) ?></td>
                            <td><?= h($fattureemesse->Soddisfatta) ?></td>
                            <td><?= h($fattureemesse->Serie) ?></td>
                            <td><?= h($fattureemesse->created) ?></td>
                            <td><?= h($fattureemesse->modified) ?></td>
                            <td><?= h($fattureemesse->IdFattureInCloud) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Fattureemesse', 'action' => 'view', $fattureemesse->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Fattureemesse', 'action' => 'edit', $fattureemesse->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Fattureemesse', 'action' => 'delete', $fattureemesse->id], ['confirm' => __('Are you sure you want to delete # {0}?', $fattureemesse->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Fatturericevute') ?></h4>
                <?php if (!empty($attivita->fatturericevute)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Attivita Id') ?></th>
                            <th><?= __('Progressivo') ?></th>
                            <th><?= __('AnnoFatturazione') ?></th>
                            <th><?= __('Motivazione') ?></th>
                            <th><?= __('Provenienza') ?></th>
                            <th><?= __('ScadPagamento') ?></th>
                            <th><?= __('Importo') ?></th>
                            <th><?= __('DataFattura') ?></th>
                            <th><?= __('Fornitore Id') ?></th>
                            <th><?= __('Legenda Cat Spesa Id') ?></th>
                            <th><?= __('Imponibile') ?></th>
                            <th><?= __('Iva') ?></th>
                            <th><?= __('FuoriIva') ?></th>
                            <th><?= __('RitenutaAcconto') ?></th>
                            <th><?= __('ScadenzaRitenutaAcconto') ?></th>
                            <th><?= __('Pagato') ?></th>
                            <th><?= __('PagatoRitenutaAcconto') ?></th>
                            <th><?= __('Faseattivita Id') ?></th>
                            <th><?= __('Protocollo Ricezione') ?></th>
                            <th><?= __('Legenda Tipo Documento Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($attivita->fatturericevute as $fatturericevute) : ?>
                        <tr>
                            <td><?= h($fatturericevute->id) ?></td>
                            <td><?= h($fatturericevute->attivita_id) ?></td>
                            <td><?= h($fatturericevute->progressivo) ?></td>
                            <td><?= h($fatturericevute->annoFatturazione) ?></td>
                            <td><?= h($fatturericevute->motivazione) ?></td>
                            <td><?= h($fatturericevute->provenienza) ?></td>
                            <td><?= h($fatturericevute->scadPagamento) ?></td>
                            <td><?= h($fatturericevute->importo) ?></td>
                            <td><?= h($fatturericevute->dataFattura) ?></td>
                            <td><?= h($fatturericevute->fornitore_id) ?></td>
                            <td><?= h($fatturericevute->legenda_cat_spesa_id) ?></td>
                            <td><?= h($fatturericevute->imponibile) ?></td>
                            <td><?= h($fatturericevute->iva) ?></td>
                            <td><?= h($fatturericevute->fuoriIva) ?></td>
                            <td><?= h($fatturericevute->ritenutaAcconto) ?></td>
                            <td><?= h($fatturericevute->scadenzaRitenutaAcconto) ?></td>
                            <td><?= h($fatturericevute->pagato) ?></td>
                            <td><?= h($fatturericevute->pagatoRitenutaAcconto) ?></td>
                            <td><?= h($fatturericevute->faseattivita_id) ?></td>
                            <td><?= h($fatturericevute->protocollo_ricezione) ?></td>
                            <td><?= h($fatturericevute->legenda_tipo_documento_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Fatturericevute', 'action' => 'view', $fatturericevute->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Fatturericevute', 'action' => 'edit', $fatturericevute->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Fatturericevute', 'action' => 'delete', $fatturericevute->id], ['confirm' => __('Are you sure you want to delete # {0}?', $fatturericevute->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Ordini') ?></h4>
                <?php if (!empty($attivita->ordini)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('DataOrdine') ?></th>
                            <th><?= __('Fornitore Id') ?></th>
                            <th><?= __('Attivita Id') ?></th>
                            <th><?= __('Note') ?></th>
                            <th><?= __('Co') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($attivita->ordini as $ordini) : ?>
                        <tr>
                            <td><?= h($ordini->id) ?></td>
                            <td><?= h($ordini->dataOrdine) ?></td>
                            <td><?= h($ordini->fornitore_id) ?></td>
                            <td><?= h($ordini->attivita_id) ?></td>
                            <td><?= h($ordini->note) ?></td>
                            <td><?= h($ordini->co) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Ordini', 'action' => 'view', $ordini->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Ordini', 'action' => 'edit', $ordini->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Ordini', 'action' => 'delete', $ordini->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ordini->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Primanota') ?></h4>
                <?php if (!empty($attivita->primanota)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Data') ?></th>
                            <th><?= __('Descr') ?></th>
                            <th><?= __('Importo') ?></th>
                            <th><?= __('Attivita Id') ?></th>
                            <th><?= __('Faseattivita Id') ?></th>
                            <th><?= __('Legenda Cat Spesa Id') ?></th>
                            <th><?= __('Provenienzasoldi Id') ?></th>
                            <th><?= __('Fatturaemessa Id') ?></th>
                            <th><?= __('Fatturaricevuta Id') ?></th>
                            <th><?= __('Assegno') ?></th>
                            <th><?= __('Note') ?></th>
                            <th><?= __('Num Documento') ?></th>
                            <th><?= __('Data Documento') ?></th>
                            <th><?= __('Persona Id') ?></th>
                            <th><?= __('Persona Descr') ?></th>
                            <th><?= __('Imponibile') ?></th>
                            <th><?= __('Iva') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($attivita->primanota as $primanota) : ?>
                        <tr>
                            <td><?= h($primanota->id) ?></td>
                            <td><?= h($primanota->data) ?></td>
                            <td><?= h($primanota->descr) ?></td>
                            <td><?= h($primanota->importo) ?></td>
                            <td><?= h($primanota->attivita_id) ?></td>
                            <td><?= h($primanota->faseattivita_id) ?></td>
                            <td><?= h($primanota->legenda_cat_spesa_id) ?></td>
                            <td><?= h($primanota->provenienzasoldi_id) ?></td>
                            <td><?= h($primanota->fatturaemessa_id) ?></td>
                            <td><?= h($primanota->fatturaricevuta_id) ?></td>
                            <td><?= h($primanota->assegno) ?></td>
                            <td><?= h($primanota->note) ?></td>
                            <td><?= h($primanota->num_documento) ?></td>
                            <td><?= h($primanota->data_documento) ?></td>
                            <td><?= h($primanota->persona_id) ?></td>
                            <td><?= h($primanota->persona_descr) ?></td>
                            <td><?= h($primanota->imponibile) ?></td>
                            <td><?= h($primanota->iva) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Primanota', 'action' => 'view', $primanota->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Primanota', 'action' => 'edit', $primanota->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Primanota', 'action' => 'delete', $primanota->id], ['confirm' => __('Are you sure you want to delete # {0}?', $primanota->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
