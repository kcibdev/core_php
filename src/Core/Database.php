<?php

namespace Hp\LearningRoute\Core;

use PDOException;
use PDO;
use Hp\LearningRoute\Common\Functions;

class Database
{
    public $connection;
    public $statement;

    public function __construct()
    {
        $db_user = $_ENV['DB_USER'];
        $db_password = $_ENV['DB_PASSWORD'];
        $db_host = $_ENV['DB_HOST'];
        $db_name = $_ENV['DB_NAME'];


        $config = [
            'host' => $db_host,
            'port' => 3306,
            'dbname' => $db_name,
            'charset' => 'utf8mb4',
        ];

        $dsn = 'mysql:' . http_build_query($config, '', ';');

        $this->connection = new PDO($dsn, $db_user, $db_password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function query($query, $params = [])
    {
        try {
            $this->statement = $this->connection->prepare($query);

            $this->statement->execute($params);

            return $this;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                Functions::dump($e);
                Functions::abort($e->errorInfo[2], SC500);
                // handle the error
                Functions::abort("Error: Email address already exists.", SC400);
            }
            //TODO: Functions::abort('An error occurred please try again', 500);

            Functions::abort($e->errorInfo[2], SC500);
        }
    }

    public function all()
    {
        try {
            //code...

            $result = $this->statement->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            Functions::abort($e->errorInfo[2], SC500);
        }
    }

    public function find()
    {
        try {
            $result = $this->statement->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            Functions::abort($e->errorInfo[2], SC500);
        }
    }

    public function count()
    {
        try {
            $result = $this->statement->rowCount();

            return $result;
        } catch (PDOException $e) {
            Functions::abort($e->errorInfo[2], SC500);
        }
    }

    public function id()
    {
        try {
            $result = $this->connection->lastInsertId();

            return $result;
        } catch (PDOException $e) {
            Functions::abort($e->errorInfo[2], SC500);
        }
    }

    public function findOrFail()
    {
        $result = $this->find();

        if (!$result) {
            Functions::abort("Server error, please try again!", SC500);
        }

        return $result;
    }
}
