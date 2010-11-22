<?php
/**
 * This is the regular del form
 *
 * @author marcelo.jacobus
 */
class Admin_Form_Del extends App_Form
{
    public function __construct(array $params = array())
    {
        parent::__construct();
        $this->setAttrib('class','del');

        foreach($params as $name => $value) {
            
            $element = $this->getHiddenElement($name);
            $element->setValue($value);
            $this->addElement($element);
        }

        $cancel = new Zend_Form_Element_Submit('cancel');
        $cancel->setValue('cancel')->setLabel('Não');
        $this->addElement($cancel);

        $confirm = new Zend_Form_Element_Submit('confirm');
        $confirm->setValue('confirm')->setLabel('Sim');
        $this->addElement($confirm);
    }
}