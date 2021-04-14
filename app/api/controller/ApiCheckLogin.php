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

namespace app\api\controller;

use app\common\controller\ControllerBase;
use think\Hook;

/**
 * 接口基类控制器
 */
class ApiCheckLogin extends ApiBase
{
    
    /**
     * 基类初始化
     */
    public function __construct()
    {
        
        parent::__construct();
        
        $this->logicApiBase->checkParam($this->param);
        

    }

}
