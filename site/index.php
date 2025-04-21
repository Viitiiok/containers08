<?php

require_once __DIR__ . '/modules/database.php';
require_once __DIR__ . '/modules/page.php';
require_once __DIR__ . '/config.php';

$db = new Database($config["db"]["path"]);

$page = new Page(__DIR__ . '/templates/index.tpl');

// Get page ID from query parameter, default to 1 if not provided
$pageId = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Validate page ID
if ($pageId <= 0) {
    $pageId = 1;
}

// Get page data
$data = $db->Read("page", $pageId);

// If page not found, show a default page
if (!$data) {
    $data = [
        'title' => 'Page Not Found',
        'content' => 'The requested page could not be found.'
    ];
}

echo $page->Render($data);