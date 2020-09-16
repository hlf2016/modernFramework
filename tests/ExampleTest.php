<?php

class ExampleTest extends \core\TestCase
{
    // 测试config
    public function testDatabaseDefault()
    {
        // 断言内容是 "mysql_one"
        $this->assertEquals('mysql_one', config('database.default'));
    }

    // 测试GET路由
    public function testGetRoute()
    {
        // 断言状态码是200
        $this->get('/hello')->assertStatusCode(200);
    }

    // 测试POST路由
    public function testPostRoute()
    {
        $res = $this->get('/hello');
        // 断言返回的内容是 "你在访问hello"
        $this->assertEquals('你在访问 hello', $res->getContent());
    }
}
