<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Guideor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 导游逻辑
 */
class Guide extends LtasBase
{
    
    /**
     * 获取导游列表
     */
    public function getGuideList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list=$this->modelGuide->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     * 获取导游单条信息
     */
    public function getGuideInfo($where = [], $field = true)
    {
        return $this->modelGuide->getInfo($where, $field);
    }

    /**
     * 导游添加
     */
    public function guideAdd($data = [])
    {
        
        $validate_result = $this->validateGuide->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateGuide->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelGuide->setInfo($data);

        $result && action_log('新增', '导游导游，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '导游添加成功', $url] : [RESULT_ERROR, $this->modelGuide->getError()];
    }
    
    /**
     * 导游编辑
     */
    public function guideEdit($data = [])
    {
        
        $validate_result = $this->validateGuide->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateGuide->getError()];
        }
        
        $url = url('guideList');
        
        $result = $this->modelGuide->setInfo($data);
        
        $result && action_log('编辑', '编辑导游，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '导游编辑成功', $url] : [RESULT_ERROR, $this->modelGuide->getError()];
    }
    
    /**
     * 导游删除
     */
    public function guideDel($where = [])
    {
        
        $result = $this->modelGuide->deleteInfo($where,true);
        
        $result && action_log('删除', '删除导游，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '导游删除成功'] : [RESULT_ERROR, $this->modelGuide->getError()];
    }

    /**
     * 获取列信息
     */
    public function getGuideColumn($where = [], $field = '')
    {
        return $this->modelGuide->getColumn($where, $field);
    }

}
