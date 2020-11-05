<?php

// No direct call to this file
if (!defined('INDEX_LOADED')) {
    http_response_code(403); // forbidden
    die('Direct access to this php file is forbidden');
}

/*
 * classe pour bases de donnée de tous types avec PDO.
 */
//$production = 1;
if (!isset($production)) {
    // database local sur mon laptop
    define('HOST', 'localhost');
    define('DB_NAME', 'classicmodels');
    define('USER_NAME', 'un_test');
    define('USER_PW', 'qqwwee');
} else {
    // database sur infinity free
    define('HOST', 'sql101.epizy.com');
    define('DB_NAME', 'epiz_26539840_classicmodels');
    define('USER_NAME', 'epiz_26539840');
    define('USER_PW', 'CXxI7rpOx1O');
}

class DB
{
    private $host = HOST; // meme chose que 127.0.0.1
    //private $host = '127.0.0.1';

    // the database name on SQL server
    private $db_name = DB_NAME;

    // créer utilisateurs sur serveur avant
    // vice de sécurité car valeur directement dans un fichier sans encryption
    //one of the user name set on localhost SQL server
    private $username = USER_NAME;
    //the user password set on SQL server
    private $password = USER_PW;

    private $pdo; // PDO connection object

    public function __construct()
    {
        $port = 3306;
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$this->host;dbname=$this->db_name;charset=$charset;port=$port";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
        //echo 'connected';
    }

    public function query($sql_str)
    {
        try {
            $result = $this->pdo->query($sql_str);
            //$result = $this->pdo->query('SELECT * FROM toto');
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
        //var_dump($result);

        return $result;
    }

    // L'utilisation des paramêtres nommées (named parameters)
    // offre une protection contre l'injection SQL
    // Le même technique aussi existe en Java: https://docs.oracle.com/cd/E19798-01/821-1841/bnbrh/index.html
    // exemple
    //$stmt = $pdo->prepare('SELECT * FROM profs WHERE AnneeAffectation = :AnneeAffectation');
    //$stmt->execute(['AnneeAffectation' => 2001]);
    public function queryParam($sql_str, $params)
    {
        $stmt = $this->pdo->prepare($sql_str);
        $stmt->execute($params);

        return true;
    }

    /**
     * for SELECT returning records.
     */
    public function querySelect($sql_str)
    {
        $records = $this->query($sql_str)->fetchAll();

        return $records;
    }

    /**
     * requête avec paramètres pour se protéger contre injection SQL.
     */
    public function querySelectParam($sql_str, $params)
    {
        $stmt = $this->pdo->prepare($sql_str);
        $stmt->execute($params);
        $records = $stmt->fetchAll();

        return $records;
    }

    /**
     * table($table_name) returns all rows and all columns of a table.
     */
    public function table($table_name)
    {
        $records = $this->querySelect('SELECT * FROM '.$table_name);

        return $records;
    }

    public function disconnect()
    {
        $this->pdo = null;
    }
}
