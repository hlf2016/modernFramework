<?php

require __DIR__ . "/../vendor/autoload.php";

require_once __DIR__ . '/../app.php';

// 绑定request
App::getContainer()->bind(\core\request\RequestInterface::class, function () {
    return \core\request\PhpRequest::create(
        $_SERVER['REQUEST_URI'],
        $_SERVER['REQUEST_METHOD'],
        $_SERVER
    );
});


App::getContainer()->get('response')->setContent(
    App::getContainer()->get('router')->dispatch(
        App::getContainer()->get(\core\request\RequestInterface::class)
    )
)->send();

die;

// 测试 response
/*
$response = App::getContainer()->get('response')->setHeaders(['name' => '123'])->setContent(
    App::getContainer()->get(\core\request\RequestInterface::class)->getMethod()
)->setCode(404)->send();
echo $response->getStatusCode() . PHP_EOL;
*/

App::getContainer()->bind('str', function () {
    return 'hello str';
});

echo App::getContainer()->get("str") . PHP_EOL;

// 自行实现的简单的自动加载
/*
spl_autoload_register(function ($class) {
    echo $class; // $class 是App\User
    die;
});
*/

// 自行实现的野路子自动加载
/*
spl_autoload_register(function ($class) {
    // $class 是App\User
    $psr4 = [
        "App" => "app"
    ];
    $suffix = ".php";
    foreach ($psr4 as $name => $value) {
        // 如果是psr4替换 注意此处替换 \\ 为mac中用的分隔符 /
        $class = str_replace($name, $value, str_replace('\\', '/', $class));
        include($class . $suffix);
    }
});

*/
echo hello();
$user = new App\User();
$user->index();
