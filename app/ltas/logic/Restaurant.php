<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Restaurantor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 餐厅逻辑
 */
class Restaurant extends LtasBase
{
    
    /**
     * 获取餐厅列表
     */
    public function getRestaurantList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelRestaurant->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 餐厅添加
     */
    public function restaurantAdd($data = [])
    {
        
        $validate_result = $this->validateRestaurant->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateRestaurant->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelRestaurant->setInfo($data);

        $result && action_log('新增', '新增餐厅，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '餐厅添加成功', $url] : [RESULT_ERROR, $this->modelRestaurant->getError()];
    }
    
    /**
     * 餐厅编辑
     */
    public function restaurantEdit($data = [])
    {
        
        $validate_result = $this->validateRestaurant->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateRestaurant->getError()];
        }
        
        $url = url('restaurantList');
        
        $result = $this->modelRestaurant->setInfo($data);
        
        $result && action_log('编辑', '编辑餐厅，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '餐厅编辑成功', $url] : [RESULT_ERROR, $this->modelRestaurant->getError()];
    }
    
    /**
     * 餐厅删除
     */
    public function restaurantDel($where = [])
    {
        
        $result = $this->modelRestaurant->deleteInfo($where,true);
        
        $result && action_log('删除', '删除餐厅，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '餐厅删除成功'] : [RESULT_ERROR, $this->modelRestaurant->getError()];
    }
    
    /**
     * 获取餐厅信息
     */
    public function getRestaurantInfo($where = [], $field = true)
    {

        return $this->modelRestaurant->getInfo($where, $field);
    }

}
