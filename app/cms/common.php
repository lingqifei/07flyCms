<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

use app\admin\logic\Log as LogicLog;

/**
 * 记录行为日志
 */
function action_log($name = '', $describe = '')
{

    $logLogic = get_sington_object('logLogic', LogicLog::class);
    
    $logLogic->logAdd($name, $describe);
}


//得到把列表数据=》数形参数
 function list2tree($list, $pId = 0, $level = 0, $pk='id', $pidk = 'pid',$name='name')
{
    $tree = [];
    foreach ($list as $k => $v) {
        if ($v[$pidk] == $pId) { //父亲找到儿子
            $v['nodes']       =  list2tree($list, $v[$pk], $level + 1, $pk, $pidk,$name);
            $v['level']          = $level + 1;
            $v['treename'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . '|--' . $v[$name];
            $v['tags']           = $v['id'];
            $v['text']           = $v[$name].'(ID:'.$v['id'].')';
            $tree[] = $v;
        }
    }
    return $tree;
}
