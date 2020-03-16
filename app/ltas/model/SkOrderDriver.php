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
 * 散客司机安排模型
 */
class SkOrderDriver extends LtasBase
{

    //站点类型
    public static function getTypeText($sType = '')
    {
        $typeArr = array('1' => '接', '2' => '送', '3' => '跟团');
        if (!empty($sType)) {
            if (!in_array($sType, array_keys($typeArr))) {
                return $sType;
            } else {
                return $typeArr[$sType];
            }
        }
        return $typeArr;
    }


    //站点类型
    public function  getTypeAttr($value,$data)
    {
        $type = ['1' => '接', '2' => '送', '3' => '跟团'];
        return $type[$data['type']];
    }
    public function getTypeTextAttr($value,$data)
    {
        $type = ['1' => '接', '2' => '送', '3' => '跟团'];
        return $type[$data['type']];
    }

}