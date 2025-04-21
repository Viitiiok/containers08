<?php

require_once __DIR__ . '/testframework.php';

// Update the paths to correctly point to files in the site directory
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$tests = new TestFramework();

// Test 1: Check database connection
function testDbConnection() {
    global $config;
    
    try {
        $db = new Database($config["db"]["path"]);
        return assertExpression(true, "Database connection successful", "Database connection failed");
    } catch (Exception $e) {
        error("Exception: " . $e->getMessage());
        return false;
    }
}

// Test 2: Test count method
function testDbCount() {
    global $config;
    
    try {
        $db = new Database($config["db"]["path"]);
        $count = $db->Count("page");
        return assertExpression($count >= 3, "Page count is $count (expected at least 3)", "Page count is incorrect");
    } catch (Exception $e) {
        error("Exception: " . $e->getMessage());
        return false;
    }
}

// Test 3: Test create method
function testDbCreate() {
    global $config;
    
    try {
        $db = new Database($config["db"]["path"]);
        $data = [
            'title' => 'Test Page',
            'content' => 'Test Content'
        ];
        
        $id = $db->Create("page", $data);
        return assertExpression($id > 0, "Page created with ID: $id", "Failed to create page");
    } catch (Exception $e) {
        error("Exception: " . $e->getMessage());
        return false;
    }
}

// Test 4: Test read method
function testDbRead() {
    global $config;
    
    try {
        $db = new Database($config["db"]["path"]);
        $data = $db->Read("page", 1);
        return assertExpression(
            isset($data['title']) && $data['title'] == 'Page 1',
            "Read page title: {$data['title']}",
            "Failed to read page or title is incorrect"
        );
    } catch (Exception $e) {
        error("Exception: " . $e->getMessage());
        return false;
    }
}

// Test 5: Test update method
function testDbUpdate() {
    global $config;
    
    try {
        $db = new Database($config["db"]["path"]);
        $newData = [
            'title' => 'Updated Page 2',
            'content' => 'Updated Content 2'
        ];
        
        $result = $db->Update("page", 2, $newData);
        if (!$result) {
            return assertExpression(false, "", "Failed to update page");
        }
        
        $updated = $db->Read("page", 2);
        return assertExpression(
            $updated['title'] == 'Updated Page 2',
            "Page updated successfully",
            "Page update verification failed"
        );
    } catch (Exception $e) {
        error("Exception: " . $e->getMessage());
        return false;
    }
}

// Test 6: Test delete method
function testDbDelete() {
    global $config;
    
    try {
        $db = new Database($config["db"]["path"]);
        
        // First create a page to delete
        $data = [
            'title' => 'Page to Delete',
            'content' => 'This page will be deleted'
        ];
        
        $id = $db->Create("page", $data);
        
        // Now delete it
        $result = $db->Delete("page", $id);
        
        // Try to read it - should return false or empty
        $deleted = $db->Read("page", $id);
        
        return assertExpression(
            $result && !$deleted,
            "Page deleted successfully",
            "Failed to delete page"
        );
    } catch (Exception $e) {
        error("Exception: " . $e->getMessage());
        return false;
    }
}

// Test 7: Test fetch method
function testDbFetch() {
    global $config;
    
    try {
        $db = new Database($config["db"]["path"]);
        $result = $db->Fetch("SELECT * FROM page LIMIT 3");
        
        return assertExpression(
            is_array($result) && count($result) > 0,
            "Fetch returned " . count($result) . " rows",
            "Fetch failed to return rows"
        );
    } catch (Exception $e) {
        error("Exception: " . $e->getMessage());
        return false;
    }
}

// Test 8: Test Page class
function testPageRender() {
    try {
        $page = new Page(__DIR__ . '/../templates/index.tpl');
        $data = [
            'title' => 'Test Title',
            'content' => 'Test Content'
        ];
        
        $rendered = $page->Render($data);
        
        return assertExpression(
            strpos($rendered, 'Test Title') !== false && strpos($rendered, 'Test Content') !== false,
            "Page rendered correctly",
            "Page rendering failed"
        );
    } catch (Exception $e) {
        error("Exception: " . $e->getMessage());
        return false;
    }
}

// Add tests
$tests->add('Database connection', 'testDbConnection');
$tests->add('Table count', 'testDbCount');
$tests->add('Data create', 'testDbCreate');
$tests->add('Data read', 'testDbRead');
$tests->add('Data update', 'testDbUpdate');
$tests->add('Data delete', 'testDbDelete');
$tests->add('Data fetch', 'testDbFetch');
$tests->add('Page render', 'testPageRender');

// Run tests
$tests->run();

echo $tests->getResult();