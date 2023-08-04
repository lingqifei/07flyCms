<?php
/*
*
* portalmember.MemberOrder  前台会员管理中心-频道模型
*
* =========================================================
* 零起飞网络 - 专注于网站建设服务和行业系统开发
* 以质量求生存，以服务谋发展，以信誉创品牌 !
* ----------------------------------------------
* @copyright	Copyright (C) 2017-2021 07FLY Network Technology Co,LTD.
* @license    For licensing, see LICENSE.html or http://www.07fly.xyz/crm/license
* @author ：kfrs <goodkfrs@QQ.com> 574249366
* @version ：1.0
* @link ：http://www.07fly.xyz
*/
namespace app\portalmember\controller;


/**
 * 会员订单管理=》首页
 */
class MemberOrder extends MemberBaseAuth
{

    /**
     * 登录
     */
    public function show()
    {

        $where['member_id']=['=',$this->member['id']];
        if (!empty($this->param['class'])) {
            $this->assign('class', $this->param['class']);
            $class = $this->param['class'];
        } else {
            $this->assign('class', 'all');
            $class = 'all';
        }

        switch ($class) {
            case 'pay':
                $where['payment_status'] = ['=', '1'];
                break;
            case 'nopay':
                $where['payment_status'] = ['=', '0'];
                break;
        }

        $list=$this->logicMemberOrder->getMemberOrderList($where);
        $pages=$list->render('info,pre,next,pageno',DB_LIST_ROWS);
        $this->assign('pages', $pages);
        $this->assign('list', $list);
        return $this->fetch('member_order_show');
    }

    /**
     * 订单支付
     */
    public function pay()
    {

        IS_POST && $this->jump($this->logicMemberOrder->getMemberOrderPay($this->param));
        $info=$this->logicMemberOrder->getMemberOrderInfo($this->param);
        $payinfo=$this->logicMemberOrder->getMemberOrderPay($this->param);
        if ($payinfo[0] == RESULT_ERROR) {
            $this->jump($payinfo);
            exit;
        }

        $this->assign('info', $info);
        $this->assign('payinfo', $payinfo[3]);
        return $this->fetch('member_order_pay');
    }

    /**
     * 订单支付=>检查
     */
    public function pay_check()
    {
        IS_POST && $this->jump($this->logicMemberOrder->memberOrderCheck($this->param));
    }

    /**
     * 删除
     */
    public function del()
    {
       $this->jump($this->logicMemberOrder->memberOrderDel($this->param));
    }

}
?>