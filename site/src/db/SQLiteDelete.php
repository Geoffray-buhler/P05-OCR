<?php

namespace Bdd;

use App\Debug;

class SQLiteDelete {
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

    public function DeleteUser($id){
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":id"=> $id
        ]);
        return 'Compte bien supprimé';
    }
}