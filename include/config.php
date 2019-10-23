<?php

use App\Models\Post;

session_start();

// include the classes and pass in the database connection
include __DIR__ . '/../Classes/Comment.php';
include __DIR__ . '/../Classes/Post.php';

try {
    // create PDO connection
    $db = new PDO('sqlite:'.__DIR__.'/blog.db');
    // set error mode
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    // show error
    echo 'Unable to connect 😕 <br>';
    echo $e->getMessage();
    exit;
}

// load post object
$posts = new Post($db);