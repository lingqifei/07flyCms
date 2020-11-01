<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Channelor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\authorize\logic;
use \think\Db;
/**
 * 授权域名管理=》逻辑层
 */
class AuthVersion extends IndexBase
{

    /**授权域名信息
     * @param array $where
     * @param bool $field
     * @return
     */
    public function getAuthVersionInfo($where = [], $field = true)
    {
        $info= $this->modelAuthVersion->getInfo($where, $field);
        $root_url = get_file_root_path();
        $info['filename']=DOMAIN.$root_url . 'upgrade/'.$info['system'].'/'.$info['upgradefile'];
        return $info;
    }

    public function getAuthVersionList($where = [], $field = true, $order = 'pubdate desc', $paginate = 15)
    {

        $list= $this->modelAuthVersion->getList($where, $field, $order, $paginate)->toArray();
        $paginate===false && $list['data']=$list;
        foreach ($list['data'] as &$row){
            $root_url = get_file_root_path();
            $row['filename']=DOMAIN.$root_url . 'upgrade/'.$row['system'].'/'.$row['upgradefile'];
        }
        return $list;
    }

}
