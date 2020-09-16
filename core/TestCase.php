<?php

namespace core;

use core\request\PhpRequest;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    // 路由请求响应的结果
    protected $response;

    // 基境共享
    protected function setUp(): void
    {
        require_once __DIR__ . '/../app.php';
    }

    // 调用路由
    public function call($uri, $method)
    {
        // 将request绑定到容器
        \App::getContainer()->bind(\core\request\RequestInterface::class, function () use ($uri, $method) {
            return PhpRequest::create($uri, $method, $header = []);
        });

        // 响应
        return $this->response = app('response')->setContent(
            // 路由
            app('router')->dispatch(
                // 调用绑定request
                \App::getContainer()->get(\core\request\RequestInterface::class)
            )
        );
    }

    // get方式调用
    public function get($uri, $params = [])
    {
        $this->call($uri, 'GET', $params);
        return $this;
    }

    // post方式调用
    public function post($uri, $params = [])
    {
        $this->call($uri, 'POST', $params);
        return $this;
    }

    // 断言状态码是否一样
    protected function assertStatusCode($statusCode)
    {
        $this->assertEquals($statusCode, $this->response->getStatusCode());
        return $this;
    }

    // ... 其他断言不写了 参考的断言状态码

    // 代理模式 访问response
    public function __call($name, $arguments)
    {
        return $this->response->{$name}(...$arguments);
    }
}
