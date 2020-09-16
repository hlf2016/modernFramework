<?php

namespace core\log;

use core\log\driver\StackLogger;

class Logger
{
    // 所有的实例化的通道  就是多例而已
    protected $channels = [];
    protected $config;

    public function __construct()
    {
        $this->config = \App::getContainer()->get('config')->get('log');
    }

    public function channel($name = null)
    {
        // 选择使用那个日志记录
        if (!$name) {
            // 没选择名字
            $name = $this->config['default'];
        }

        if (isset($this->channels[$name])) {
            return $this->channels[$name];
        }

        $config = \App::getContainer()->get('config')->get('log.channels.' . $name);
        //如:$config['driver'] = stack, 则调用createStack($config);
        return $this->channels[$name] = $this->{'create' . ucfirst($config['driver'])}($config);
    }

    // 放在同一个文件
    public function createStack($config)
    {
        return new StackLogger($config);
    }

    public function __call($method, $parameters)
    {
        return $this->channel()->$method(...$parameters);
    }
}
