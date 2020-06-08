<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Channelor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\index\controller;

use think\Controller;

class Guestbook extends IndexBase{

    public $aid = '';
    public $type = '';

    /**
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function add(){
        if(empty($this->param['tid']) || empty($this->param['addfield'])){
            $this->tid=$this->param['tid'];
        }else{
            $this->jump($this->logicGuestbook->guestbookAdd($this->param));
        }
    }
}
