<?php

namespace App\Classes;

class Tag
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getAllTags()
    {
        $sql = 'SELECT name FROM tags';
        try {
            $tags = [];
            $results = $this->db->query($sql)->fetchAll();
            foreach ($results as $result) {
                $tags[] = $result['name'];
            }
            return $tags;
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
            return [];
        }
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

    public function getTagsForPost($id)
    {
        $sql = 'SELECT tags.name FROM tags 
                    LEFT OUTER JOIN posts_to_tags ON tags.id = posts_to_tags.tag_id
                    LEFT OUTER JOIN posts ON posts_to_tags.post_id = posts.id
                    WHERE posts_to_tags.post_id = ?';
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
