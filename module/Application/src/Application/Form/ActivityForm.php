<?php

namespace Application\Form;

use Zend\Form\Form;

class ActivityForm extends Form
{
    public function __construct($name = "ActivityForm")
    {
        parent::__construct($name);

        $this->setAttribute('enctype', "multipart/form-data");

        $this->setAttributes(array(
            'id' => "formNewActivity",
            'method' => "post",
            'action' => "/activities/save"
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
            'name'       => "title",
            'type'       => "Zend\Form\Element\Text",
            'attributes' => array(
                'id' => "title",
                'class' => "form-control",
                'placeholder' => "Title activity",
                'required' => "required"
            ),
            'options'    => array(
                'label' => "Title: *",
            ),
        ));

        $this->add(array(
            'name'       => "description",
            'type'       => "Zend\Form\Element\Textarea",
            'attributes' => array(
                'id' => "description",
                'class' => "form-control",
                'placeholder' => "Enter description",
                'required' => "required",
                'rows' => 5
            ),
            'options'    => array(
                'label' => "Description: *",
            ),
        ));

        $this->add(array(
            'name'       => "observations",
            'type'       => "Zend\Form\Element\Textarea",
            'attributes' => array(
                'id' => "observations",
                'class' => "form-control",
                'placeholder' => "Enter observations",
                'required' => "required",
                'rows' => 5
            ),
            'options'    => array(
                'label' => "Observations: *",
            ),
        ));

        $this->add(array(
            'name'       => "branch",
            'type'       => "Zend\Form\Element\Text",
            'attributes' => array(
                'id' => "branch",
                'class' => "form-control",
                'placeholder' => "Enter observations",
                'required' => "required"
            ),
            'options'    => array(
                'label' => "Branch: *",
            ),
        ));

        $this->add(array(
            'name' => "typeActivity",
            'type' => "Zend\Form\Element\Select",
            'attributes' => array(
                'class' => "form-control",
                'id' => "typeActivity"
            ),
            'options' => array(
                'label' => 'Type: *',
                'value_options' => array(
                    '' => "Select one option",
                    '0' => "Activity",
                    '1' => "Sub-Activity",
                ),
            ),
        ));

        $this->add(array(
            'name'       => "listActivities",
            'type'       => "Zend\Form\Element\Text",
            'attributes' => array(
                'id' => "listActivities",
                'class' => "form-control",
                'placeholder' => "Enter activity",
                'required' => "required"
            ),
            'options'    => array(
                'label' => "Activity: *",
            ),
        ));

        $this->add(array(
            'name' => "status",
            'type' => "Zend\Form\Element\Select",
            'attributes' => array(
                'class' => "form-control",
                'id' => "status"
            ),
            'options' => array(
                'label' => 'Status: *',
                'value_options' => array(
                    '' => "Select one option",
                    '0' => "Working",
                    '1' => "Finished",
                ),
            ),
        ));

        $this->add(array(
            'name'       => "datePickerStart",
            'type'       => "Zend\Form\Element\Text",
            'attributes' => array(
                'id' => "datePickerStart",
                'class' => "form-control",
                'required' => "required"
            ),
            'options'    => array(
                'label' => "Start: *",
            ),
        ));

        $this->add(array(
            'name'       => "datePickerEnd",
            'type'       => "Zend\Form\Element\Text",
            'attributes' => array(
                'id' => "datePickerEnd",
                'class' => "form-control",
                'required' => "required"
            ),
            'options'    => array(
                'label' => "End: *",
            ),
        ));

        $this->add(array(
            'name'       => "currentlyWorking",
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => "currentlyWorking",
                'checked' => "checked"
            ),
            'options' => array(
                'label' => 'Currently working.',
                'use_hidden_element' => true,
                'checked_value' => 1,
                'unchecked_value' => 0,
            )
        ));

    }
}