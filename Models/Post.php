<?php

namespace App\Models;

class Post
{
    // database connection passed on construction
    private $db;
    // array of post objects
    public $posts = [];

    /**
     * Post constructor
     *
     * @param PDO  $db connection to the database
     */
    public function __construct($db)
    {
        // set $db property
        $this->db = $db;
    }
}
