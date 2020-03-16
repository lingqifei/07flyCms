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
 * 团队票务表逻辑
 */
class TmOrderTicketBuy extends LtasBase
{
    /**
     * 获取团队购买票务表列表
     */
    public function getTmOrderTicketBuyList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list =$this->modelTmOrderTicketBuy->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     * 团队票务表添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function tmOrderTicketBuyAdd($data = [])
    {


        $validate_result = $this->validateTmOrderTicketBuy->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmOrderTicketBuy->getError()];
        }

        $result = $this->modelTmOrderTicketBuy->setInfo($data);

        $result && action_log('新增', '新增团队购票，name：' . $data['train_name']);
        
        return $result ? [RESULT_SUCCESS, '团队购票添加成功', ""] : [RESULT_ERROR, $this->modelTmOrderTicketBuy->getError()];
    }
    
    /**
     * 团队票务表编辑
     */
    public function tmOrderTicketBuyEdit($data = [])
    {

        $result = $this->modelTmOrderTicketBuy->setInfo($data);

        $result && action_log('编辑', '编辑团队购票，name：' );

        return $result ? [RESULT_SUCCESS, '团队团队购票编辑成功', ""] : [RESULT_ERROR, $this->modelTmOrderTicketBuy->getError()];

    }
    
    /**
     * 团队票务表删除
     */
    public function tmOrderTicketBuyDel($where = [])
    {
        
        $result = $this->modelTmOrderTicketBuy->deleteInfo($where,true);
        
        $result && action_log('删除', '删除团队购票，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '团队购票删除成功'] : [RESULT_ERROR, $this->modelTmOrderTicketBuy->getError()];
    }
    
    /**
     * 获取团队票务表信息
     */
    public function getTmOrderTicketBuyInfo($where = [], $field =true)
    {
        return $this->modelTmOrderTicketBuy->getInfo($where);
    }


}
