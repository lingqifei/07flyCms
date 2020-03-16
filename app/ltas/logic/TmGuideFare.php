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
 * 导游代付车费表逻辑
 */
class TmGuideFare extends LtasBase
{
    /**
     * 获取导游代付车费列表
     */
    public function getTmGuideFareList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelTmGuideFare->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelTmGuideFare->stat($where,'sum','money');

        return $list;
    }

    /**
     * 导游代付车费添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function tmGuideFareAdd($data = [])
    {

        $validate_result = $this->validateTmGuideFare->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmGuideFare->getError()];
        }

        $result = $this->modelTmGuideFare->setInfo($data);

        $result && action_log('新增', '新增导游代付车费，导游：' . $data['guide_name']);
        
        return $result ? [RESULT_SUCCESS, '导游代付车费添加成功', ""] : [RESULT_ERROR, $this->modelTmGuideFare->getError()];
    }
    
    /**
     * 导游代付车费编辑
     */
    public function tmGuideFareEdit($data = [])
    {

        $result = $this->modelTmGuideFare->setInfo($data);

        $result && action_log('编辑', '编辑导游代付车费，金额：' .$data['money']);

        return $result ? [RESULT_SUCCESS, '导游代付车费编辑成功', ""] : [RESULT_ERROR, $this->modelTmGuideFare->getError()];

    }
    
    /**
     * 导游代付车费删除
     */
    public function tmGuideFareDel($where = [])
    {
        
        $result = $this->modelTmGuideFare->deleteInfo($where,true);
        
        $result && action_log('删除', '删除导游代付车费，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '导游代付车费删除成功'] : [RESULT_ERROR, $this->modelTmGuideFare->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getTmGuideFareInfo($where = [], $field =true)
    {
        return $this->modelTmGuideFare->getInfo($where);
    }


}
