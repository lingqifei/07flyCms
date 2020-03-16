<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Scenicor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 景点逻辑
 */
class Scenic extends LtasBase
{
    
    /**
     * 获取景点列表
     */
    public function getScenicList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelScenic->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 景点添加
     */
    public function scenicAdd($data = [])
    {
        
        $validate_result = $this->validateScenic->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateScenic->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelScenic->setInfo($data);

        $result && action_log('新增', '新增景点，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '景点添加成功', $url] : [RESULT_ERROR, $this->modelScenic->getError()];
    }
    
    /**
     * 景点编辑
     */
    public function scenicEdit($data = [])
    {
        
        $validate_result = $this->validateScenic->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateScenic->getError()];
        }
        
        $url = url('scenicList');
        
        $result = $this->modelScenic->setInfo($data);
        
        $result && action_log('编辑', '编辑景点，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '景点编辑成功', $url] : [RESULT_ERROR, $this->modelScenic->getError()];
    }
    
    /**
     * 景点删除
     */
    public function scenicDel($where = [])
    {
        
        $result = $this->modelScenic->deleteInfo($where,true);
        
        $result && action_log('删除', '删除景点，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '景点删除成功'] : [RESULT_ERROR, $this->modelScenic->getError()];
    }
    
    /**
     * 获取景点信息
     */
    public function getScenicInfo($where = [], $field = true)
    {

        return $this->modelScenic->getInfo($where, $field);
    }

}
