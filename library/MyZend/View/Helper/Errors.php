<?php
class MyZend_View_Helper_Errors extends MyZend_View_Helper_Messager
{

    /**
     * Html class for the parent div for the messages
     * @var string
     */
    protected $_class = 'error';

    /**
     * Sets errors
     * @param string/array $messages
     * @return object
     */
    function errors($messages = null)
    {
       parent::messager($messages);
        return $this;
    }
 
}