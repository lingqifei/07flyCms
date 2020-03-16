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
 * 其它支出 逻辑
 */
class SkOrderExpend extends LtasBase
{
    /**
     * 获取其它支出列表
     */
    public function getSkOrderExpendList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelSkOrderExpend->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelSkOrderExpend->stat($where,'sum','total_price');

        return $list;
    }

    /**
     * 其它支出添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skOrderExpendAdd($data = [])
    {

        $validate_result = $this->validateSkOrderExpend->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkOrderExpend->getError()];
        }

        $result = $this->modelSkOrderExpend->setInfo($data);

        $result && action_log('新增', '新增其它支出，金额：' . $data['total_price']);
        
        return $result ? [RESULT_SUCCESS, '其它支出添加成功', ""] : [RESULT_ERROR, $this->modelSkOrderExpend->getError()];
    }
    
    /**
     * 其它支出编辑
     */
    public function skOrderExpendEdit($data = [])
    {

        $result = $this->modelSkOrderExpend->setInfo($data);

        $result && action_log('编辑', '编辑其它支出，金额：' .$data['total_price']);

        return $result ? [RESULT_SUCCESS, '其它支出编辑成功', ""] : [RESULT_ERROR, $this->modelSkOrderExpend->getError()];

    }
    
    /**
     * 其它支出删除
     */
    public function skOrderExpendDel($where = [])
    {
        
        $result = $this->modelSkOrderExpend->deleteInfo($where,true);
        
        $result && action_log('删除', '删除其它支出，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '其它支出删除成功'] : [RESULT_ERROR, $this->modelSkOrderExpend->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getSkOrderExpendInfo($where = [], $field =true)
    {
        return $this->modelSkOrderExpend->getInfo($where);
    }


}
