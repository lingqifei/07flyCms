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

namespace app\ltas\logic;

/**
 * 散客票务表逻辑
 */
class SkOrderTicketBuy extends LtasBase
{
    /**
     * 获取散客购买票务表列表
     */
    public function getSkOrderTicketBuyList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list =$this->modelSkOrderTicketBuy->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     * 散客票务表添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skOrderTicketBuyAdd($data = [])
    {


        $validate_result = $this->validateSkOrderTicketBuy->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkOrderTicketBuy->getError()];
        }

        $result = $this->modelSkOrderTicketBuy->setInfo($data);

        $result && action_log('新增', '新增散客购票，name：' . $data['train_name']);
        
        return $result ? [RESULT_SUCCESS, '散客购票添加成功', ""] : [RESULT_ERROR, $this->modelSkOrderTicketBuy->getError()];
    }
    
    /**
     * 散客票务表编辑
     */
    public function skOrderTicketBuyEdit($data = [])
    {

        $result = $this->modelSkOrderTicketBuy->setInfo($data);

        $result && action_log('编辑', '编辑散客购票，name：' );

        return $result ? [RESULT_SUCCESS, '散客散客购票编辑成功', ""] : [RESULT_ERROR, $this->modelSkOrderTicketBuy->getError()];

    }
    
    /**
     * 散客票务表删除
     */
    public function skOrderTicketBuyDel($where = [])
    {
        
        $result = $this->modelSkOrderTicketBuy->deleteInfo($where,true);
        
        $result && action_log('删除', '删除散客购票，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '散客购票删除成功'] : [RESULT_ERROR, $this->modelSkOrderTicketBuy->getError()];
    }
    
    /**
     * 获取散客票务表信息
     */
    public function getSkOrderTicketBuyInfo($where = [], $field =true)
    {
        return $this->modelSkOrderTicketBuy->getInfo($where);
    }


}
