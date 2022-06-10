<?php
namespace App;

use Monolog\Logger;
use Twig\Environment;

class Messenger {
    private Environment $view;
    private Logger $logger;

    private const dirname = __DIR__ . '/messages.json';

    private array $user_list = array(
        'admin' => 'password',
        'semen' => '1234'
    );

    public function __construct(Environment $view, Logger $logger) {
        $this->view = $view;
        $this->logger = $logger;
        $view->display('index.twig');
        $this->printMessages();
    }

    public function addMessage($login, $pass, $message) {
        if (array_key_exists($login, $this->user_list) && $message != '' && $this->user_list[$login] == $pass) {
            $json_data = json_decode(file_get_contents(self::dirname));
            $newMessage = (object)['date' => date('d-m-y h:i:s'), 'user' => $login, 'message' => $message];
            $json_data[] = $newMessage;
            file_put_contents(self::dirname, json_encode($json_data));

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
        $json_data = json_decode(file_get_contents(self::dirname));
        foreach($json_data as $cur){
            $this->view->display('message.twig', [
                'date' => $cur->date,
                'user' => $cur->user,
                'message' => $cur->message
            ]);
        }
    }
}