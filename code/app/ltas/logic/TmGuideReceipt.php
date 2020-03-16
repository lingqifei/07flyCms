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
 * 导游代付回执单表逻辑
 */
class TmGuideReceipt extends LtasBase
{
    /**
     * 获取导游代付回执单列表
     */
    public function getTmGuideReceiptList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelTmGuideReceipt->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelTmGuideReceipt->stat($where,'sum','total_price');

        return $list;
    }

    /**
     * 导游代付回执单添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function tmGuideReceiptAdd($data = [])
    {

        $validate_result = $this->validateTmGuideReceipt->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmGuideReceipt->getError()];
        }

        $result = $this->modelTmGuideReceipt->setInfo($data);

        $result && action_log('新增', '新增导游代付回执单，金额：' . $data['total_price']);
        
        return $result ? [RESULT_SUCCESS, '导游代付回执单添加成功', ""] : [RESULT_ERROR, $this->modelTmGuideReceipt->getError()];
    }
    
    /**
     * 导游代付回执单编辑
     */
    public function tmGuideReceiptEdit($data = [])
    {

        $result = $this->modelTmGuideReceipt->setInfo($data);

        $result && action_log('编辑', '编辑导游代付回执单，金额：' .$data['total_price']);

        return $result ? [RESULT_SUCCESS, '导游代付回执单编辑成功', ""] : [RESULT_ERROR, $this->modelTmGuideReceipt->getError()];

    }
    
    /**
     * 导游代付回执单删除
     */
    public function tmGuideReceiptDel($where = [])
    {
        
        $result = $this->modelTmGuideReceipt->deleteInfo($where,true);
        
        $result && action_log('删除', '删除导游代付回执单，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '导游代付回执单删除成功'] : [RESULT_ERROR, $this->modelTmGuideReceipt->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getTmGuideReceiptInfo($where = [], $field =true)
    {
        return $this->modelTmGuideReceipt->getInfo($where);
    }


}
