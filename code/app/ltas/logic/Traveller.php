<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Travelleror: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 游客逻辑
 */
class Traveller extends LtasBase
{
    
    /**
     * 获取游客列表
     */
    public function getTravellerList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list=$this->modelTraveller->getList($where, $field, $order, $paginate)->toArray();
        foreach ($list["data"] as $key=>$row){
            $list['data'][$key]['gender_text']=$this->modelTraveller->getGenderText($row['gender']);
        }
        return $list;
    }

    /**
     * 获取游客单条信息
     */
    public function getTravellerInfo($where = [], $field = true)
    {
        return $this->modelTraveller->getInfo($where, $field);
    }

    /**
     * 游客添加
     */
    public function travellerAdd($data = [])
    {
        
        $validate_result = $this->validateTraveller->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateTraveller->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelTraveller->setInfo($data);

        $result && action_log('新增', '游客游客，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '游客添加成功', $url] : [RESULT_ERROR, $this->modelTraveller->getError()];
    }
    
    /**
     * 游客编辑
     */
    public function travellerEdit($data = [])
    {
        
        $validate_result = $this->validateTraveller->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateTraveller->getError()];
        }
        
        $url = url('travellerList');
        
        $result = $this->modelTraveller->setInfo($data);
        
        $result && action_log('编辑', '编辑游客，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '游客编辑成功', $url] : [RESULT_ERROR, $this->modelTraveller->getError()];
    }
    
    /**
     * 游客删除
     */
    public function travellerDel($where = [])
    {
        
        $result = $this->modelTraveller->deleteInfo($where,true);
        
        $result && action_log('删除', '删除游客，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '游客删除成功'] : [RESULT_ERROR, $this->modelTraveller->getError()];
    }
    


}
