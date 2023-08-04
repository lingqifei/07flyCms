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

		//会员表
		'member' => [
			'table_name' => 'member',
			'comment' => '[会员]注册会员列表',
			'engine' => 'MyISAM',
			'charset' => 'utf8mb4',
			'collate' => 'utf8mb4_general_ci',
			'columns' => [
				'id' => ['type' => 'int', 'length' => 16, 'unsigned' => false, 'autoincrement' => true, 'comment' => '关键id',],
				'is_recharge' => ['type' => 'int', 'length' => 2, 'required' => true, 'default' => 0, 'comment' => '0=未充值，1=充值过',],
				'expire_level_time' => ['type' => 'datetime', 'required' => false, 'comment' => 'vip会员到期时间',],
			],
			//主键 多个主键['user_id','name']
			'primary' => ['id'],
			'index' => [],
		],

        //会员表
        'member_order' => [
            'table_name' => 'member_order',
            'comment' => '[会员]会员订单表',
            'engine' => 'MyISAM',
            'charset' => 'utf8mb4',
            'collate' => 'utf8mb4_general_ci',
            'columns' => [
                'id' => ['type' => 'int', 'length' => 16, 'unsigned' => false, 'autoincrement' => true, 'comment' => '关键id',],
                'pay_transaction_no' => ['type' => 'varchar', 'length' => 256, 'required' => true, 'default' => 0, 'comment' => '支付渠道单号',],
            ],
            //主键 多个主键['user_id','name']
            'primary' => ['id'],
            'index' => [],
        ],

	//表结构结束 ********************************************************************************************************

	],//end tables;

];