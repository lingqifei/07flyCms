<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Driveror: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 权限组逻辑
 */
class Driver extends LtasBase
{
    
    /**
     * 获取司机列表
     */
    public function getDriverList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelDriver->getList($where, $field, $order, $paginate);
    }

    /**
     * 获取司机单条信息
     */
    public function getDriverInfo($where = [], $field = true)
    {
        return $this->modelDriver->getInfo($where, $field);
    }

    /**
     * 司机添加
     */
    public function driverAdd($data = [])
    {
        
        $validate_result = $this->validateDriver->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateDriver->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelDriver->setInfo($data);

        $result && action_log('新增', '新增司机，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '司机添加成功', $url] : [RESULT_ERROR, $this->modelDriver->getError()];
    }
    
    /**
     * 司机编辑
     */
    public function driverEdit($data = [])
    {
        
        $validate_result = $this->validateDriver->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateDriver->getError()];
        }
        
        $url = url('driverList');
        
        $result = $this->modelDriver->setInfo($data);
        
        $result && action_log('编辑', '编辑司机，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '司机编辑成功', $url] : [RESULT_ERROR, $this->modelDriver->getError()];
    }
    
    /**
     * 司机删除
     */
    public function driverDel($where = [])
    {
        
        $result = $this->modelDriver->deleteInfo($where,true);
        
        $result && action_log('删除', '删除司机，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '司机删除成功'] : [RESULT_ERROR, $this->modelDriver->getError()];
    }
    


}
