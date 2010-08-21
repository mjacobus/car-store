<?php
/**
 * This is the regular del form
 *
 * @author marcelo.jacobus
 */
class Admin_Form_Del extends Admin_Form_Abstract
{
    public function __construct($registerId = null)
    {
        parent::__construct();
        $this->setAttrib('class','del');

        $id = new Zend_Form_Element_Hidden('id');
        $id->setValue($registerId);
        $id->removeDecorator('tag');
        $this->addElement($id);


        $cancel = new Zend_Form_Element_Submit('cancel');
        $cancel->setValue('cancel')->setLabel('Não');
        $this->addElement($cancel);

        $confirm = new Zend_Form_Element_Submit('confirm');
        $confirm->setValue('confirm')->setLabel('Sim');
        $this->addElement($confirm);
    }
}