<?php
// Routes

use App\Classes\Post;
use App\Classes\Comment;

$app->get('/new', function ($request, $response, $args) {
    // Log message
    $this->logger->info("New Entry '/new' route");
    // Render new view
    return $this->view->render($response, 'new.twig', $args);
});

$app->get('/edit', function ($request, $response, $args) {
    // Log message
    $this->logger->info("Edit Entry '/edit' route");
    // Render edit view
    return $this->view->render($response, 'edit.twig', $args);
});

$app->get('/{post_title}', function ($request, $response, $args) {
    // Log message
    $this->logger->info("Details of Entry '/details' route");
    $post = new Post($this->db);
    $singlePost = $post->getSinglePost($args['post_title']);
    // Render detail view
    return $this->view->render($response, 'details.twig', ['post' => $singlePost]);
});

$app->get('/', function ($request, $response, $args) {
    // Log message
    $this->logger->info("Home'/' route");
    $post = new Post($this->db);
    $allPosts = $post->getAllPosts();
    // Render home view
    return $this->view->render($response, 'home.twig', ['posts' => $allPosts]);
});
