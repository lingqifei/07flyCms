<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Daysor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 权限组逻辑
 */
class Days extends LtasBase
{
    
    /**
     * 获取权限分组列表
     */
    public function getDaysList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelDays->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 权限组添加
     */
    public function daysAdd($data = [])
    {
        
        $validate_result = $this->validateDays->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateDays->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelDays->setInfo($data);

        $result && action_log('新增', '新增日期，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '日期添加成功', $url] : [RESULT_ERROR, $this->modelDays->getError()];
    }
    
    /**
     * 权限组编辑
     */
    public function daysEdit($data = [])
    {
        
        $validate_result = $this->validateDays->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateDays->getError()];
        }
        
        $url = url('daysList');
        
        $result = $this->modelDays->setInfo($data);
        
        $result && action_log('编辑', '编辑日期，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '日期编辑成功', $url] : [RESULT_ERROR, $this->modelDays->getError()];
    }
    
    /**
     * 权限组删除
     */
    public function daysDel($where = [])
    {
        
        $result = $this->modelDays->deleteInfo($where,true);
        
        $result && action_log('删除', '删除日期，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '日期删除成功'] : [RESULT_ERROR, $this->modelDays->getError()];
    }
    
    /**
     * 获取权限组信息
     */
    public function getDaysInfo($where = [], $field = true)
    {

        return $this->modelDays->getInfo($where, $field);
    }

}
