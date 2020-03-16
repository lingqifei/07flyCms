<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 购物店 逻辑
 */
class SkTeamStore extends LtasBase
{
    /**
     * 获取购物店列表
     */
    public function getSkTeamStoreList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelSkTeamStore->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelSkTeamStore->stat($where,'sum','total_price');

        return $list;
    }

    /**
     * 购物店添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skTeamStoreAdd($data = [])
    {

        $validate_result = $this->validateSkTeamStore->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkTeamStore->getError()];
        }

        $result = $this->modelSkTeamStore->setInfo($data);

        $result && action_log('新增', '新增购物店，金额：' . $data['total_price']);
        
        return $result ? [RESULT_SUCCESS, '购物店添加成功', ""] : [RESULT_ERROR, $this->modelSkTeamStore->getError()];
    }
    
    /**
     * 购物店编辑
     */
    public function skTeamStoreEdit($data = [])
    {

        $result = $this->modelSkTeamStore->setInfo($data);

        $result && action_log('编辑', '编辑购物店，金额：' .$data['total_price']);

        return $result ? [RESULT_SUCCESS, '购物店编辑成功', ""] : [RESULT_ERROR, $this->modelSkTeamStore->getError()];

    }
    
    /**
     * 购物店删除
     */
    public function skTeamStoreDel($where = [])
    {
        
        $result = $this->modelSkTeamStore->deleteInfo($where,true);
        
        $result && action_log('删除', '删除购物店，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '购物店删除成功'] : [RESULT_ERROR, $this->modelSkTeamStore->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getSkTeamStoreInfo($where = [], $field =true)
    {
        return $this->modelSkTeamStore->getInfo($where);
    }


}
