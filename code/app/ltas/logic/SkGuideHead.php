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
 * 导游代付人头费表逻辑
 */
class SkGuideHead extends LtasBase
{
    /**
     * 获取导游代付人头费列表
     */
    public function getSkGuideHeadList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelSkGuideHead->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelSkGuideHead->stat($where,'sum','total_money');

        return $list;
    }

    /**
     * 导游代付人头费添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skGuideHeadAdd($data = [])
    {

        $validate_result = $this->validateSkGuideHead->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkGuideHead->getError()];
        }

        $result = $this->modelSkGuideHead->setInfo($data);

        $result && action_log('新增', '新增导游代付人头费，金额：' . $data['total_price']);
        
        return $result ? [RESULT_SUCCESS, '导游代付人头费添加成功', ""] : [RESULT_ERROR, $this->modelSkGuideHead->getError()];
    }
    
    /**
     * 导游代付人头费编辑
     */
    public function skGuideHeadEdit($data = [])
    {

        $result = $this->modelSkGuideHead->setInfo($data);

        $result && action_log('编辑', '编辑导游代付人头费，金额：' .$data['total_price']);

        return $result ? [RESULT_SUCCESS, '导游代付人头费编辑成功', ""] : [RESULT_ERROR, $this->modelSkGuideHead->getError()];

    }
    
    /**
     * 导游代付人头费删除
     */
    public function skGuideHeadDel($where = [])
    {
        
        $result = $this->modelSkGuideHead->deleteInfo($where,true);
        
        $result && action_log('删除', '删除导游代付人头费，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '导游代付人头费删除成功'] : [RESULT_ERROR, $this->modelSkGuideHead->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getSkGuideHeadInfo($where = [], $field =true)
    {
        return $this->modelSkGuideHead->getInfo($where);
    }


}
