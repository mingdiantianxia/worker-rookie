<?php
return [
    'env' => 'prod',//环境名 local_debug本地调试，dev开发，test测试，prod正式

    //api签名秘钥
    'secret' => '',

    //rsa公钥
    'rsa_public_key' => '',
    //rsa秘钥
    'rsa_private_key' => '',

    'db'=> [
        'database_type' => 'mysql',
        'database_name' => 'test',
        'server' => '172.18.1.127',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        // 可选参数
        'port' => 3306,
        // 可选，定义表的前缀
        'prefix' => '',
    ],

    'redis' => [
            //redis服务器地址
            'host'  => '172.18.1.127',
            //redis端口
            'port'  => '6379',
            //redis密码
            'password' => '',
            'select' => 1,
            //连接超时
            'timeout' => 10,
            //持久链接
            'persistent' => true,
    ],

    //php命令路径
    "phpbin" => "/usr/local/php/bin/php",

    'log' => [
            'path' => WORKER_PROJECT_PATH . '/runtime/log/',
    ],
];
