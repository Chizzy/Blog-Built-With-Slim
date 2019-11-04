<?php

namespace App\Classes;

class Tag
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getTagWithPosts($name)
    {
        $sql = 'SELECT posts.id, posts.title, posts.date, tags.name
                    FROM posts 
                    LEFT OUTER JOIN posts_to_tags ON posts.id = posts_to_tags.post_id
                    LEFT OUTER JOIN tags ON posts_to_tags.tag_id = tags.id
                    WHERE tags.name = ?
                    ORDER BY date DESC';
        try {
            $results = $this->db->prepare($sql);
            $results->bindValue(1, $name, \PDO::PARAM_STR);
            $results->execute();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . '😕 <br>';
            return false;
        }
        return $results->fetchAll();
    }
}
