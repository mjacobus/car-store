<?php
/**
 * Displays login information
 * @author marcelo.jacobus
 */
class Zend_View_Helper_LoginBar extends Zend_View_Helper_Url
{

    /**
     * Display login info
     * @return String
     */
    public function loginBar()
    {
        $html = '';
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getIdentity();
            $html = $this->render($user);
        }

        return $html;
    }

    /**
     *
     * @param User $user
     * @return display login info
     */
    private function render(User $user)
    {

        $profileUrl = $this->url(array(
                'module' => 'default',
                'controller' => 'profile',
                'action' => null),null,true);

        $logoutUrl = $this->url(array(
                'module' => 'default',
                'controller' => 'authentication',
                'action' => 'logout'),null,true);


        return "<div id='login-bar'>
            Logado como {$user->name}.
            <a href='$profileUrl'>Editar Profile</a>
            <a href='$logoutUrl'>Sair</a>
        </div>";
    }
}