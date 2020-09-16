<?php

return [
    'default' => 'stack',
    'channels' => [
        // 文件类型的日志
        'stack' => [
            'driver' => 'stack',
            'path' => FRAME_BASE_PATH . '/storage/',
            // 格式化类型  分别代表:[日期][日志级别]消息
            'format' => '[%s][%s] %s',
        ],
        'daily' => [
            'driver' => 'daily',
            'path' => FRAME_BASE_PATH . '/storage/',
            // 格式化类型
            'format' => '[%s][%s] %s',
        ]
    ]
];
