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

    public function addComment($name, $body, $post_id)
    {
        date_default_timezone_set('America/New_York');
        $timestamp = date('Y-m-d g:i a');
        $sql = 'INSERT INTO comments (name, date, body, post_id) VALUES (?, ?, ?, ?)';
        try {
            $results = $this->db->prepare($sql);
            $results->bindValue(1, $name, \PDO::PARAM_STR);
            $results->bindValue(2, $timestamp, \PDO::PARAM_STR);
            $results->bindValue(3, $body, \PDO::PARAM_STR);
            $results->bindValue(4, $post_id, \PDO::PARAM_INT);
            $results->execute();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . '😕 <br>';
            return false;
        }
        return true;
    }

    public function deleteCommentPost($id)
    {
        $sql = 'DELETE FROM comments WHERE post_id = ?';
        try {
            $results = $this->db->prepare($sql);
            $results->bindValue(1, $id, \PDO::PARAM_INT);
            $results->execute();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . '😕 <br>';
            return false;
        }
        return true;
    }
}
