<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+
empty(STATIC_DOMAIN) ? $static = [] :  $static['__STATIC__'] = STATIC_DOMAIN . SYS_DS_PROS . SYS_STATIC_DIR_NAME;
//配置文件
return [
    
    // 视图输出字符串内容替换
    'view_replace_str' => $static,

    /* 模板布局配置 */
    'template'  =>  [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写
        'auto_rule'    => 1,
        // 模板路径
        'view_path'    => PATH_PUBLIC.DS.'theme/'.config('web_theme').'/',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
        //加载扩展标签
        'taglib_pre_load'     =>    'app\index\taglib\Fly',
    ]
];
