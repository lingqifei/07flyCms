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

namespace app\index\taglib;

use think\Db;
use think\Request;


/**
 * 标签调用
 */
class TagTag extends Base
{

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->aid = input('param.aid/d', 0);
    }


    /**获取标签
     * @param int $getall
     * @param string $typeid
     * @param int $aid
     * @param int $row
     * @param string $sort
     * @param string $type
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Author: lingqifei created by at 2020/3/5 0005
     */
    public function getTag($getall = 0, $typeid = '', $aid = 0, $row = 30, $sort = 'new', $type = '')
    {
        $aid = !empty($aid) ? $aid : $this->aid;
        $getall = intval($getall);
        !empty($typeid) && $getall = 1;
        $result = false;
        $where = array();

        if ($getall == 0 && $aid > 0) {
            $where['aid'] = array('eq', $aid);
            $logicTaglist = new \app\index\logic\Taglist();
            $result = $logicTaglist->getTaglistTaglibList($where, '*, tid AS tagid', '',false,$row);

        } else {

            if (!empty($typeid)) {
                $typeid = $this->getTypeids($typeid, $type);
                $where['typeid'] = array('in', $typeid);
            }
            if($sort == 'rand') $orderby = 'rand() ';
            else if($sort == 'week') $orderby=' weekcc DESC ';
            else if($sort == 'month') $orderby=' monthcc DESC ';
            else if($sort == 'hot') $orderby=' count DESC ';
            else if($sort == 'total') $orderby=' total DESC ';
            else $orderby = 'create_time DESC  ';

            $logicTags = new \app\index\logic\Tags();
            $result = $logicTags->getTagsTaglibList($where, '*, id AS tagid', $orderby,false,$row);
        }

        foreach ($result as $key => $val) {
            $val['link'] = url('index/Tags/lists', array('tagid'=>$val['tagid']));
            $result[$key] = $val;
        }

        return $result;
    }

    private function getTypeids($typeid, $type = '')
    {
        $typeidArr = $typeid;
        if (!is_array($typeidArr)) {
            $typeidArr = explode(',', $typeid);
        }
        $typeids = [];

        $logicArctype = new \app\index\logic\Arctype();

        foreach($typeidArr as $key => $tid) {
            $result = [];
            switch ($type) {
                case 'son': // 下级栏目
                    $result = $logicArctype->getArctypeAllSon($tid);
                    break;

                case 'self': // 同级栏目
                    $result =  $logicArctype->getArctypeAllSelf($tid);
                    break;

                case 'top': // 顶级栏目
                    $result =  $logicArctype->getArctypeAllTop($tid);
                    break;

                default:
                    $result = [
                        [
                            'id'    => $tid,
                        ]
                    ];
                    break;
            }

            if (!empty($result)) {
                $typeids = array_merge($typeids, get_arr_column($result, 'id'));
            }
        }

        return $typeids;
    }
}