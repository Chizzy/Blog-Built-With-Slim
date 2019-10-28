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
        $sql = 'SELECT title, date FROM posts ORDER BY id DESC';
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

    public function getSinglePost($title)
    {
        $sql = 'SELECT * FROM posts WHERE title = ?';
        try {
            $results = $this->db->prepare($sql);
            $results->bindValue(1, str_replace('-', ' ', $title), \PDO::PARAM_STR);
            $results->execute();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . '😕 <br>';
            return false;
        }
        return $results->fetch();
    }
}
