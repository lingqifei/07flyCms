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

            $map_chap=[];
            $this->chapid = intval($this->chapid);
            if(!empty($this->chapid)){
                $map_chap['id']=['=',$this->chapid];
            }
            $chapinfo=$this->logicBook->getBookChapInfo($map_chap);

            $rtnArray = array(
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

    public function chap_list_tree(){
        $bookid = input("param.bookid", '0');
        // 文章列表
        $chap_list = $this->logicBook->getBookChapList(['book_id' => $bookid],'id,pid,title');
        return list2tree($chap_list,0,0,'id','pid','title');
    }


}
