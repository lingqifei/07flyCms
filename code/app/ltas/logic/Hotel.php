<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Hotelor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 酒店逻辑
 */
class Hotel extends LtasBase
{
    
    /**
     * 获取酒店列表
     */
    public function getHotelList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list=$this->modelHotel->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     * 获取酒店单条信息
     */
    public function getHotelInfo($where = [], $field = true)
    {
        return $this->modelHotel->getInfo($where, $field);
    }

    /**
     * 酒店添加
     */
    public function hotelAdd($data = [])
    {
        
        $validate_result = $this->validateHotel->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateHotel->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelHotel->setInfo($data);

        $result && action_log('新增', '酒店名称，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '酒店添加成功', $url] : [RESULT_ERROR, $this->modelHotel->getError()];
    }
    
    /**
     * 酒店编辑
     */
    public function hotelEdit($data = [])
    {
        
        $validate_result = $this->validateHotel->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateHotel->getError()];
        }
        
        $url = url('hotelList');
        
        $result = $this->modelHotel->setInfo($data);
        
        $result && action_log('编辑', '编辑酒店，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '酒店编辑成功', $url] : [RESULT_ERROR, $this->modelHotel->getError()];
    }
    
    /**
     * 酒店删除
     */
    public function hotelDel($where = [])
    {
        
        $result = $this->modelHotel->deleteInfo($where,true);
        
        $result && action_log('删除', '删除酒店，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '酒店删除成功'] : [RESULT_ERROR, $this->modelHotel->getError()];
    }
    


}
