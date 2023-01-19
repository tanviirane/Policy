<?php
namespace Policy\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class PolicyTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getPolicy($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function savePolicy(Policy $policy)
    {
        $data = [
            'firstname' => $policy->firstname,
            'lastname'  => $policy->lastname,
            'policynumber'  => $policy->policynumber,
            'startdate'  => $policy->startdate,
            'enddate'  => $policy->enddate,
            'premium'  => $policy->premium,
        ];

        $id = (int) $policy->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getPolicy($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update policy with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deletePolicy($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}