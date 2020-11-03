<?php
/*
ThinkPHP5.0+整合百度编辑器Ueditor1.4.3.3+
作者：符工@邦明
日期：西元二零一七年元月五日
网址：http://bbs.df81.com/
不要怀念哥，哥只是个搬运工
*/

namespace app\index\controller;

use think\Controller;

class Tags extends IndexBase{

    public $tid = '';
    public $type = '';

    /**
     * 标签主页
     */
    public function index()
    {
        /*获取当前页面URL*/
        $result['pageurl'] = $this->request->url(true);
        /*--end*/
        $rtnArray = array(
            'field' => $result,
        );

        /*模板文件*/
        $viewfile = 'tags_index.html';
        /*--end*/
        $this->assign('fly', $rtnArray);
        return $this->fetch($viewfile);
    }

    /**
     * 标签列表
     */
    public function lists()
    {
        $param = $this->param;

        $tagid = isset($param['tagid']) ? $param['tagid'] : '';
        $tag = isset($param['tag']) ? trim($param['tag']) : '';
        if (!empty($tag)) {
            $map['tag']=['=',$tag];
            $tagindexInfo = $this->logicTag->getTagindexInfo($map);
        } elseif (intval($tagid) > 0) {
            $map['id']=['=',$tagid];
            $tagindexInfo = $this->logicTag->getTagindexInfo($map);
        }

        if (!empty($tagindexInfo)) {
            $tagid = $tagindexInfo['id'];
            $tag = $tagindexInfo['tag'];
            //更新统计、点击数据
            $this->logicTag->getTagindexUpdate($tagindexInfo);
        }

        $field_data = array(
            'tag'   => $tag,
            'tagid'   => $tagid,
        );
        $rtnArray = array(
            'field'  => $field_data,
        );

        $this->assign('fly', $rtnArray);

        /*模板文件*/
        $viewfile = 'tags_list.html';
        return $this->fetch($viewfile);
    }

}
