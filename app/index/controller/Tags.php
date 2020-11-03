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
            $
        } elseif (intval($tagid) > 0) {
            $tagindexInfo = M('tagindex')->where([
                'id'   => $tagid,
                'lang'  => $this->home_lang,
            ])->find();
        }

        if (!empty($tagindexInfo)) {
            $tagid = $tagindexInfo['id'];
            $tag = $tagindexInfo['tag'];
            //更新浏览量和记录数
            $map = array(
                'tid'   => array('eq', $tagid),
                'arcrank'   => array('gt', -1),
                'lang'  => $this->home_lang,
            );
            $total = M('taglist')->where($map)
                ->count('tid');
            M('tagindex')->where([
                'id'    => $tagid,
                'lang'  => $this->home_lang,
            ])->inc('count')
                ->inc('weekcc')
                ->inc('monthcc')
                ->update(array('total'=>$total));

            $ntime = getTime();
            $oneday = 24 * 3600;

            //周统计
            if(ceil( ($ntime - $tagindexInfo['weekup'])/$oneday ) > 7)
            {
                M('tagindex')->where([
                    'id'    => $tagid,
                    'lang'  => $this->home_lang,
                ])->update(array('weekcc'=>0, 'weekup'=>$ntime));
            }

            //月统计
            if(ceil( ($ntime - $tagindexInfo['monthup'])/$oneday ) > 30)
            {
                M('tagindex')->where([
                    'id'    => $tagid,
                    'lang'  => $this->home_lang,
                ])->update(array('monthcc'=>0, 'monthup'=>$ntime));
            }
        }

        $field_data = array(
            'tag'   => $tag,
            'tagid'   => $tagid,
        );
        $eyou = array(
            'field'  => $field_data,
        );
        $this->eyou = array_merge($this->eyou, $eyou);
        $this->assign('eyou', $this->eyou);

        /*模板文件*/
        $viewfile = 'lists_tags';
        /*--end*/

        /*多语言内置模板文件名*/
        if (!empty($this->home_lang)) {
            $viewfilepath = TEMPLATE_PATH.$this->theme_style_path.DS.$viewfile."_{$this->home_lang}.".$this->view_suffix;
            if (file_exists($viewfilepath)) {
                $viewfile .= "_{$this->home_lang}";
            }
        }
        /*--end*/

        return $this->fetch(":{$viewfile}");
    }

}
