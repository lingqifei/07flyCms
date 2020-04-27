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
 * Date: 2020-02-12
 */
namespace app\cms\model;

/**
 * 栏目模型
 */
class Arctype extends CmsBase
{
    //扩展数据类型
    public function  ispart_text( $key = null ) {
        $data = array(
            "0" => '列表栏目',
            "1" => '频道封面',
            "2" => '外部连接'
        );
        return ( array_key_exists($key,$data) ) ? $data[ $key ] : $data;
    }

}
