<?php

namespace core;

class Config
{
    protected $config = [];
    // 扫描 config 文件夹 加入到配置的大数组
    public function init()
    {
        // 遍历一个文件文件下的以 .php 结尾的所有文件
        foreach (glob(FRAME_BASE_PATH . '/config/*.php') as $file) {
            $key = str_replace('.php', '', basename($file));
            $this->config[$key] = require $file;
        }
    }

    // 获取配置
    public function get($key)
    {
        $keys = explode('.', $key);
        $config = $this->config;
        foreach ($keys as $key) {
            $config = $config[$key];
        }
        return $config;
    }

    // 设置配置的值
    public function set($key, $val)
    {
        $keys = explode('.', $key);
        $newConfig = &$this->config;
        foreach ($keys as $key) {
            $newConfig = &$newConfig[$key];
        }
        $newConfig = $val;
    }
}
