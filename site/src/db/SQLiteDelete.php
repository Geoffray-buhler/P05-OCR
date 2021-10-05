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

    public function DeleteUser($idUser){
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":id"=> $idUser
        ]);
        return 'Compte bien supprimé';
    }

    public function DeleteComment($idComment){
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":id"=> $idComment
        ]);
        return 'le commentaire a bien supprimé';
    }
}