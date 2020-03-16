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
namespace app\ltas\model;

/**
 * 办事处管理模型
 */
class Agency extends LtasBase
{
    //自定义统计求和
    public function skorder()
    {
        return $this->hasMany('SkOrder','agency_id','id');
    }

    //自定义统计求和
    public function tmorder()
    {
        return $this->hasMany('TmOrder','agency_id','id');
    }

}
