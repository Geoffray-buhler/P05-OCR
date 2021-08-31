<?php

namespace Bdd;

use App\Debug;

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
                email VARCHAR (50) NOT NULL,
                password VARCHAR (50) NOT NULL,
                CONSTRAINT users UNIQUE (id,login)
            )','CREATE TABLE IF NOT EXISTS articles (
                id INTEGER PRIMARY KEY,
                title  VARCHAR (50) NOT NULL,
                body  TEXT NOT NULL,
                fileName VARCHAR (50),
                users_id INTEGER,
                FOREIGN KEY (users_id)
                REFERENCES users(id) ON UPDATE CASCADE
                ON DELETE CASCADE
            )','CREATE TABLE IF NOT EXISTS comments (
                id INTEGER PRIMARY KEY,
                title VarChar (50) NOT NULL,
                body TEXT NOT NULL,
                users_id INTEGER,
                articles_id INTEGER,
                FOREIGN KEY (users_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
                FOREIGN KEY (articles_id) REFERENCES articles(id) ON UPDATE CASCADE ON DELETE CASCADE
            )'
        ];

        // execute the sql commands to create new tables
        foreach ($commands as $key => $command) {
            $this->pdo->exec($command);
        }
    }
}