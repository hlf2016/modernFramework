<?php

namespace core;

use core\request\RequestInterface;

class RouteCollection
{
    // 所有路由存放
    protected $routes = [];
    // 当前访问的路由
    protected $route_index = 0;
    // 当前组
    public $currGroup = [];
    // 获取所有路由
    public function getRoutes()
    {
        return $this->routes;
    }

    public function group($attributes = [], \Closure $callback)
    {
        $this->currGroup[] = $attributes;
        // $callback($this);  跟这个一样的效果
        // group的实现主要的这个$this  这个$this将当前状态传递到了闭包
        call_user_func($callback, $this);
        array_pop($this->currGroup);
    }

    // 增加/  如: GETUSER 改成 GET/USER
    public function addSlash(&$uri)
    {
        return $uri[0] == '/' ?: $uri = '/' . $uri;
    }

    // 增加路由
    public function addRoute($method, $uri, $uses)
    {
        // 前缀
        $prefix = '';
        // 中间件
        $middleware = [];
        // 命名空间
        $namespace = '';
        $this->addSlash($uri);

        foreach ($this->currGroup as $group) {
            $prefix .= $group['prefix'] ?? false;
            if ($prefix) {
                $this->addSlash($prefix);
            }
            // 合并组中间件
            $middleware = $group['middleware'] ?? [];
            // 拼接组的命名空间
            $namespace .= $group['namespace'] ?? '';
        }
        // 请求方式 转大写
        $method = strtoupper($method);
        $uri = $prefix . $uri;
        // 路由索引
        $this->route_index = $method . $uri;
        // 路由存储结构  用 GET/USER   这种方式做索引 一次性就找到了
        $this->routes[$this->route_index] = [
            // 请求类型
            'method' => $method,
            // 请求url
            'uri' => $uri,
            'action' => [
                'uses' => $uses,
                'middleware' => $middleware,
                'namespace' => $namespace
            ]
        ];
    }

    public function get($uri, $uses)
    {
        $this->addRoute('get', $uri, $uses);
        return $this;
    }

    public function post($uri, $uses)
    {
        $this->addRoute('post', $uri, $uses);
        return $this;
    }

    public function put($uri, $uses)
    {
        $this->addRoute('put', $uri, $uses);
        return $this;
    }
    public function delete($uri, $uses)
    {
        $this->addRoute('delete', $uri, $uses);
        return $this;
    }

    // 设置中间件
    public function middleware($class)
    {
        $this->routes[$this->route_index]['action']['middleware'][] = $class;
        return $this;
    }

    // 获取当前访问的路由
    public function getCurrRoute()
    {
        $routes = $this->getRoutes();
        $route_index = $this->route_index;
        if (isset($routes[$route_index])) {
            return $routes[$route_index];
        }
        $route_index .= '/';
        if (isset($routes[$route_index])) {
            return $routes[$route_index];
        }
        return false;
    }

    // 根据request执行路由
    public function dispatch(RequestInterface $request)
    {
        $method = $request->getMethod();
        $uri = $request->getUri();
        $this->route_index = $method . $uri;
        $route = $this->getCurrRoute();
        // 找不到路由
        if (!$route) {
            return 404;
        }
        $middleware = $route['action']['middleware'] ?? [];
        $routeDispatch = $route['action']['uses'];

        // 不是闭包 就是控制器了
        if (!$route['action']['uses'] instanceof \Closure) {
            $action = $route['action'];
            $uses = explode('@', $action['uses']);
            $controller = $action['namespace'] . '\\' . $uses[0]; // 控制器
            $method = $uses[1]; // 执行的方法
            $controllerInstance = new $controller;
            $middleware = array_merge($middleware, $controllerInstance->getMiddleware()); // 合并控制器中间件
            $routeDispatch = function ($request) use ($route, $controllerInstance, $method) {
                return $controllerInstance->callAction($method, [$request]);
            };
        }

        return \App::getContainer()->get('pipeline')->create()->setClass(
            $middleware
        )->run($routeDispatch)($request);
    }
}
