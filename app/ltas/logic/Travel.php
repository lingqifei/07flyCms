<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Travelor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 旅行社逻辑
 */
class Travel extends LtasBase
{
    
    /**
     * 获取权限分组列表
     */
    public function getTravelList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelTravel->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 权限组添加
     */
    public function travelAdd($data = [])
    {
        
        $validate_result = $this->validateTravel->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateTravel->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelTravel->setInfo($data);

        $result && action_log('新增', '新增旅行社，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '旅行社添加成功', $url] : [RESULT_ERROR, $this->modelTravel->getError()];
    }
    
    /**
     * 权限组编辑
     */
    public function travelEdit($data = [])
    {
        
        $validate_result = $this->validateTravel->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateTravel->getError()];
        }
        
        $url = url('travelList');
        
        $result = $this->modelTravel->setInfo($data);
        
        $result && action_log('编辑', '编辑旅行社，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '旅行社编辑成功', $url] : [RESULT_ERROR, $this->modelTravel->getError()];
    }
    
    /**
     * 权限组删除
     */
    public function travelDel($where = [])
    {
        
        $result = $this->modelTravel->deleteInfo($where,true);
        
        $result && action_log('删除', '删除旅行社，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '旅行社删除成功'] : [RESULT_ERROR, $this->modelTravel->getError()];
    }
    
    /**
     * 获取权限组信息
     */
    public function getTravelInfo($where = [], $field = true)
    {

        return $this->modelTravel->getInfo($where, $field);
    }

}
