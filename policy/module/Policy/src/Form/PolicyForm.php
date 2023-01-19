<?php

namespace Policy\Form;

use Zend\Form\Form;
use Zend\Form\Element;


class PolicyForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('policy');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'firstname',
            'type' => 'text',
            'options' => [
                'label' => 'First Name',
            ],
        ]);
        $this->add([
            'name' => 'lastname',
            'type' => 'text',
            'options' => [
                'label' => 'Last Name',
            ],
        ]);
        $this->add([
            'name' => 'startdate',
            'type' => Element\Date::class,
            'options' => [
                'label' => 'Start Date',
                'format' => 'Y-m-d',
            ],
            'attributes' => [
                'min' => date('Y-m-d'),
                'step' => '1', // days; default step interval is 1 day
            ],
        ]);
        $this->add([
            'name' => 'enddate',
            'type' => Element\Date::class,
            'options' => [
                'label' => 'End Date',
                'format' => 'Y-m-d',
            ],
            'attributes' => [
                'min' => date('Y-m-d'),
                'step' => '1', // days; default step interval is 1 day
            ],
        ]);
        $this->add([
            'name' => 'policynumber',
            'type' => 'text',
            'options' => [
                'label' => 'Policy Number',
            ],
        ]);
        $this->add([
            'name' => 'premium',
            'type' => 'text',
            'options' => [
                'label' => 'Premium',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}