<?php

namespace core\view;

class View
{
    protected $engine;

    // 传入实现者
    public function __construct(ViewInterface $engine)
    {
        $this->engine = $engine;
        $this->engine->init();
    }

    // 利用代理模式 来调用
    public function __call($method, $args)
    {
        return $this->engine->$method(...$args);
    }
}
