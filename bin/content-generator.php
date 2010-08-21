<?php

ini_set('memory_limit','512M');

// Define path to application directory
defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application/'));


// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
        realpath(APPLICATION_PATH . '/../library'),
        get_include_path()
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
        'production',
        APPLICATION_PATH . '/configs/application.ini'
);

$application->getBootstrap()->bootstrap();

$model = new Admin_Model_ContentGenerator();
$success = $model->generateAll();

$text = '';
foreach( $model->getMessages() as $message) {
    $text .= "$message<br />";
}

$template = APPLICATION_PATH . '/../templates/emails/content-generator.html';
$htmlBody = str_replace('{_messages_}',$text,file_get_contents($template));

$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/email.ini', 'email');
$config = $config->mail;
$transp = array (
        'auth' =>$config->params->auth,
        'username' => $config->params->account,
        'password' => $config->params->password,
        'ssl' => $config->params->ssl,
        'port' => $config->params->port
);
$sender = $config->params->sender;
$recipients = $config->params->recipients;

$mailTransport = new Zend_Mail_Transport_Smtp ( $config->smtp, $transp );
$mail = new Zend_Mail ("UTF-8");
$mail->setFrom ($sender->email,$sender->name);

foreach($recipients as $recipient){
    $mail->addTo ($recipient->email,$recipient->name);
}
$mail->setBodyHtml($htmlBody);
$mail->setSubject ("Content Generation");
$mail->send ($mailTransport);

exit(0);