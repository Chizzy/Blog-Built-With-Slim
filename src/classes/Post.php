<?php

namespace App\Classes;

class Post
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getAllPosts()
    {
        $sql = 'SELECT * FROM posts ORDER BY date DESC';
        try {
            $results = $this->db->query($sql);
            return $results->fetchAll();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
            return [];
        }
    }

    public function addPost($title, $body)
    {
        date_default_timezone_set('America/New_York');
        $timestamp = date('Y-m-d g:i a');
        $sql = 'INSERT INTO posts (title, date, body) VALUES (?, ?, ?)';
        try {
            $results = $this->db->prepare($sql);
            $results->bindValue(1, strtolower($title), \PDO::PARAM_STR );
            $results->bindValue(2, $timestamp, \PDO::PARAM_STR);
            $results->bindValue(3, $body, \PDO::PARAM_STR);
            $results->execute();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
            return false;
        }
        return true;
    }

    public function getSinglePost($id)
    {
        $sql = 'SELECT * FROM posts WHERE id = ?';
        try {
            $results = $this->db->prepare($sql);
            $results->bindValue(1, $id, \PDO::PARAM_INT);
            $results->execute();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . '😕 <br>';
            return false;
        }
        return $results->fetch();
    }

    public function editPost($id, $title, $body)
    {
        date_default_timezone_set('America/New_York');
        $timestamp = date('Y-m-d g:i a');
        $sql = 'UPDATE posts SET title = ?, date = ?, body = ? WHERE id = ?';
        try {
            $results = $this->db->prepare($sql);
            $results->bindValue(1, strtolower($title), \PDO::PARAM_STR);
            $results->bindValue(2, $timestamp, \PDO::PARAM_STR);
            $results->bindValue(3, $body, \PDO::PARAM_STR);
            $results->bindValue(4, $id, \PDO::PARAM_INT);
            $results->execute();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . '😕 <br>';
            return false;
        }
        return true;
    }

    public function deletePost($id)
    {
        $sql = 'DELETE FROM posts WHERE id = ?';
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
