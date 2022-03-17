<?php
/**
 * 零起飞07FLY-CMS
 * ============================================================================
 * 版权所有 2018-2028 成都零起飞科技有限公司，并保留所有权利。
 * 网站地址: http://www.07fly.com
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * Author: 开发人生 <goodkfrs@qq.com>
 * Date: 2021-01-01-3
 */

namespace app\index\controller;

use think\Controller;

class Sitemap extends IndexBase
{

    /**
     * 地图创建
     *
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function add()
    {

        $siteStr = '<?xml version="1.0" encoding="utf-8"?><urlset>';
        $arctypelist = $this->logicArctype->getAllList('', 'id,litpic,ispart,typedir', '', false);
        foreach ($arctypelist as &$row) {
            $typeurl = $this->logicArctype->getArctypeUrl($row);
            $siteStr .= '<url><loc>' . DOMAIN . $typeurl . '</loc></url>';
        }
        $arclist = $this->logicArchives->getAllList('', 'id,litpic,is_jump,type_id', '', false);
        foreach ($arclist as $row) {
            $arcurl = $this->logicArchives->getArchivesUrl($row);
            $siteStr .= '<url><loc>' . DOMAIN . $arcurl . '</loc></url>';
        }
        $siteStr .= '</urlset>';
        file_put_contents(PATH_PUBLIC . 'sitemap.xml', $siteStr);
        d($siteStr);

    }
}
