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
 * 其它收入 逻辑
 */
class TmOrderRevenue extends LtasBase
{
    /**
     * 获取其它收入列表
     */
    public function getTmOrderRevenueList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelTmOrderRevenue->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelTmOrderRevenue->stat($where,'sum','total_price');

        return $list;
    }

    /**
     * 其它收入添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function tmOrderRevenueAdd($data = [])
    {

        $validate_result = $this->validateTmOrderRevenue->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmOrderRevenue->getError()];
        }

        $result = $this->modelTmOrderRevenue->setInfo($data);

        $result && action_log('新增', '新增其它收入，金额：' . $data['total_price']);
        
        return $result ? [RESULT_SUCCESS, '其它收入添加成功', ""] : [RESULT_ERROR, $this->modelTmOrderRevenue->getError()];
    }
    
    /**
     * 其它收入编辑
     */
    public function tmOrderRevenueEdit($data = [])
    {

        $result = $this->modelTmOrderRevenue->setInfo($data);

        $result && action_log('编辑', '编辑其它收入，金额：' .$data['total_price']);

        return $result ? [RESULT_SUCCESS, '其它收入编辑成功', ""] : [RESULT_ERROR, $this->modelTmOrderRevenue->getError()];

    }
    
    /**
     * 其它收入删除
     */
    public function tmOrderRevenueDel($where = [])
    {
        
        $result = $this->modelTmOrderRevenue->deleteInfo($where,true);
        
        $result && action_log('删除', '删除其它收入，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '其它收入删除成功'] : [RESULT_ERROR, $this->modelTmOrderRevenue->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getTmOrderRevenueInfo($where = [], $field =true)
    {
        return $this->modelTmOrderRevenue->getInfo($where);
    }


}
