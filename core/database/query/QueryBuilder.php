<?php

namespace core\database\query;

use core\database\connection\Connection;

class QueryBuilder
{
    protected $connection;
    protected $grammer;
    public $binds;
    public $columns;
    public $distinct;
    public $from;
    public $union;
    public $bindings = [
        'select' => [],
        'from' => [],
        'join' => [],
        'where' => [],
        'groupBy' => [],
        'having' => [],
        'order' => [],
        'union' => [],
        'unionOrder' => [],
    ];

    protected $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>', 'like', 'like binary', 'not like', 'ilike', '&', '|', '^',
        '<<', '>>', 'rlike', 'not rlike', 'regexp', 'not regexp', '~', '~*', '!~', '!~*', 'similar to', 'not similar to',
        'not ilike', '~~*', '!~~*'
    ];

    public function __construct(Connection $connection, $grammar)
    {
        // 数据库连接
        $this->connection = $connection;
        // 拼接成sql的类
        $this->grammar = $grammar;
    }

    public function table(string $table, $as = null)
    {
        return $this->from($table, $as);
    }

    public function from(string $table, $as = null)
    {
        $this->from = $as ? "{$table} as {$as}" : $table;
        return $this;
    }

    public function get($columns  = ['*'])
    {
        if (is_array($columns)) {
            $columns = func_get_args();
        }
        $this->columns = $columns;
        $sql = $this->toSql();
        return $this->runSql($sql);
    }

    // 运行sql
    public function runSql($sql)
    {
        return $this->connection->select(
            $sql,
            $this->getBinds()
        );
    }

    public function where($column, $operator = null, $value = null, $joiner = 'and')
    {
        if (is_array($column)) // 如果是 where(['id' => '2','name' => 'xxh']) 这种
            foreach ($column as $col => $value)
                $this->where($col, '=', $value);

        if (!in_array($operator, $this->operators)) { // 操作符不存在
            $value = $operator;
            $operator = '=';
        }

        $type = 'Basic';
        $this->wheres[] = compact(
            'type',
            'column',
            'operator',
            'value',
            'joiner'
        ); // 存到wheres变量

        $this->binds[] = $value;
        return $this;
    }

    public function orWhere($column, $operator = null, $value = null)
    {

        return $this->where($column, $operator, $value, 'or');
    }

    public function find($id, $columns = ['*'], $key = 'id')
    {
        return $this->where($key, $id)->get($columns);
    }

    public function whereLike($column, $operator = null, $value = null)
    {
        return $this->where($column, $operator, $value, 'like');
    }

    public function toSql() // 编译成sql
    {
        return $this->grammar->compileSql($this);
    }

    public function getBinds() // 绑定
    {
        return $this->binds;
    }
}