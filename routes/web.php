<?php

$router->get('/hello', function () {
    return '123 hello';
}); //->middleware(\App\middleware\WebMiddleWare::class);

$router->get('/test', function () {
    return '456 test';
});

$router->get('/config', function () {
    echo  App::getContainer()->get('config')->get('database.connections.mysql_one.driver') . '<hr/>';
});

$router->get('/db', function () {
    $id = 1;
    var_dump(
        \App::getContainer()->get('db')->table('keywords')->where('id', 2)->get()
    );
});

$router->get('/model', function () {
    $users = \App\User::Where('id', 1)->orWhere('id', 2)->get();
    foreach ($users as $user) {
        echo $user->say() . "<br/>";
    }
});

$router->get('/user', 'UserController@index');

$router->get('view/blade', function () {
    $str = '这是Blade模版引擎';
    return view('blade.index', compact('str'));
});

$router->get('view/thinkphp', function () {
    $str = '这是ThinkPHP模版引擎';
    return view('thinkphp.index', compact('str'));
});

$router->get('log/stack', function () {
    App::getContainer()->get('log')->debug('{lang} 是世界上最好的语言 ', ['lang' => 'PHP']);
    App::getContainer()->get('log')->info('Hello World');;
});

$router->get('exception', function () {
    throw new \App\exceptions\ErrorMessageException('error ok');
});

$router->get('error', function () {
    // hello world;
});
