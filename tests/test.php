<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$testFramework = new TestFramework();

// test 1: check database connection
function testDbConnection() {
    global $config;
    $db = new Database($config['database']['path']);
    return assertExpression($db instanceof Database, 'Database connection success', 'Database connection failed');
}

// test 2: test count method
function testDbCount() {
    global $config;
    $db = new Database($config['database']['path']);
    $count = $db->Count('page');
    return assertExpression($count === 0, 'Table count success', 'Table count failed');
}

// test 3: test create method
function testDbCreate() {
    global $config;
    $db = new Database($config['database']['path']);
    $id = $db->Create('page', ['title' => 'test', 'content' => 'test']);
    $count = $db->Count('page');
    return assertExpression($count === 1, 'Data create success', 'Data create failed');
}

// test 4: test read method
function testDbRead() {
    global $config;
    $db = new Database($config['database']['path']);
    $id = $db->Create('page', ['title' => 'test', 'content' => 'test']);
    $data = $db->Read('page', $id);
    return assertExpression($data['title'] === 'test', 'Data read success', 'Data read failed');
}

// Добавьте в файл ./tests/test.php тесты для всех методов класса Database, а также для методов класса Page.

function testDbUpdate() {
    global $config;
    $db = new Database($config['database']['path']);
    $id = $db->Create('page', ['title' => 'test', 'content' => 'test']);
    $db->Update('page', $id, ['title' => 'test2', 'content' => 'test2']);
    $data = $db->Read('page', $id);
    return assertExpression($data['title'] === 'test2', 'Data update success', 'Data update failed');
}

function testDbDelete() {
    global $config;
    $db = new Database($config['database']['path']);
    $id = $db->Create('page', ['title' => 'test', 'content' => 'test']);
    $db->Delete('page', $id);
    $count = $db->Count('page');
    return assertExpression($count === 0, 'Data delete success', 'Data delete failed');
}

function testPageRender() {
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    $html = $page->Render(['title' => 'test', 'content' => 'test']);
    return assertExpression($html === '<h1>test</h1><p>test</p>', 'Page render success', 'Page render failed');
}

$tests = new TestFramework();

// add tests
$tests->add('Database connection', 'testDbConnection');
$tests->add('table count', 'testDbCount');
$tests->add('data create', 'testDbCreate');
$tests->add('data read', 'testDbRead');
$tests->add('data update', 'testDbUpdate');
$tests->add('data delete', 'testDbDelete');
$tests->add('page render', 'testPageRender');

// run tests
$tests->run();

echo $tests->getResult();
