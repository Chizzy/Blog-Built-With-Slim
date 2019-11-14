<?php

use App\Classes\Post;
use App\Classes\Comment;
use App\Classes\Tag;

$app->map(['GET', 'POST'], '/posts/new', function ($request, $response, $args) {
    if ($request->getMethod() == 'POST') {
        $args = array_merge($args, $request->getParsedBody());
        if (!empty($args['title']) && !empty($args['body'])) {
            $post = new Post($this->db);
            $this->logger->notice(json_encode([$args['title'], $args['body'], $args['tags']]));
            $post->addPost($args['title'], $args['body'], $args['tags']);
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

    $tag = new Tag($this->db);
    $args['tags'] = $tag->getAllTags();
    $args['save'] = $_POST;
    $this->logger->info("New Entry '/new' route");
    return $this->view->render($response, 'new.twig', $args);
});


$app->map(['GET', 'PUT'], '/posts/{id}/edit', function ($request, $response, $args) {
    if ($request->getMethod() == 'PUT') {
        $args = array_merge($args, $request->getParsedBody());
        if (!empty($args['title']) && !empty($args['body'])) {
            $post = new Post($this->db);
            $this->logger->notice(json_encode([$args['title'], $args['body'], $args['tags']]));
            $post->editPost($args['id'], $args['title'], $args['body'], $args['tags']);
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
    $tag = new Tag($this->db);
    $args['tags'] = $tag->getAllTags();
    $selectedTags = $tag->getTagsForPost($args['id']);
    foreach ($selectedTags as $select) {
        $args['selected'][] = $select['name'];
    }
    return $this->view->render($response, 'edit.twig', $args);
});


$app->map(['GET', 'POST', 'DELETE'], '/posts/{id}/{post_title}', function ($request, $response, $args) {
    $post = new Post($this->db);
    $comment = new Comment($this->db);
    $tags = new Tag($this->db);

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
        $comment->deleteCommentPost($args['id']);
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
    $args['tags'] = $tags->getTagsForPost($singlePost['id']);
    if (empty($singlePost)) {
        $url = $this->router->pathFor('home');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
        $args['save'] = $_POST;
        return $this->view->render($response, 'details.twig', $args);
})->setName('singlePost');


$app->get('/tags/{name}', function ($request, $response, $args) {
    $this->logger->info("{name} tag route");
    $tag = new Tag($this->db);
    $tagWithPosts = $tag->getTagWithPosts($args['name']);
    $args['posts'] = $tagWithPosts;
    return $this->view->render($response, 'tags.twig', $args);
});

$app->get('/', function ($request, $response, $args) {
    $this->logger->info("Home'/' route");
    $post = new Post($this->db);
    $allPosts = $post->getAllPosts();
    $args['posts'] = $allPosts;
    return $this->view->render($response, 'home.twig', $args);
})->setName('home');
