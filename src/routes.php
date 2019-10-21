<?php
// Routes

$app->get('/detail', function ($request, $response, $args) {
    // Log message
    $this->logger->info("Details of Entry '/detail' route");
    // Render detail view
    return $this->view->render($response, 'detail.twig', $args);
});

$app->get('/edit', function ($request, $response, $args) {
    // Log message
    $this->logger->info("Edit Entry '/edit' route");
    // Render edit view
    return $this->view->render($response, 'edit.twig', $args);
});

$app->get('/new', function ($request, $response, $args) {
    // Log message
    $this->logger->info("New Entry '/new' route");
    // Render new view
    return $this->view->render($response, 'new.twig', $args);
});

$app->get('/', function ($request, $response, $args) {
    // Log message
    $this->logger->info("Home'/' route");
    // Render home view
    return $this->view->render($response, 'home.twig', $args);
});
