<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * ItemTravelor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 交社选项逻辑
 */
class ItemTravel extends LtasBase
{
    
    /**
     * 获取交社选项列表
     */
    public function getItemTravelList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelItemTravel->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 交社选项添加
     */
    public function itemTravelAdd($data = [])
    {
        
        $validate_result = $this->validateItemTravel->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateItemTravel->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelItemTravel->setInfo($data);

        $result && action_log('新增', '新增交社选项，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '交社选项添加成功', $url] : [RESULT_ERROR, $this->modelItemTravel->getError()];
    }
    
    /**
     * 交社选项编辑
     */
    public function itemTravelEdit($data = [])
    {
        
        $validate_result = $this->validateItemTravel->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateItemTravel->getError()];
        }
        
        $url = url('itemTravelList');
        
        $result = $this->modelItemTravel->setInfo($data);
        
        $result && action_log('编辑', '编辑交社选项，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '交社选项编辑成功', $url] : [RESULT_ERROR, $this->modelItemTravel->getError()];
    }
    
    /**
     * 交社选项删除
     */
    public function itemTravelDel($where = [])
    {
        
        $result = $this->modelItemTravel->deleteInfo($where,true);
        
        $result && action_log('删除', '删除交社选项，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '交社选项删除成功'] : [RESULT_ERROR, $this->modelItemTravel->getError()];
    }
    
    /**
     * 获取交社选项信息
     */
    public function getItemTravelInfo($where = [], $field = true)
    {

        return $this->modelItemTravel->getInfo($where, $field);
    }

}
