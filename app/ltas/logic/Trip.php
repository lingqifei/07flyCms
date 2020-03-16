<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Tripor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 权限组逻辑
 */
class Trip extends LtasBase
{
    
    /**
     * 获取行程列表
     */
    public function getTripList($where = [], $field = "a.*,l.name as line_name", $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        $this->modelTrip->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'line l', 'a.line_id = l.id'],
           // [SYS_DB_PREFIX . 'sys_org s', 'a.org_id = s.id'],
        ];
        $this->modelTrip->join = $join;

        $list=$this->modelTrip->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 获取行程单条信息
     */
    public function getTripInfo($where = [], $field = true)
    {
        return $this->modelTrip->getInfo($where, $field);
    }

    /**
     * 行程添加
     */
    public function tripAdd($data = [])
    {
        
        $validate_result = $this->validateTrip->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateTrip->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelTrip->setInfo($data);

        $result && action_log('新增', '行程名称，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '行程添加成功', $url] : [RESULT_ERROR, $this->modelTrip->getError()];
    }


    /**
     * 行程添加
     */
    public function tripAddMore($data = [])
    {

        $to_data=transform_array($data);


        foreach ($to_data as $key=>$row){
            $result = $this->modelTrip->setInfo($row);
            $result && action_log('新增', '行程名称，name：' . $row['name']);
        }

        $url = url('show');

        return $result ? [RESULT_SUCCESS, '行程添加成功', $url] : [RESULT_ERROR, $this->modelTrip->getError()];
    }


    /**
     * 行程编辑
     */
    public function tripEdit($data = [])
    {
        
        $validate_result = $this->validateTrip->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateTrip->getError()];
        }
        
        $url = url('tripList');
        
        $result = $this->modelTrip->setInfo($data);
        
        $result && action_log('编辑', '编辑行程，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '行程编辑成功', $url] : [RESULT_ERROR, $this->modelTrip->getError()];
    }
    
    /**
     * 行程删除
     */
    public function tripDel($where = [])
    {
        
        $result = $this->modelTrip->deleteInfo($where,true);
        
        $result && action_log('删除', '删除行程，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '行程删除成功'] : [RESULT_ERROR, $this->modelTrip->getError()];
    }
    


}
