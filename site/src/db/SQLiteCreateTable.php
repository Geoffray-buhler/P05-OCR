<?php

namespace Bdd;

class SQLiteCreateTable {

        /**
         * PDO object
         * @var \PDO
         */
        private $pdo;

        /**
         * connect to the SQLite database
         */
        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

    /**
    * create tables 
    */
    public function createTables() {

        $commands = [
            'CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY,
                type VARCHAR (50) NOT NULL,
                login VARCHAR (50) NOT NULL,
                password VARCHAR (50) NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS articles (
                id INTEGER PRIMARY KEY,
                title  VARCHAR (50) NOT NULL,
                body  TEXT NOT NULL,
                users_id VARCHAR (255),
                FOREIGN KEY (id)
                REFERENCES users(id) ON UPDATE CASCADE
                ON DELETE CASCADE,
            )',
            'CREATE TABLE IF NOT EXISTS comment (
                id INTEGER PRIMARY KEY,
                title  VARCHAR (50) NOT NULL,
                body  TEXT NOT NULL,
                users_id VARCHAR (255),
                FOREIGN KEY (id)
                REFERENCES users(id) ON UPDATE CASCADE
                ON DELETE CASCADE,
                articles_id VARCHAR (255),
                FOREIGN KEY (id)
                REFERENCES articles(id) ON UPDATE CASCADE
                ON DELETE CASCADE,
            )'
        ];

        // execute the sql commands to create new tables
        for ($i=0; $i < count($commands); $i++) {
            $this->pdo->exec($commands[$i]);
        }
    }
}