<?php

namespace App;

use Exception;
use PDO;

class Database
{
    private PDO $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                'mysql:host=localhost;dbname=my_messenger',
                'user123',
                'PASSWORD'
            );
            $this->pdo->exec('CREATE TABLE IF NOT EXISTS messages(date_time varchar(255), login varchar(255), text varchar(255))');
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function addMessage($date, $login, $text) {
        $command = 'INSERT INTO messages VALUES (:date, :login , :text)';
        $tmp = $this->pdo->prepare($command);
        $tmp->bindParam('date', $date, PDO::PARAM_STR);
        $tmp->bindParam('login', $login, PDO::PARAM_STR);
        $tmp->bindParam('text', $text, PDO::PARAM_STR);
        $tmp->execute();
    }

    public function getMessages(): array
    {
        $result = array();
        $statement = $this->pdo->query('SELECT * FROM messages');

        foreach ($statement as $row) {
            $result[] = [
                'date' => $row['date_time'],
                'user' => $row['login'],
                'message' => $row['text']
            ];
        }
        return $result;
    }
}