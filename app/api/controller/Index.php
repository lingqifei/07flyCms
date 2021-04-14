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

/**
 * 首页控制器
 */
class Index extends ControllerBase
{
    
    /**
     * 首页方法
     */
    public function index()
    {
        
        $list = $this->logicDocument->getApiList([], true, 'sort');
        
        $code_list = $this->logicDocument->apiErrorCodeData();
        
        $this->assign('code_list', $code_list);
        
        $content = $this->fetch('content_default');

        $this->assign('content', $content);
        
        $this->assign('list', $list);
        
        return $this->fetch();
    }
    
    /**
     * API详情
     */
    public function details($id = 0)
    {

        $list = $this->logicDocument->getApiList([], true, 'sort');
        
        $info = $this->logicDocument->getApiInfo(['id' => $id]);
        
        $this->assign('info', $info);
        
        // 测试期间使用token ， 测试完成请删除
        $this->assign('test_access_token', get_access_token());
        
        $content = $this->fetch('content_template');
        if (IS_AJAX) {
            return throw_response_exception(['content' => $content]);
        }
        
        $this->assign('content', $content);
        
        $this->assign('list', $list);
        
        return $this->fetch('index');
    }
}
