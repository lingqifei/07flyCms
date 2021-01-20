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

class InfoType extends IndexBase
{

    public $tid = '';
    public $type = '';


    /**
     * 构造方法
     */
    public function __construct()
    {
        // 执行父类构造方法
        parent::__construct();

    }

    /**
     * 分类课时显示
     *
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function index($data = [])
    {
        $tid = input("param.tid/s", '');
        $province = input("param.province", '');
        $city = input("param.city", '');
        $county = input("param.county", '');

        /*获取当前栏目ID以及模型ID*/
        $page_tmp = input('param.page/s', 0);
        
        if (empty($tid) || !is_numeric($page_tmp)) {
            abort(404, '页面不存在');
        }

        if (!is_numeric($tid) || strval(intval($tid)) !== strval($tid)) {
            $map = array('typedir' => $tid);
        } else {
            $map = array('id' => $tid);
        }
        //栏目信息
        $type = $this->logicInfoType->getInfoTypeInfo($map);
        if (empty($type)) {
            echo "tid错误~";
            abort(404, '页面不存在');
            exit;
        }
        if(!empty($province)){
            $province_id=$this->logicRegion->getRegionCityId($province);
            $where['province_id']=['in',$province_id];
        }

        if(!empty($city)){
            $city_id=$this->logicRegion->getRegionCityId($city,2);
            $where['city_id']=['in',$city_id];
        }

        if(!empty($county)){
            $county_id=$this->logicRegion->getRegionCityId($county,3,$city);
            $where['county_id']=['in',$county_id];
        }

        $tid=$type['id'];
        //栏目内容列表
        if(!empty($tid)){
            $son=$this->logicInfoType->getInfoTypeAllSon($tid);
            $son[]=$tid;
            $where['type_id2']=['in',$son];
        }

        $list=$this->logicInfo->getInfoList($where);
        $info_list_right=$this->logicInfo->getInfoListHot('','','',10);
        $company_list_right=$this->logicMemberCompany->getMemberCompanyListHot('','','',10);
        $type_list_right=$this->logicInfoType->getInfoTypeSelfSonChannel($this->param);

        $pages=$list->render('pre,next,pageno',DB_LIST_ROWS);
        $rtnArray = array(
            'type' => $type,
            'list' => $list,
            'info_list_right' => $info_list_right,
            'company_list_right' => $company_list_right,
            'type_list_right' => $type_list_right,
            'pages' => $pages,
        );

        /*模板文件*/

        //判断栏目类型0=列表，1=封面
        if ($type['ispart'] == 0) {
            $tpfile = $type['temp_list'];
        } else if ($type['ispart'] == 1) {
            $tpfile = $type['temp_index'];
        }
        if(empty($tpfile)){
            $tpfile = 'info_type.html';
        }
        $viewfile = !empty($tpfile) ? strtolower($tpfile) : $tpfile;

        $this->typeinfo = $rtnArray;
        $this->assign('fly', $this->typeinfo);
        return $this->fetch($viewfile);
    }


    /**
     * 分类公司显示
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function company($data = [])
    {
        $tid = input("param.tid/s", '');
        $province = input("param.province", '');
        $city = input("param.city", '');
        $county = input("param.county", '');

        //城市筛选
        if(!empty($province)){
            $province_id=$this->logicRegion->getRegionCityId($province);
            $where['province_id']=['in',$province_id];
        }
        if(!empty($city)){
            $city_id=$this->logicRegion->getRegionCityId($city,2);
            $where['city_id']=['in',$city_id];
        }
        if(!empty($county)){
            $county_id=$this->logicRegion->getRegionCityId($county,3,$city);
            $where['county_id']=['in',$county_id];
        }
        //右边显示
        $info_list_right=$this->logicInfo->getInfoListHot('','','',10);
        $company_list_right=$this->logicMemberCompany->getMemberCompanyListHot('','','',10);


        /*获取当前栏目ID以及模型ID*/
        if (!empty($tid)) {

            if (!is_numeric($tid) || strval(intval($tid)) !== strval($tid)) {
                $map = array('typedir' => $tid);
            } else {
                $map = array('id' => $tid);
            }

            //栏目信息
            $type = $this->logicInfoType->getInfoTypeInfo($map);

            if (empty($type)) {
                echo "tid错误~";
                abort(404, '页面不存在');
                exit;
            }
            $tid=$type['id'];
            //栏目内容列表
            if(!empty($tid)){
                $pid=$this->logicInfoType->getInfoTypeAllPid($tid);
                $pid[]=$tid;
                $where['category_id']=['in',$pid];
            }

        }

        $list=$this->logicMemberCompany->getMemberCompanyList($where);
        $pages=$list->render('pre,next,pageno',DB_LIST_ROWS);
        $rtnArray = array(
            'type' => $type,
            'list' => $list,
            'info_list_right' => $info_list_right,
            'company_list_right' => $company_list_right,
            'pages' => $pages,
        );

        /*模板文件*/

        //判断栏目类型0=列表，1=封面
        if ($type['ispart'] == 0) {
            $tpfile = $type['temp_list'];
        } else if ($type['ispart'] == 1) {
            $tpfile = $type['temp_index'];
        }
        if(empty($tpfile)){
            $tpfile = 'info_company.html';
        }
        $viewfile = !empty($tpfile) ? strtolower($tpfile) : $tpfile;

        $this->typeinfo = $rtnArray;
        $this->assign('fly', $this->typeinfo);
        return $this->fetch($viewfile);
    }

    /**
     * 后台调用方法，可以配合路由配置
     * @return mixed
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/11/2 0002
     */
    public function adminindex()
    {
        return $this->index($this->param);
    }

}
