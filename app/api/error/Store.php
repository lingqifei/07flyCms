<?php
/**
 * 零起飞-(07FLY-ERP)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * AuthDomainor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\api\error;

class Store
{
    
    public static $appNotExist           	= [API_CODE_NAME => 1020001, API_MSG_NAME => '请求APP应用市场不存在'];
    
    public static $appNotOrderPay  			= [API_CODE_NAME => 1020002, API_MSG_NAME => '请求的APP未授权'];
    
    public static $registerFail             = [API_CODE_NAME => 1020003, API_MSG_NAME => '注册失败'];
    
    public static $oldOrNewPassword         = [API_CODE_NAME => 1020004, API_MSG_NAME => '旧密码或新密码不能为空'];
    
    public static $changePasswordFail       = [API_CODE_NAME => 1020005, API_MSG_NAME => '密码修改失败'];
    
}
