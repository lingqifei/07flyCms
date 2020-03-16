<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Itemor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 项目逻辑
 */
class Item extends LtasBase
{
    
    /**
     * 获取项目列表
     */
    public function getItemList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelItem->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 项目添加
     */
    public function itemAdd($data = [])
    {
        
        $validate_result = $this->validateItem->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateItem->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelItem->setInfo($data);

        $result && action_log('新增', '新增项目，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '项目添加成功', $url] : [RESULT_ERROR, $this->modelItem->getError()];
    }
    
    /**
     * 项目编辑
     */
    public function itemEdit($data = [])
    {
        
        $validate_result = $this->validateItem->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateItem->getError()];
        }
        
        $url = url('itemList');
        
        $result = $this->modelItem->setInfo($data);
        
        $result && action_log('编辑', '编辑项目，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '项目编辑成功', $url] : [RESULT_ERROR, $this->modelItem->getError()];
    }
    
    /**
     * 项目删除
     */
    public function itemDel($where = [])
    {
        
        $result = $this->modelItem->deleteInfo($where,true);
        
        $result && action_log('删除', '删除项目，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '项目删除成功'] : [RESULT_ERROR, $this->modelItem->getError()];
    }
    
    /**
     * 获取项目信息
     */
    public function getItemInfo($where = [], $field = true)
    {

        return $this->modelItem->getInfo($where, $field);
    }

}
