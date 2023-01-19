<?php
namespace Policy\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Policy
{
    public $id;
    public $firstname;
    public $lastname;
    public $policynumber;
    public $startdate;
    public $enddate;
    public $premium;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->firstname = !empty($data['firstname']) ? $data['firstname'] : null;
        $this->lastname  = !empty($data['lastname']) ? $data['lastname'] : null;
        $this->policynumber  = !empty($data['policynumber']) ? $data['policynumber'] : null;
        $this->startdate  = !empty($data['startdate']) ? $data['startdate'] : null;
        $this->enddate  = !empty($data['enddate']) ? $data['enddate'] : null;
        $this->premium  = !empty($data['premium']) ? $data['premium'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        /* $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]); */

        $inputFilter->add([
            'name' => 'firstname',
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'lastname',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'startdate',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],

        ]);

        $inputFilter->add([
            'name' => 'enddate',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            /* 'validators' => [
               [
                'name' => 'LessThan',
                'options' => [
                    'max' => 'startdate',
                ],
               ]
            ], */ 
            'validators' => [
                [
                    'name' => 'Callback',
                    'options' => [
                        'messages' => [
                                \Zend\Validator\Callback::INVALID_VALUE => 'The end date should be greater than start date',
                        ],
                        'callback' => function($value, $context = array()) {                                  
                            $startDate = \DateTime::createFromFormat('d-m-Y', $context['startdate']);
                            $endDate = \DateTime::createFromFormat('d-m-Y', $value);
                            //return $endDate >= $startDate;
                            if($endDate > $startDate){
                                return false;
                            } else {
                                return true;
                            }
                        },
                    ]
                ],                          
        ],

        ]);

        $inputFilter->add([
            'name' => 'policynumber',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
               
            ],
        ]);

        $inputFilter->add([
            'name' => 'premium',
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'startdate'  => $this->startdate,
            'enddate'  => $this->enddate,
            'policynumber'  => $this->policynumber,
            'premium'  => $this->premium,
        ];
    }
}