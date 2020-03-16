<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Lineor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 权限组逻辑
 */
class Line extends LtasBase
{
    
    /**
     * 获取线路列表
     */
    public function getLineList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list=$this->modelLine->getList($where, $field, $order, $paginate)->toArray();
        if($paginate){
            foreach ($list["data"] as $key=>$row){
                $list['data'][$key]['typename']=$this->modelLine->getLineType($row['type']);
            }
        }
        return $list;
    }

    /**
     * 获取线路单条信息
     */
    public function getLineInfo($where = [], $field = true)
    {
        return $this->modelLine->getInfo($where, $field);
    }

    /**
     * 线路添加
     */
    public function lineAdd($data = [])
    {
        
        $validate_result = $this->validateLine->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateLine->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelLine->setInfo($data);

        $result && action_log('新增', '线路名称，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '线路添加成功', $url] : [RESULT_ERROR, $this->modelLine->getError()];
    }
    
    /**
     * 线路编辑
     */
    public function lineEdit($data = [])
    {
        
        $validate_result = $this->validateLine->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateLine->getError()];
        }
        
        $url = url('lineList');
        
        $result = $this->modelLine->setInfo($data);
        
        $result && action_log('编辑', '编辑线路，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '线路编辑成功', $url] : [RESULT_ERROR, $this->modelLine->getError()];
    }
    
    /**
     * 线路删除
     */
    public function lineDel($where = [])
    {
        
        $result = $this->modelLine->deleteInfo($where,true);
        
        $result && action_log('删除', '删除线路，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '线路删除成功'] : [RESULT_ERROR, $this->modelLine->getError()];
    }
    


}
