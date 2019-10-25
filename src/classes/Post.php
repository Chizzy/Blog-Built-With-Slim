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
        $sql = 'SELECT title, date FROM posts';
        try {
            $posts = $this->db->query($sql);
            return $posts;
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
            return [];
        }
    }
}
