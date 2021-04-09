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

class Book extends IndexBase
{

    public $bookid = '';
    public $chapid = '';

    /**
     * 广告位调用
     *
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function read($bookid = '')
    {
        $this->bookid = input("param.bookid", '0');
        $this->chapid = input("param.chapid", '0');
        if (!is_numeric($this->bookid) || strval(intval($this->bookid)) !== strval($this->bookid)) {
            abort(404, 'bookid 不存在');
        }
        $this->bookid = intval($this->bookid);


        if (empty($this->bookid)) {
            abort(404, 'aid 页面不存在');
            exit;
        } else {
            /**文档处理**/
            $bookinfo = $this->logicBook->getBookInfo(['id' => $this->bookid]);
            if (empty($bookinfo)) {
                abort(404, 'bookid 页面不存在');
                exit;
            }
            // 文章列表
            $chap_list = $this->logicBook->getBookChapList(['book_id' => $this->bookid]);

            //
            $chapmenu=$this->chap_list_tree_html($bookid);

            $map_chap=[];
            $this->chapid = intval($this->chapid);
            if(!empty($this->chapid)){
                $map_chap['id']=['=',$this->chapid];
            }
            $chapinfo=$this->logicBook->getBookChapInfo($map_chap);

            $pajx = input("param.pajx", '0');
            if(!empty($pajx)){
                return $chapinfo;
                exit;
            }

            $rtnArray = array(
                'chapmenu' => $chapmenu,
                'chap_list' => $chap_list,
                'bookinfo' => $bookinfo,
                'chapinfo' => $chapinfo,
            );

            /*模板文件*/
            if(empty($tpfile)){
                $tpfile = 'book.html';
            }
            $viewfile = !empty($tpfile) ? strtolower($tpfile) : $tpfile;
            $this->typeinfo = $rtnArray;
            $this->assign('fly', $this->typeinfo);
            return $this->fetch($viewfile);

        }
    }

    public function chap_list_tree_html($bookid){
        // 文章列表
        $chap_list = $this->logicBook->getBookChapList(['book_id' => $bookid],'id,book_id,pid,title','sort asc');
        $tree_list  = list2tree($chap_list,0,0,'id','pid','title');

        $html ="<ul>";
        $html .=$this->chap_list_tree_to($tree_list);
        $html .="</ul>";
        return $html;
    }

    public function chap_list_tree_to($list){
        $html ='';
        foreach ($list as $key=>$row){
            $url=url('index/book/read',array('bookid'=>$row['book_id'],'chapid'=>$row['id']));

            if(empty($row['nodes'])){
                $html .="<li>";
                $html .='<span><i></i></span><a href="'.$url.'">'.$row['title'].'</a>';
                $html .="</li>";
            }else{
                $html .='<li class="has_child">';
                $html .='<span><i class="icon-plus-sign"></i></span><a href="'.$url.'">'.$row['title'].'</a>';

                $html .="<ul style='display: none;'>";
                $html .=$this->chap_list_tree_to($row['nodes']);
                $html .="</ul>";

                $html .="</li>";
            }

        }

        return $html;
    }


}
