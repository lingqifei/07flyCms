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
 * 签单支出表逻辑
 */
class SkOrderSignbill extends LtasBase
{
    /**
     * 获取签单支出列表
     */
    public function getSkOrderSignbillList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelSkOrderSignbill->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelSkOrderSignbill->stat($where,'sum','total_price');

        return $list;
    }

    /**
     * 签单支出添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skOrderSignbillAdd($data = [])
    {

        $validate_result = $this->validateSkOrderSignbill->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkOrderSignbill->getError()];
        }

        $result = $this->modelSkOrderSignbill->setInfo($data);

        $result && action_log('新增', '新增签单支出，金额：' . $data['total_price']);
        
        return $result ? [RESULT_SUCCESS, '签单支出添加成功', ""] : [RESULT_ERROR, $this->modelSkOrderSignbill->getError()];
    }
    
    /**
     * 签单支出编辑
     */
    public function skOrderSignbillEdit($data = [])
    {

        $result = $this->modelSkOrderSignbill->setInfo($data);

        $result && action_log('编辑', '编辑签单支出，金额：' .$data['total_price']);

        return $result ? [RESULT_SUCCESS, '签单支出编辑成功', ""] : [RESULT_ERROR, $this->modelSkOrderSignbill->getError()];

    }
    
    /**
     * 签单支出删除
     */
    public function skOrderSignbillDel($where = [])
    {
        
        $result = $this->modelSkOrderSignbill->deleteInfo($where,true);
        
        $result && action_log('删除', '删除签单支出，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '签单支出删除成功'] : [RESULT_ERROR, $this->modelSkOrderSignbill->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getSkOrderSignbillInfo($where = [], $field =true)
    {
        return $this->modelSkOrderSignbill->getInfo($where);
    }


}
