<?php
class MyZend_View_Helper_Messager extends Zend_View_Helper_Abstract
{

    /**
     * Stores the messages
     * @var array
     */
    protected $_messages = array();

    /**
     * Html class for the parent div for the messages
     * @var string
     */
    protected $_class = 'success';

    /**
     *
     * @param string/array $messages
     * @return object
     */
    function messager($messages = null)
    {
        if($messages !== null) {
            if (is_array($messages)) {
                $this->addMessages($messages);
            } else {
                $this->addMessage((string) $messages);
            }
        }
        return $this;
    }

    /**
     * Set the messages container class
     * @param string $class
     * @return MyZend_View_Helper_Messager
     */
    public function setClass($class)
    {
        $this->_class = $class;
        return $this;
    }

    /**
     * Add messages
     * @param array $messages
     * @return MyZend_View_Helper_Errors
     */
    public function addMessages($messages = array())
    {
        foreach($messages as $message) {
            $this->addMessage($message);
        }
        return $this;
    }

    /**
     * Add a message
     * @param string $message
     * @return MyZend_View_Helper_Errors
     */
    public function addMessage($message)
    {
        $this->_messages[] = $message;
        return $this;
    }

    /**
     * Renders the message
     * @param bool $escape
     * @return String
     */
    function render($escape = false)
    {
        $htmlMessage = '<div class="messages ' . $this->_class . '"><ul>';

        foreach($this->_messages as $message) {
            if ($escape) {
                $message = htmlentities($message, ENT_COMPAT, 'UTF-8');
            }
            $htmlMessage .= '<li>' . $message  . '</li>';
        }

        $htmlMessage  .= '</ul></div>';

        return count($this->_messages) ? $htmlMessage : null;
    }

}