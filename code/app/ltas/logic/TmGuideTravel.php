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
 * 导游代交社费逻辑
 */
class TmGuideTravel extends LtasBase
{
    /**
     * 获取导游代付交社费列表
     */
    public function getTmGuideTravelList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelTmGuideTravel->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelTmGuideTravel->stat($where,'sum','total_price');

        return $list;
    }

    /**
     * 导游代付交社费添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function tmGuideTravelAdd($data = [])
    {

        $validate_result = $this->validateTmGuideTravel->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmGuideTravel->getError()];
        }

        $result = $this->modelTmGuideTravel->setInfo($data);

        $result && action_log('新增', '新增导游代付交社费，金额：' . $data['total_price']);
        
        return $result ? [RESULT_SUCCESS, '导游代付交社费添加成功', ""] : [RESULT_ERROR, $this->modelTmGuideTravel->getError()];
    }
    
    /**
     * 导游代付交社费编辑
     */
    public function tmGuideTravelEdit($data = [])
    {

        $result = $this->modelTmGuideTravel->setInfo($data);

        $result && action_log('编辑', '编辑导游代付交社费，金额：' .$data['total_price']);

        return $result ? [RESULT_SUCCESS, '导游代付交社费编辑成功', ""] : [RESULT_ERROR, $this->modelTmGuideTravel->getError()];

    }
    
    /**
     * 导游代付交社费删除
     */
    public function tmGuideTravelDel($where = [])
    {
        
        $result = $this->modelTmGuideTravel->deleteInfo($where,true);
        
        $result && action_log('删除', '删除导游代付交社费，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '导游代付交社费删除成功'] : [RESULT_ERROR, $this->modelTmGuideTravel->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getTmGuideTravelInfo($where = [], $field =true)
    {
        return $this->modelTmGuideTravel->getInfo($where);
    }


}
