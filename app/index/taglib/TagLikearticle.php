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
 * 相关文档列表
 */
class TagLikearticle extends Base
{
    public $tid = '';
    public $aid = '';

    //初始化
    protected function _initialize()
    {
        parent::_initialize();

        // 应用于栏目列表
        $this->tid = input("param.tid/s", '');

        /*应用于文档列表*/
        $this->aid = input('param.aid/d', 0);

    }

    /**
     *  arclist解析函数
     *
     * @author wengxianhu by 2018-4-20
     * @access    public
     * @param     array  $param  查询数据条件集合
     * @param     int  $row  调用行数
     * @param     string  $orderby  排列顺序
     * @param     string  $addfields  附加表字段，以逗号隔开
     * @param     string  $orderway  排序方式
     * @param     string  $tagid  标签id
     * @param     string  $tag  标签属性集合
     * @param     string  $pagesize  分页显示条数
     * @param     string  $thumb  是否开启缩略图
     * @return    array
     */
    public function getLikearticle($channelid = '', $typeid = '', $limit = 12)
    {
        $result = false;
        $where = [];
        if($channelid){
            $where['a.channelid']=['in',$channelid];
        }


        if (!empty($typeid)) {
            if (!preg_match('/^\d+([\d\,]*)$/i', $typeid)) {
                echo '标签arclist报错：typeid属性值语法错误，请正确填写栏目ID。';
                return false;
            }
            // 过滤typeid中含有空值的栏目ID
            $logicArctype = new \app\index\logic\Arctype();
            $typeidArr_tmp = explode(',', $typeid);
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
            $typeid = implode(',', $typeidArr_tmp);
            // end
        }
        
        if($typeid){
            $where['a.type_id']=['in',$typeid];
        }

        if($this->aid){
            $where['a.id']=['notin',$this->aid];
        }
//        $reg_txt=str_replace(",","|",$param['flag']);
//        $where['a.flag']=['exp',Db::raw("REGEXP '(^|,)($reg_txt)(,|$)'")];

        /*获取文档列表*/
        $logicArchives = new \app\index\logic\Archives();
        $info = $logicArchives->getArchivesInfo(['id'=>$this->aid]);
        if(empty($info['keywords'])){//通过插件分析关键字
            $keywords=getKeywords($info['title'],$info['body']);
            $reg_txt=implode('|',$keywords);
        }else{
            $reg_txt=preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)/" ,',' ,$info['keywords']);
            $reg_txt=str_replace(',','|',$reg_txt);
        }

        $where['a.keywords']=['exp',Db::raw("REGEXP '(^|,)($reg_txt)(,|$)'")];

        $result = $logicArchives->getArchiveslikeList($where, true, '',false,$limit);


        //获取文档栏目信息
        $logicArctype = new \app\index\logic\Arctype();

        foreach ($result['data'] as &$row){
            $typeinfo=$logicArctype->getArctypeInfo(['id'=>$row['type_id']]);
            if($typeinfo){
                $row['typename']=$typeinfo['typename'];
                $row['typeurl']=$typeinfo['typeurl'];
            }
        }

        return $result['data'];
    }


}