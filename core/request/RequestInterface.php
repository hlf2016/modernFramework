<?php

namespace core\request;

interface RequestInterface
{
    // 初始化
    public function __construct($uri, $method, $headers);

    // 创建 request 对象
    public static function create($uri, $method, $headers);

    // 获取请求 url
    public function getUri();

    // 获取请求方法
    public function getMethod();

    // 获取请求头
    public function getHeader();
}
