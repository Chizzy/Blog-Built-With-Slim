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
            return $this->db->query($sql);
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
            return [];
        }
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
