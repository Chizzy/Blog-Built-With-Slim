<?php

use App\Classes\Post;
use App\Classes\Comment;

$app->map(['GET', 'POST'], '/new', function ($request, $response, $args) {
    if ($request->getMethod() == 'POST') {
        $args = array_merge($args, $request->getParsedBody());
        if (!empty($args['title']) && !empty($args['body'])) {
            $post = new Post($this->db);
            $this->logger->notice(json_encode([$args['title'], $args['body']]));
            $post->addPost($args['title'], $args['body']);
            $url = $this->router->pathFor('home');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        $args['error'] = 'All fields required.';
    }
    // CSRF token name and value
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $args['csrf'] = [
        $nameKey => $request->getAttribute($nameKey),
        $valueKey => $request->getAttribute($valueKey)
    ];
    $args['addPost'] = './new';
    $args['post'] = $_POST;
    $this->logger->info("New Entry '/new' route");
    return $this->view->render($response, 'new.twig', $args);
})->setName('addPost');

$app->get('/edit', function ($request, $response, $args) {
    $this->logger->info("Edit Entry '/edit' route");
    return $this->view->render($response, 'edit.twig', $args);
});

$app->get('/{post_title}', function ($request, $response, $args) {
    $this->logger->info("Details of Entry '/details' route");
    $post = new Post($this->db);
    $singlePost = $post->getSinglePost($args['post_title']);
    $args['post'] = $singlePost;
    $comment = new Comment($this->db);
    $comments = $comment->getCommentsForPost($singlePost['id']);
    $args['comments'] = $comments;
    if (empty($singlePost)) {
        $url = $this->router->pathFor('home');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
        return $this->view->render($response, 'details.twig', $args);
});

$app->get('/', function ($request, $response, $args) {
    $this->logger->info("Home'/' route");
    $post = new Post($this->db);
    $allPosts = $post->getAllPosts();
    $args['posts'] = $allPosts;
    return $this->view->render($response, 'home.twig', $args);
})->setName('home');
