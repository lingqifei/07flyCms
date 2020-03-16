<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Trainor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 权限组逻辑
 */
class Train extends LtasBase
{
    
    /**
     * 获取车次列表
     */
    public function getTrainList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list=$this->modelTrain->getList($where, $field, $order, $paginate)->toArray();
        foreach ($list["data"] as $key=>$row){
            $list['data'][$key]['typename']=$this->modelTrain->getTrainType($row['type']);
        }
        return $list;
    }

    /**
     * 获取车次单条信息
     */
    public function getTrainInfo($where = [], $field = true)
    {
        return $this->modelTrain->getInfo($where, $field);
    }

    /**
     * 车次添加
     */
    public function trainAdd($data = [])
    {
        
        $validate_result = $this->validateTrain->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateTrain->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelTrain->setInfo($data);

        $result && action_log('新增', '车次名称，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '车次添加成功', $url] : [RESULT_ERROR, $this->modelTrain->getError()];
    }
    
    /**
     * 车次编辑
     */
    public function trainEdit($data = [])
    {
        
        $validate_result = $this->validateTrain->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateTrain->getError()];
        }
        
        $url = url('trainList');
        
        $result = $this->modelTrain->setInfo($data);
        
        $result && action_log('编辑', '编辑车次，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '车次编辑成功', $url] : [RESULT_ERROR, $this->modelTrain->getError()];
    }
    
    /**
     * 车次删除
     */
    public function trainDel($where = [])
    {
        
        $result = $this->modelTrain->deleteInfo($where,true);
        
        $result && action_log('删除', '删除车次，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '车次删除成功'] : [RESULT_ERROR, $this->modelTrain->getError()];
    }
    


}
