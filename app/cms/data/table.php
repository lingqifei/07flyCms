<?php
// +----------------------------------------------------------------------
// | 07FLY系统 [基于ThinkPHP5.0开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2021 http://www.07fly.xyz
// +----------------------------------------------------------------------
// | 07FLY承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 开发人生 <574249366@qq.com>
// +----------------------------------------------------------------------
/**
 * 表结构基本信息
 */
/**
 * type 类型
 * length 类型长度
 * unsigned 是否无符号
 * autoincrement 是否自动增长
 * required  是否必填
 * default  默认值
 * comment  注释
 */
return [
	'tables' => [

		'taglist' => [
			//表名
			'table_name' => 'taglist',
			//表注释
			'comment' => '[系统]系统用户表',
			//'engine' => 'InnoDB',
			'engine' => 'InnoDB',
			'charset' => 'utf8mb4',
			'collate' => 'utf8mb4_general_ci',
			//字段信息
			'columns' => [
				'id' => ['type' => 'int', 'length' => 16, 'unsigned' => false, 'autoincrement' => true, 'comment' => '主id',],
			],
			//主键 多个主键['user_id','name']
			'primary' => ['id'],
			//索引
			'index' => [
				'tid' => ['type' => "normal", 'columns' => ['tid','aid','typeid']],
				//'ind_age' => ['type' => "normal", 'columns' => ['test002']],
			],
		],

	],//end tables;

];