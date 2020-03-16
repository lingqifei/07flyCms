<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Stationor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 权限组逻辑
 */
class Station extends LtasBase
{
    
    /**
     * 获取站点列表
     */
    public function getStationList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list=$this->modelStation->getList($where, $field, $order, $paginate)->toArray();
        foreach ($list["data"] as $key=>$row){
            $list['data'][$key]['typename']=$this->modelStation->getStationType($row['type']);
        }
        return $list;
    }

    /**
     * 获取站点单条信息
     */
    public function getStationInfo($where = [], $field = true)
    {
        return $this->modelStation->getInfo($where, $field);
    }

    /**
     * 站点添加
     */
    public function stationAdd($data = [])
    {
        
        $validate_result = $this->validateStation->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateStation->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelStation->setInfo($data);

        $result && action_log('新增', '站点站点，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '站点添加成功', $url] : [RESULT_ERROR, $this->modelStation->getError()];
    }
    
    /**
 * 站点编辑
 */
    public function stationEdit($data = [])
    {

        $validate_result = $this->validateStation->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateStation->getError()];
        }

        $url = url('stationList');

        $result = $this->modelStation->setInfo($data);

        $result && action_log('编辑', '编辑站点，name：' . $data['name']);

        return $result ? [RESULT_SUCCESS, '站点编辑成功', $url] : [RESULT_ERROR, $this->modelStation->getError()];
    }
    
    /**
     * 站点删除
     */
    public function stationDel($where = [])
    {
        
        $result = $this->modelStation->deleteInfo($where,true);
        
        $result && action_log('删除', '删除站点，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '站点删除成功'] : [RESULT_ERROR, $this->modelStation->getError()];
    }
    


}
