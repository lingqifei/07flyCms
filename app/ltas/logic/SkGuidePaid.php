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
 * 导游代付其它费用表逻辑
 */
class SkGuidePaid extends LtasBase
{
    /**
     * 获取导游代付其它费用列表
     */
    public function getSkGuidePaidList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelSkGuidePaid->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelSkGuidePaid->stat($where,'sum','money');

        return $list;
    }

    /**
     * 导游代付其它费用添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skGuidePaidAdd($data = [])
    {

        $validate_result = $this->validateSkGuidePaid->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkGuidePaid->getError()];
        }

        $result = $this->modelSkGuidePaid->setInfo($data);

        $result && action_log('新增', '新增导游代付其它费用，金额：' . $data['money']);
        
        return $result ? [RESULT_SUCCESS, '导游代付其它费用添加成功', ""] : [RESULT_ERROR, $this->modelSkGuidePaid->getError()];
    }
    
    /**
     * 导游代付其它费用编辑
     */
    public function skGuidePaidEdit($data = [])
    {

        $result = $this->modelSkGuidePaid->setInfo($data);

        $result && action_log('编辑', '编辑导游代付其它费用，金额：' .$data['money']);

        return $result ? [RESULT_SUCCESS, '导游代付其它费用编辑成功', ""] : [RESULT_ERROR, $this->modelSkGuidePaid->getError()];

    }
    
    /**
     * 导游代付其它费用删除
     */
    public function skGuidePaidDel($where = [])
    {
        
        $result = $this->modelSkGuidePaid->deleteInfo($where,true);
        
        $result && action_log('删除', '删除导游代付其它费用，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '导游代付其它费用删除成功'] : [RESULT_ERROR, $this->modelSkGuidePaid->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getSkGuidePaidInfo($where = [], $field =true)
    {
        return $this->modelSkGuidePaid->getInfo($where);
    }


}
