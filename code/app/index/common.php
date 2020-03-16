<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

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
    $tree = '';
    foreach ($list as $k => $v) {
        if ($v[$pidk] == $pId) { //父亲找到儿子
            $v['nodes']       =  list2tree($list, $v[$pk], $level + 1, $pk, $pidk,$name);
            $v['level']          = $level + 1;
            $v['treename'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . '|--' . $v[$name];
            $v['tags']           = $v['id'];
            $v['text']           = $v[$name];
            $tree[] = $v;
        }
    }
    return $tree;
}
