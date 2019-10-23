<?php

try {
    $db = new PDO('sqlite:'.__DIR__.'/blog.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo 'Unable to connect 😕 <br>';
    echo $e->getMessage();
    exit;
}