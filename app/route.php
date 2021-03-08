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
//   'list/:tid' => 'index/lists/index',
//   'tags/:tagid' => 'index/tags/lists',
//    'show/[:aid]' => ['index/view/index',['method'=>'get','ext'=>'html']],

    //信息 列表路由

    'sitemap' => ['index/index/sitemap',['method'=>'get','ext'=>'html']],

    //信息路由

//    'xue/[:province]/[:city]/[:county]/:tid' => ['index/Info/lists',['method'=>'get']],
//    'xue/[:province]/[:city]/:tid' => ['index/Info/lists',['method'=>'get']],
//    'xue/[:province]/:tid' => ['index/Info/lists',['method'=>'get']],
//    'xue/<tid>/[:pageNum]$' => ['index/Info/lists',['method'=>'get']],
//    'xue/<tid>' => ['index/Info/lists',['method'=>'get']],
//
//    'xue/types' => ['index/InfoType/lists',['method'=>'get','ext'=>'html','merge_extra_vars'=>true]],

    //地区路由
//    'city/[:province]/[:city]/[:county]' => ['index/City/index',['method'=>'get','ext'=>'html']],
//    'city/[:province]/[:city]' => ['index/City/index',['method'=>'get','ext'=>'html']],
//    'city/[:province]' => ['index/City/index',['method'=>'get','ext'=>'html']],
//    'city' => ['index/city/citys',['method'=>'get','ext'=>'html']],

    //api  版本设置
//    'authorize/:version/:controller/:function'=>'authorize/:version.:controller/:function'// 有方法名时
];
