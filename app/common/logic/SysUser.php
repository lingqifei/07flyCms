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

namespace app\common\logic;

/**
 * 用户逻辑
 */
class SysUser extends LogicBase
{
    /**
     * 获取列表
     */
    public function getUserList($where = [], $field = true, $order = 'id desc', $paginate = DB_LIST_ROWS)
    {
        return $this->modelSysUser->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 获取单个信息
     */
    public function getUserInfo($where = [], $field = true)
    {
        return $this->modelSysUser->getInfo($where, $field);
    }

    /**
     * 获取列信息
     */
    public function getUserValue($where = [], $field = '')
    {
        return $this->modelSysUser->getValue($where, $field);
    }

}
