<?php

namespace Application\Form;

use Zend\Form\Form;

class ObservationForm extends Form
{
    public function __construct($name = "ObservationForm")
    {
        parent::__construct($name);

        $this->setAttribute('enctype', "multipart/form-data");

        $this->setAttributes(array(
            'id' => "formNewObservation",
            'method' => "post",
            'action' => "/observations/save"
        ));

        $this->add(array(
            'name' => "formAction",
            'type'       => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'id' => "formAction"
            )
        ));

        $this->add(array(
            'name' => "id",
            'type'       => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'id' => "id"
            )
        ));

        $this->add(array(
            'name'       => "observation",
            'type'       => "Zend\Form\Element\Textarea",
            'attributes' => array(
                'id' => "observation",
                'class' => "form-control",
                'placeholder' => "Enter observation",
                'required' => "required",
                'rows' => 5
            ),
            'options'    => array(
                'label' => "Observation: *",
            ),
        ));
    }
}