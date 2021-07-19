<?php

namespace Bdd;

class SQLiteGet {
    /**
     * get the table list in the database
     */
    public function getAllArticles() {

        $stmt = $this->pdo->query("SELECT *
                                   FROM articles");
        $articles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $articles[] = $row;
        }
        return $articles;
    }
}