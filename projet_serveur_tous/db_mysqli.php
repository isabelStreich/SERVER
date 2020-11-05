<?php

// No direct call to this file
if (!defined('INDEX_LOADED')) {
    http_response_code(403); // forbidden
    die('Direct access to this php file is forbidden');
}

/**
 * Classe pour base de données sereur de type MySQL uniquement
 * https://www.php.net/manual/en/book.mysqli.php.
 */
class DB
{
    // ces valeurs peuvent être dans un fichier de configuration séparée
    // peuvent être constante ou variable si menu pour changer de DB

    private $host = 'localhost'; // meme chose que 127.0.0.1
    //private $host = '127.0.0.1';

    // the database name on SQL server
    private $db_name = 'classicmodels';

    // créer utilisateurs sur serveur avant
    // vice de sécurité car valeur directement dans un fichier sans encryption

    //one of the user name set on localhost SQL server
    private $username = 'un_test';
    //the user password set on SQL server
    private $password = 'qqwwee';

    private $connection; // msqli connection object

    /**
     * constructor automatically connects to database
     * and set $connection object.
     */
    public function __construct()
    {
        // connect to database
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        /* check for connection error */
        if (mysqli_connect_errno()) {
            http_response_code(400); // bad request
            die('Database Connection failed:'.mysqli_connect_error());
        }
    }

    /**
     * query($sql_str) executes any query on the SQL server.
     * Use this for queries not returning records like INSERT, DELETE, UPDATE, etc...
     *
     * 1)returns full SQL server response
     * whatever the query (select, delete, update, etc...)
     * 2)for INSERT, DELETE, UPDATE returns simply 'true'
     * 3)for SELECT no records conversion done here, use querySelect function instead
     */
    public function query($sql_str)
    {
        $result = $this->connection->query($sql_str);

        if (!$result) {
            // -pas de résultat si la requête est erronnée, afficher l'erreur
            // -si requête est bonne mais retourne aucun enregistrements
            // ce code ne sera pas exécuté car $result est quand même true
            http_response_code(400); // bad request
            // affiche message d'erreur de SQL voir connection->error
            die('Database SQL Error: '.$this->connection->error);
        }
        //var_dump($result);
        return $result;
    }

    /**
     * querySelect($sql_str) for SELECT queries returning records.
     */
    public function querySelect($sql_str)
    {
        $result = $this->query($sql_str); // voir fonction ci-dessous

        // initialize empty array / liste vide
        $records = [];

        // fetch_array() convertis chaque enregistrements de la table en un array key=>value
        while ($one_record = $result->fetch_array()) {
            // ajoute à la fin de la liste des objets/enregistrments
            array_push($records, $one_record);
        }

        return $records;
    }

    /**
     * table($table_name) returns all rows and all columns of a table.
     */
    public function table($table_name)
    {
        return $this->querySelect('SELECT * FROM '.$table_name);
    }
}
