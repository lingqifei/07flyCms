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
 * 模块基本信息
 */
return [
    // 模块名[必填]
    'name'        => 'admin',
    // 模块标题[必填]
    'title'       => '系统管理模块',
    // 模块唯一标识[必填]，格式：module.[应用市场ID].模块名[应用市场分支ID]
    'identifier'  => 'module.lingqifei.admin',
    // 主题模板[必填]，默认default
    'theme'        => 'default',
    // 模块图标[选填]
    'icon'        => '/static/admin/admin.png',
    // 模块简介[选填]
    'intro' => '系统核心模块，用于后台各项管理功能模块及功能拓展',
    // 开发者[必填]
    'author'      => 'lingqifei',
    // 开发者网址[选填]
    'author_url'  => 'http://www.07fly.top',
    // 版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
    // 主版本号【位数变化：1-99】：当模块出现大更新或者很大的改动，比如整体架构发生变化。此版本号会变化。
    // 次版本号【位数变化：0-999】：当模块功能有新增或删除，此版本号会变化，如果仅仅是补充原有功能时，此版本号不变化。
    // 修订版本号【位数变化：0-999】：一般是 Bug 修复或是一些小的变动，功能上没有大的变化，修复一个严重的bug即发布一个修订版。
    'version'     => '1.0.0',
    //关联数据表是指模块所需要的数据表名称，如果有多个表用英文逗号（,）分隔。如：table1,table2
    'tables'     => 'action_log,
addon,
config,
driver,
hook,
picture,
region,
sequence,
sys_auth,
sys_auth_access,
sys_dept,
sys_menu,
sys_module,
sys_org,
sys_postion,
sys_user',
];