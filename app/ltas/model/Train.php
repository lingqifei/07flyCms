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
 * 线路管理模型
 */
class Train extends LtasBase
{

    //线路类型
    public static function getTrainType($sType = '')
    {
        $trainTypeArr = array('1' => '火车', '2' => '飞机', '3' => '汽车');
        if (!empty($sType)) {
            if (!in_array($sType[0], array_keys($trainTypeArr))) {
                return $sType;
            } else {
                return $trainTypeArr[$sType[0]];
            }
        }
        return $trainTypeArr;
    }
}
