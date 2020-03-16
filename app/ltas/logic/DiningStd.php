<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * DiningStdor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 餐标逻辑
 */
class DiningStd extends LtasBase
{
    
    /**
     * 获取餐标列表
     */
    public function getDiningStdList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelDiningStd->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 餐标添加
     */
    public function diningStdAdd($data = [])
    {
        
        $validate_result = $this->validateDiningStd->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateDiningStd->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelDiningStd->setInfo($data);

        $result && action_log('新增', '新增餐标，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '餐标添加成功', $url] : [RESULT_ERROR, $this->modelDiningStd->getError()];
    }
    
    /**
     * 餐标编辑
     */
    public function diningStdEdit($data = [])
    {
        
        $validate_result = $this->validateDiningStd->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateDiningStd->getError()];
        }
        
        $url = url('diningStdList');
        
        $result = $this->modelDiningStd->setInfo($data);
        
        $result && action_log('编辑', '编辑餐标，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '餐标编辑成功', $url] : [RESULT_ERROR, $this->modelDiningStd->getError()];
    }
    
    /**
     * 餐标删除
     */
    public function diningStdDel($where = [])
    {
        
        $result = $this->modelDiningStd->deleteInfo($where,true);
        
        $result && action_log('删除', '删除餐标，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '餐标删除成功'] : [RESULT_ERROR, $this->modelDiningStd->getError()];
    }
    
    /**
     * 获取餐标信息
     */
    public function getDiningStdInfo($where = [], $field = true)
    {

        return $this->modelDiningStd->getInfo($where, $field);
    }

}
