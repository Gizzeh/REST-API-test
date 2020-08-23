<?php

class DataBase {

    private $pdo;

    public function __construct()
    {
        $this->connect();
    }

    /**
     *  Function trying to connect to Data Base
     *  If connection failed, will be trow an exception with message
     */
    private function connect() {
        $dsn = 'mysql:host=localhost;dbname=api_test_bd';

        try {
            $this->pdo = new PDO($dsn, 'root', 'root');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch ( PDOException $e ) {
            echo json_encode($e->getMessage());
        }
    }

    /**
     * Try to make actions like UPDATE - DELETE - INSERT
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function execute(string $sql, array $params = [] ) {
        $statement = $this->pdo->prepare( $sql );
        return $statement->execute( $params );
    }

    /**
     * Try to return associative array with data by SELECT method
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function query(string $sql, array $params = [] ) {
        $statement = $this->pdo->prepare( $sql );
        $statement->execute( $params );
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) return [];
        return $result;
    }

}

$pdo = new DataBase();

