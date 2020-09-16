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
 * 栏目列表
 */
class TagList extends Base
{
    public $tid = '';

    //初始化
    protected function _initialize()
    {
        parent::_initialize();

        // 应用于栏目列表
        $this->tid = input("param.tid/s", '');

        /*应用于文档列表*/
        $aid = input('param.aid/d', 0);
        if ($aid > 0) {
            //引用文档逻辑
            $this->logicArchives = new \app\index\logic\Archives();
            $this->tid = $this->logicArchives->getArchivesFieldValue(['id' => $aid], 'type_id');
        }
        /*--end*/

        /*tid为目录名称的情况下*/
        $this->tid = $this->getTrueTypeid($this->tid);
        /*--end*/
    }

    /**
     *  arclist解析函数
     *
     * @param array $param 查询数据条件集合
     * @param int $row 调用行数
     * @param string $orderby 排列顺序
     * @param string $addfields 附加表字段，以逗号隔开
     * @param string $orderway 排序方式
     * @param string $pagesize 分页显示条数
     * @param string $thumb 是否开启缩略图
     * @return    array
     * @author lingqifei by 2020-2-20
     */
    public function getList($param = array(), $pagesize = 10, $orderby = '', $addfields = '', $orderway = '', $thumb = '')
    {
        $result = false;
        $param['channelid'] = ("" != $param['channelid'] && is_numeric($param['channelid'])) ? intval($param['channelid']) : '';
        $param['typeid'] = !empty($param['typeid']) ? $param['typeid'] : $this->tid;
        empty($orderway) && $orderway = 'desc';

        $pagesize = empty($pagesize) ? intval($pagesize) : intval($pagesize);

        if (!empty($param['channelid'])) {
            if (!preg_match('/^\d+([\d\,]*)$/i', $param['channelid'])) {
                echo '标签arclist报错：typeid属性值语法错误，请正确填写栏目ID。';
                return false;
            }
            // 过滤channelid中含有空值的栏目ID
            $channelidArr_tmp = explode(',', $param['channelid']);
            $channelidArr_tmp = array_unique($channelidArr_tmp);
            foreach ($channelidArr_tmp as $k => $v) {
                if (empty($v)) unset($channelidArr_tmp[$k]);
            }
            $param['channelid'] = implode(',', $channelidArr_tmp);
            // end
        }

        if (!empty($param['typeid'])) {
            if (!preg_match('/^\d+([\d\,]*)$/i', $param['typeid'])) {
                echo '标签arclist报错：typeid属性值语法错误，请正确填写栏目ID。';
                return false;
            }
            // 过滤typeid中含有空值的栏目ID
            $logicArctype = new \app\index\logic\Arctype();
            $typeidArr_tmp = explode(',', $param['typeid']);
            $typeidArr_tmp = array_unique($typeidArr_tmp);//过滤重复的
            $typeidArr_son = [];//得到子级栏目
            foreach ($typeidArr_tmp as $k => $v) {
                if (empty($v)) {
                    unset($typeidArr_tmp[$k]);
                }else{
                    $typeid_son=$logicArctype->getArctypeAllSon($v);
                    $typeid_son && $typeidArr_son=array_merge($typeidArr_son,$typeid_son);
                }
            }
            $typeidArr_tmp = array_merge($typeidArr_tmp,$typeidArr_son);
            $param['typeid'] = implode(',', $typeidArr_tmp);
            // end
        }

        $where = [];
        if (!empty($param['channelid'])) {
            $where['a.channelid'] = ['in', $param['channelid']];
        }

        if (!empty($param['typeid'])) {
            $where['a.type_id'] = ['in', $param['typeid']];
        }

        if (!empty($param['flag'])) {
            $reg_txt = str_replace(",", "|", $param['flag']);
            $where['a.flag'] = ['exp', Db::raw("REGEXP '(^|,)($reg_txt)(,|$)'")];
        }
       // $param = input('param.');

        //搜索查询
        if (strtolower(request()->controller()) == 'search') {
            $keywords = input('param.keywords/s', '');
            $typeid = input('param.typeid/s', '');
            $where['a.title|t.typename'] = ['like', "%{$keywords}%"];
            if($typeid){
                $where['a.type_id'] = ['in', $typeid];
            }
        }

        //标签查询
        if (strtolower(request()->controller()) == 'tags') {
            $tag = input('param.tag/s', '');
            $tagid = input('param.tagid/d', 0);
            if (!empty($tag)) {
                $tagidArr = M('tagindex')->where(array('tag'=>array('LIKE', "%{$tag}%")))->column('id', 'id');
                $aidArr = M('taglist')->field('aid')->where(array('tid'=>array('in', $tagidArr)))->column('aid', 'aid');
                $where['a.id'] = array('in', $aidArr);
            } elseif ($tagid > 0) {
                $aidArr = M('taglist')->field('aid')->where(array('tid'=>array('eq', $tagid)))->column('aid', 'aid');
                $where['a.id'] = array('in', $aidArr);
            }
        }

        /*获取文档列表*/
        $logicArchives = new \app\index\logic\Archives();
        $orderby = $logicArchives->getOrderBy($orderby, $orderway);

        //判断是否查询指定频道扩展关联表
        if (!empty($param['channelexttable'])) {

            $fg = input('param.fg/s', '');
            $fx = input('param.fx/s', '');
            $mj = input('param.mj/s', '');
            $level = input('param.level/s', '');
            $scfg = input('param.scfg/s', '');

            if(!empty($mj)){
                $where['e.house_area'] = ['=', $mj];
            }
            if(!empty($fg)){
                $where['e.house_style'] = ['=', $fg];
            }
            if(!empty($fx)){
                $where['e.house_type'] = ['=', $fx];
            }
            if(!empty($level)){
                $where['e.dg_level'] = ['=', $level];
            }
            if(!empty($scfg)){
               $where['e.dg_scfg']=['like','%'.$scfg.'%'];
            }
            $result = $logicArchives->getArchivesExtablePageList($where, 'a.*,e.*', $orderby, $pagesize,$param['channelexttable']);
        }else{
            $result = $logicArchives->getArchivesPageList($where, 'a.*', $orderby, $pagesize);
        }

        //对像转为数组
        //解析整理查询数据
        $list = is_object($result)?$result->ToArray():$result;
        $logicArctype = new \app\index\logic\Arctype();
        foreach ($list['data'] as &$row) {
            $typeinfo = $logicArctype->getArctypeInfo(['id' => $row['type_id']]);
            if ($typeinfo) {
                $row['typename'] = $typeinfo['typename'];
                $row['typeurl'] = $typeinfo['typeurl'];
            }
            $row['litpic'] = get_picture_url($row['litpic']);
            $row['arcurl'] = $logicArchives->getArchivesUrl($row);
        }

//        print_r($result);exit;
        $data = [
            "list" => $list['data'],
            "pages" => $result,
        ];
        return $data;
    }


}