<?php

namespace App\Classes;

use Exception;

class Comment
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getCommentsForPost($id)
    {
        $sql = 'SELECT * FROM comments WHERE post_id = ? ORDER BY date DESC';
        try {
            $results = $this->db->prepare($sql);
            $results->bindValue(1, $id, \PDO::PARAM_INT);
            $results->execute();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . '😕 <br>';
            return false;
        }
        return $results->fetchAll();
    }
}
