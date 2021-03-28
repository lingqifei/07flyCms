<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

// PHP版本验证需要大于5.6.0
if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    
    die('07FLY-ERP Require PHP > 5.6.0 !');
}

// 定义应用目录
define('APP_PATH', __DIR__ . '/../app/');

// 检测是否安装
if (!file_exists(APP_PATH . 'database.php')) {
    header("location:./install.php");
    exit;
}

// 加载框架引导文件
require __DIR__ . '/../core/start.php';