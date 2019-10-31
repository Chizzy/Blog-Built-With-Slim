<?php

use App\Classes\Post;
use App\Classes\Comment;

$app->map(['GET', 'POST'], '/posts/new', function ($request, $response, $args) {
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

    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $args['csrf'] = [
        $nameKey => $request->getAttribute($nameKey),
        $valueKey => $request->getAttribute($valueKey)
    ];

    $args['save'] = $_POST;
    $this->logger->info("New Entry '/new' route");
    return $this->view->render($response, 'new.twig', $args);
});


$app->map(['GET', 'PUT'], '/posts/{id}/edit', function ($request, $response, $args) {
    if ($request->getMethod() == 'PUT') {
        $args = array_merge($args, $request->getParsedBody());
        if (!empty($args['title']) && !empty($args['body'])) {
            $post = new Post($this->db);
            $this->logger->notice(json_encode([$args['title'], $args['body']]));
            $post->editPost($args['id'], $args['title'], $args['body']);
            $url = $this->router->pathFor(
                'singlePost', 
                ['id' => $args['id'], 'post_title' => strtolower(str_replace(' ', '-', $args['title']))]
            );
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        $args['error'] = 'All fields required.';
    }

    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $args['csrf'] = [
        $nameKey => $request->getAttribute($nameKey),
        $valueKey => $request->getAttribute($valueKey)
    ];

    $this->logger->info("Edit Post '/edit' route");
    $post = new Post($this->db);
    $singlePost = $post->getSinglePost($args['id']);
    $args['post'] = $singlePost;
    return $this->view->render($response, 'edit.twig', $args);
});


$app->map(['GET', 'POST', 'DELETE'], '/posts/{id}/{post_title}', function ($request, $response, $args) {
    $post = new Post($this->db);
    $comment = new Comment($this->db);

    if ($request->getMethod() == 'POST') {
        $args = array_merge($args, $request->getParsedBody());
        if (!empty($args['name']) && !empty($args['body'])) {
            $this->logger->notice(json_encode([$args['name'], $args['body']]));
            $comment->addComment($args['name'], $args['body'], $args['id']);
            $url = $this->router->pathFor( 
                'singlePost', 
                ['id' => $args['id'], 'post_title' => $args['post_title']]
            );
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        $args['error'] = 'All fields required.';
    }
    
    if ($request->getMethod() == 'DELETE') {
        $this->logger->info("Delete Post route");
        $post->deletePost($args['id']);
        $url = $this->router->pathFor('home');
        return $response->withStatus(302)->withHeader('Location', $url);
    }

    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $args['csrf'] = [
        $nameKey => $request->getAttribute($nameKey),
        $valueKey => $request->getAttribute($valueKey)
    ];

    $this->logger->info("Details of Post '/details' route");
    $singlePost = $post->getSinglePost($args['id']);
    $args['post'] = $singlePost;
    $comments = $comment->getCommentsForPost($singlePost['id']);
    $args['comments'] = $comments;
    if (empty($singlePost)) {
        $url = $this->router->pathFor('home');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
        $args['save'] = $_POST;
        var_dump($args);
        return $this->view->render($response, 'details.twig', $args);
})->setName('singlePost');


$app->get('/', function ($request, $response, $args) {
    $this->logger->info("Home'/' route");
    $post = new Post($this->db);
    $allPosts = $post->getAllPosts();
    $args['posts'] = $allPosts;
    return $this->view->render($response, 'home.twig', $args);
})->setName('home');
