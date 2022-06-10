<?php

namespace App;

use Monolog\Logger;
use Twig\Environment;

class Messenger {
    private Environment $view;
    private Logger $logger;
    private Database $database;

    private array $user_list = array(
        'admin' => 'password',
        'semen' => '1234'
    );

    public function __construct(Environment $view, Logger $logger, Database $database) {
        $this->view = $view;
        $this->logger = $logger;
        $this->database = $database;
        $view->display('index.twig');
        $this->printMessages();
    }

    public function addMessage($login, $pass, $message) {
        if (array_key_exists($login, $this->user_list) && $message != '' && $this->user_list[$login] == $pass) {
            $this->database->addMessage(date('d-m-y h:i:s'), $login, $message);

            $this->view->display('message.twig', [
                'date' => date('d-m-y h:i:s'),
                'user' => $login,
                'message' => $message
            ]);

            $this->logger->info('New message from: ' . $login);
        }
        else {
            $this->view->display('error.twig');
            $this->logger->info('Unknown user: ' . $login);
        }
    }

    public function printMessages() {
        $db_data = $this->database->getMessages();
        foreach($db_data as $cur){
            $this->view->display('message.twig', [
                'date' => $cur['date'],
                'user' => $cur['user'],
                'message' => $cur['message']
            ]);
        }
    }
}