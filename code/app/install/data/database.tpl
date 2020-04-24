<?php

return [
    // 数据库类型
    'type'            => '[type]',
    // 服务器地址
    'hostname'        => '[hostname]',
    // 数据库名
    'database'        => '[database]',
    // 用户名
    'username'        => '[username]',
    // 密码
    'password'        => '[password]',
    // 端口
    'hostport'        => '[hostport]',
    // 连接dsn
    'dsn'             => '',
    // 数据库连接参数
    'params'          => [],
    // 数据库编码默认采用utf8
    'charset'         => 'utf8',
    // 数据库表前缀
    'prefix'          => '[prefix]',
    // 数据库调试模式
    'debug'           => true,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy'          => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate'     => false,
    // 读写分离后 主服务器数量
    'master_num'      => 1,
    // 指定从服务器序号
    'slave_no'        => '',
    // 是否严格检查字段是否存在
    'fields_strict'   => false,
    // 数据集返回类型
    'resultset_type'  => 'array',
    // 自动写入时间戳字段
    'auto_timestamp'  => true,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要进行SQL性能分析
    'sql_explain'     => true,
    //对象类型转换数组
    'resultset_type' => '\think\Collection',
    // 系统数据加密key
    'sys_data_key'    => '[sys_data_key]'
];