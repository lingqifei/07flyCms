<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * ItemReceiptor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 回执选项逻辑
 */
class ItemReceipt extends LtasBase
{
    
    /**
     * 获取回执选项列表
     */
    public function getItemReceiptList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelItemReceipt->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 回执选项添加
     */
    public function ItemReceiptAdd($data = [])
    {
        
        $validate_result = $this->validateItemReceipt->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateItemReceipt->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelItemReceipt->setInfo($data);

        $result && action_log('新增', '新增回执选项，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '回执选项添加成功', $url] : [RESULT_ERROR, $this->modelItemReceipt->getError()];
    }
    
    /**
     * 回执选项编辑
     */
    public function ItemReceiptEdit($data = [])
    {
        
        $validate_result = $this->validateItemReceipt->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateItemReceipt->getError()];
        }
        
        $url = url('ItemReceiptList');
        
        $result = $this->modelItemReceipt->setInfo($data);
        
        $result && action_log('编辑', '编辑回执选项，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '回执选项编辑成功', $url] : [RESULT_ERROR, $this->modelItemReceipt->getError()];
    }
    
    /**
     * 回执选项删除
     */
    public function ItemReceiptDel($where = [])
    {
        
        $result = $this->modelItemReceipt->deleteInfo($where,true);
        
        $result && action_log('删除', '删除回执选项，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '回执选项删除成功'] : [RESULT_ERROR, $this->modelItemReceipt->getError()];
    }
    
    /**
     * 获取回执选项信息
     */
    public function getItemReceiptInfo($where = [], $field = true)
    {

        return $this->modelItemReceipt->getInfo($where, $field);
    }

}
