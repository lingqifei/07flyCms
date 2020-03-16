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
 * 导游代付景点门票表逻辑
 */
class TmGuideScenic extends LtasBase
{
    /**
     * 获取导游代付景点门票列表
     */
    public function getTmGuideScenicList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelTmGuideScenic->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelTmGuideScenic->stat($where,'sum','total_price');

        return $list;
    }

    /**
     * 导游代付景点门票添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function tmGuideScenicAdd($data = [])
    {

        $validate_result = $this->validateTmGuideScenic->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmGuideScenic->getError()];
        }

        $result = $this->modelTmGuideScenic->setInfo($data);

        $result && action_log('新增', '新增导游代付景点门票，金额：' . $data['total_price']);
        
        return $result ? [RESULT_SUCCESS, '导游代付景点门票添加成功', ""] : [RESULT_ERROR, $this->modelTmGuideScenic->getError()];
    }
    
    /**
     * 导游代付景点门票编辑
     */
    public function tmGuideScenicEdit($data = [])
    {

        $result = $this->modelTmGuideScenic->setInfo($data);

        $result && action_log('编辑', '编辑导游代付景点门票，金额：' .$data['total_price']);

        return $result ? [RESULT_SUCCESS, '导游代付景点门票编辑成功', ""] : [RESULT_ERROR, $this->modelTmGuideScenic->getError()];

    }
    
    /**
     * 导游代付景点门票删除
     */
    public function tmGuideScenicDel($where = [])
    {
        
        $result = $this->modelTmGuideScenic->deleteInfo($where,true);
        
        $result && action_log('删除', '删除导游代付景点门票，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '导游代付景点门票删除成功'] : [RESULT_ERROR, $this->modelTmGuideScenic->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getTmGuideScenicInfo($where = [], $field =true)
    {
        return $this->modelTmGuideScenic->getInfo($where);
    }


}
