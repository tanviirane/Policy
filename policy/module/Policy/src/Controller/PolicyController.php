<?php
namespace Policy\Controller;

use Policy\Model\PolicyTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Policy\Form\PolicyForm;
use Policy\Model\Policy;

class PolicyController extends AbstractActionController
{
    private $table;

    public function __construct(PolicyTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'policies' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new PolicyForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $policy = new Policy();
        $form->setInputFilter($policy->getInputFilter());
        $form->setData($request->getPost());
        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $policy->exchangeArray($form->getData());
        $this->table->savePolicy($policy);
        return $this->redirect()->toRoute('policy');
    }

    public function editAction()
    {  
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('policy', ['action' => 'add']);
        }

        // Retrieve the policy with the specified id. Doing so raises
        // an exception if the policy is not found, which should result
        // in redirecting to the landing page.
        try {
            $policy = $this->table->getPolicy($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('policy', ['action' => 'index']);
        }

        $form = new PolicyForm();
        $form->bind($policy);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($policy->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->savePolicy($policy);

        // Redirect to policy list
        return $this->redirect()->toRoute('policy', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('policy');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deletePolicy($id);
            }

            // Redirect to list of policies
            return $this->redirect()->toRoute('policy');
        }

        return [
            'id'    => $id,
            'policy' => $this->table->getPolicy($id),
        ];
    }
}