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

class City extends IndexBase
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
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function index($data = [])
    {

        $province = input("param.province", '');
        $city = input("param.city", '');
        $county = input("param.county", '');

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
        $list_info=$this->logicInfo->getInfoListHot($where,'','a.update_time desc',100);

        $list_company=$this->logicMemberCompany->getMemberCompanyListHot($where,'','',10);
        $type_list_right=$this->logicInfoType->getInfoTypeSelfSonChannel($this->param);
        $rtnArray = array(
            'list_info' => $list_info,
            'list_company' => $list_company,
            'type_list_right' => $type_list_right,
        );


        /*模板文件*/
        if(empty($tpfile)){
            $tpfile = 'city_index.html';
        }
        $viewfile = !empty($tpfile) ? strtolower($tpfile) : $tpfile;
        $this->typeinfo = $rtnArray;
        $this->assign('fly', $this->typeinfo);
        return $this->fetch($viewfile);
    }

    /**
     * 热门城市函数
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function citys($data = [])
    {
        $list=$this->logicRegion->getInfoProvinceCityChannel(['id'=>'100000']);
        $rtnArray = array(
            'list' => $list,
        );
        /*模板文件*/
        if(empty($tpfile)){
            $tpfile = 'citys.html';
        }
        $viewfile = !empty($tpfile) ? strtolower($tpfile) : $tpfile;
        $this->typeinfo = $rtnArray;
        $this->assign('fly', $this->typeinfo);
        return $this->fetch($viewfile);
    }

}
