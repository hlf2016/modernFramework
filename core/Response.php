<?php

namespace core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response
{
    // 要发送的请求头
    protected $headers = [];
    // 要发送的内容
    protected $content = '';
    // 发送状态码
    protected $code = 200;

    // 发送内容
    public function sendContent()
    {
        echo $this->content;
    }

    // 发送头部
    public function sendHeaders()
    {
        foreach ($this->headers as $key => $header) {
            header($key . ':' . $header);
        }
    }

    // 发送
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
        return $this;
    }

    // 设置内容
    public function setContent($content)
    {
        if (is_array($content)) {
            $content = json_encode($content);
        }
        $this->content = $content;
        return $this;
    }

    // 设置 headers
    public function setHeaders($headers)
    {
        if (!is_array($headers)) {
            $headers = [];
        }
        $this->headers = $headers;
        return $this;
    }

    // 获取内容
    public function getContent()
    {
        return $this->content;
    }

    // 获取状态码
    public function getStatusCode()
    {
        return $this->code;
    }

    // 设置状态码
    public function setCode(int $code)
    {
        $this->code = $code;
        return $this;
    }
}
