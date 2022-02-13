<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Attivita Controller
 *
 * @property \App\Model\Table\AttivitaTable $Attivita
 * @method \App\Model\Entity\Attivita[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AttivitaController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Progetti', 'Clienti', 'Aree', 'Aziende'],
        ];
        $attivita = $this->paginate($this->Attivita);

        $this->set(compact('attivita'));
    }

    /**
     * View method
     *
     * @param string|null $id Attivita id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $attivita = $this->Attivita->get($id, [
            'contain' => ['Progetti', 'Clienti', 'Aree', 'Aziende', 'Aliases', 'Cespiticalendario', 'Ddt', 'Faseattivita', 'Fattureemesse', 'Fatturericevute', 'Ordini', 'Primanota'],
        ]);

        $this->set(compact('attivita'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $attivita = $this->Attivita->newEmptyEntity();
        if ($this->request->is('post')) {
            $attivita = $this->Attivita->patchEntity($attivita, $this->request->getData());
            if ($this->Attivita->save($attivita)) {
                $this->Flash->success(__('The attivita has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The attivita could not be saved. Please, try again.'));
        }
        $progetti = $this->Attivita->Progetti->find('list', ['limit' => 200]);
        $clienti = $this->Attivita->Clienti->find('list', ['limit' => 200]);
        $aree = $this->Attivita->Aree->find('list', ['limit' => 200]);
        $aziende = $this->Attivita->Aziende->find('list', ['limit' => 200]);
        $this->set(compact('attivita', 'progetti', 'clienti', 'aree', 'aziende'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Attivita id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $attivita = $this->Attivita->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attivita = $this->Attivita->patchEntity($attivita, $this->request->getData());
            if ($this->Attivita->save($attivita)) {
                $this->Flash->success(__('The attivita has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The attivita could not be saved. Please, try again.'));
        }
        $progetti = $this->Attivita->Progetti->find('list', ['limit' => 200]);
        $clienti = $this->Attivita->Clienti->find('list', ['limit' => 200]);
        $aree = $this->Attivita->Aree->find('list', ['limit' => 200]);
        $aziende = $this->Attivita->Aziende->find('list', ['limit' => 200]);
        $this->set(compact('attivita', 'progetti', 'clienti', 'aree', 'aziende'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Attivita id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attivita = $this->Attivita->get($id);
        if ($this->Attivita->delete($attivita)) {
            $this->Flash->success(__('The attivita has been deleted.'));
        } else {
            $this->Flash->error(__('The attivita could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
