<?php

namespace App\Models;

class Comment
{
    // database connection passed on construction
    private $db;
    // array of comment objects
    public $comments = [];

    /**
     * Comment constructor
     *
     * @param PDO $db connection to the database
     */
    public function __construct($db)
    {
        // set $db property
        $this->db = $db;
    }
}
