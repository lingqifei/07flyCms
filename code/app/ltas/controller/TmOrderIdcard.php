<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\controller;

use think\db;
/**
 * 身份证信息
 */
class TmOrderIdcard extends LtasBase
{

    /**
     * 列表
     */
    public function show()
    {
        $where=$this->logicTmOrderIdcard->getWhere($this->param);

        $list =$this->logicTmOrderIdcard->getTmOrderIdcardList($where);

        $this->assign('list', $list);

        return  $this->fetch('show');
    }


    /**
     * 列表
     */
    public function show_json()
    {
        $where=$this->logicTmOrderIdcard->getWhere($this->param);

        $list['data'] =$this->logicTmOrderIdcard->getTmOrderIdcardList($where);

       return $list;
    }

    /**
     * 列表=》下载
     */
    public function down()
    {
        $where=$this->logicTmOrderIdcard->getWhere($this->param);

        $this->logicTmOrderIdcard->getTmOrderIdcardListDown($where);

    }
}
