<?php

namespace App\Classes;
use App\Classes\Tag;

class Post
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getAllPosts()
    {
        $sql = "SELECT posts.id, posts.title, posts.date, GROUP_CONCAT(tags.name, ' ') AS name FROM posts 
                    LEFT OUTER JOIN posts_to_tags ON posts.id = posts_to_tags.post_id
                    LEFT OUTER JOIN tags ON posts_to_tags.tag_id = tags.id 
                    GROUP BY posts.id ORDER BY date DESC";
        try {
            $results = $this->db->query($sql);
            return $results->fetchAll();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
            return [];
        }
    }

    public function addPost($title, $body, $tags = null)
    {
        date_default_timezone_set('America/New_York');
        $timestamp = date('Y-m-d g:i a');
        try {
            $this->db->beginTransaction();

            $sql = 'INSERT INTO posts (title, date, body) VALUES (?, ?, ?)';
            $results = $this->db->prepare($sql);
            $results->bindValue(1, strtolower($title), \PDO::PARAM_STR );
            $results->bindValue(2, $timestamp, \PDO::PARAM_STR);
            $results->bindValue(3, $body, \PDO::PARAM_STR);
            $results->execute();
            $post_id = $this->db->lastInsertId();
            
            $allTags = new Tag($this->db);
            $allTags = $allTags->getAllTags();
            foreach ($tags as $tag) {
                if (isset($tag) && (in_array($tag, $allTags)) == false) {
                    $sql = 'INSERT INTO tags (name) VALUES (?)';
                    $results = $this->db->prepare($sql);
                    $results->bindValue(1, $tag, \PDO::PARAM_STR);
                    $results->execute();
                    $tag_id = $this->db->lastInsertId();

                    $sql = "INSERT INTO posts_to_tags (post_id, tag_id) VALUES ($post_id, $tag_id)";
                    $this->db->query($sql);
                } else if (isset($tag)) {
                    $sql = 'SELECT id FROM tags WHERE name = ?';
                    $results = $this->db->prepare($sql);
                    $results->bindValue(1, $tag, \PDO::PARAM_STR);
                    $results->execute();

                    $tag_id = $results->fetch();
                    $tag_id = $tag_id['id'];

                    $sql = "INSERT INTO posts_to_tags (post_id, tag_id) VALUES ($post_id, $tag_id)";
                    $this->db->query($sql);
                }
            }
            $this->db->commit();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
            $this->db->rollBack();
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

    public function editPost($id, $title, $body, $tags = null)
    {
        date_default_timezone_set('America/New_York');
        $timestamp = date('Y-m-d g:i a');
        try {
            $this->db->beginTransaction();

            $sql = 'DELETE FROM posts_to_tags WHERE post_id = ?';
            $results = $this->db->prepare($sql);
            $results->bindValue(1, $id, \PDO::PARAM_INT);
            $results->execute();

            $sql = 'UPDATE posts SET title = ?, date = ?, body = ? WHERE id = ?';
            $results = $this->db->prepare($sql);
            $results->bindValue(1, strtolower($title), \PDO::PARAM_STR);
            $results->bindValue(2, $timestamp, \PDO::PARAM_STR);
            $results->bindValue(3, $body, \PDO::PARAM_STR);
            $results->bindValue(4, $id, \PDO::PARAM_INT);
            $results->execute();

            $allTags = new Tag($this->db);
            $allTags = $allTags->getAllTags();
            foreach ($tags as $tag) {
                if (isset($tag) && (in_array($tag, $allTags)) == false) {
                    $sql = 'INSERT INTO tags (name) VALUES (?)';
                    $results = $this->db->prepare($sql);
                    $results->bindValue(1, $tag, \PDO::PARAM_STR);
                    $results->execute();
                    $tag_id = $this->db->lastInsertId();

                    $sql = "INSERT INTO posts_to_tags (post_id, tag_id) VALUES ($id, $tag_id)";
                    $this->db->query($sql);
                } elseif (isset($tag)) {
                    $sql = 'SELECT id FROM tags WHERE name = ?';
                    $results = $this->db->prepare($sql);
                    $results->bindValue(1, $tag, \PDO::PARAM_STR);
                    $results->execute();

                    $tag_id = $results->fetch();
                    $tag_id = $tag_id['id'];

                    $sql = "INSERT INTO posts_to_tags (post_id, tag_id) VALUES ($id, $tag_id)";
                    $this->db->query($sql);
                }
            }
            $this->db->commit();
        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . '😕 <br>';
            $this->db->rollBack();
            return false;
        }
        return true;
    }

    public function deletePost($id)
    {
        try {
            $this->db->beginTransaction();

            $sql = 'DELETE FROM posts WHERE id = ?';
            $results = $this->db->prepare($sql);
            $results->bindValue(1, $id, \PDO::PARAM_INT);
            $results->execute();

            $sql = 'DELETE FROM posts_to_tags WHERE post_id = ?';
            $results = $this->db->prepare($sql);
            $results->bindValue(1, $id, \PDO::PARAM_INT);
            $results->execute();

            $this->db->commit();

        } catch (Exception $e) {
            echo 'ERROR!: ' . $e->getMessage() . '😕 <br>';
            $this->db->rollBack();
            return false;
        }
        return true;
    }
}
