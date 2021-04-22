<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],

    //cms 路由设置
    'list/:tid' => ['index/lists/index',['method'=>'get','ext'=>'html']],
   	'tags/:tagid' => 'index/tags/lists',
    'show/[:aid]' => ['index/view/index',['method'=>'get','ext'=>'html']],


	'doc/:bookid/[:chapid]' => ['index/book/read',['method'=>'get','ext'=>'html']],
	'doc/:bookid' => ['index/book/read',['method'=>'get','ext'=>'']],

    //api  版本设置
//    'authorize/:version/:controller/:function'=>'authorize/:version.:controller/:function'// 有方法名时
];
