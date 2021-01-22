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

class Info extends IndexBase{

    public $iid = '';
    public $type = '';

    /**
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function view($iid = ''){

        $this->iid = input("param.iid", '0');

        if (!is_numeric($this->iid) || strval(intval($this->iid)) !== strval($this->iid)) {
            abort(404,'iid页面不存在');
        }
        $this->iid = intval($this->iid);
        if(empty($this->iid)){
            abort(404, '页面不存在');
            exit;
        }else{
            /**文档处理**/
            $info=$this->logicInfo->getInfoInfo(['id'=>$this->iid]);
            if(empty($info)){
                abort(404, '页面不存在');
                exit;
            }
            $city=$this->logicRegion->getRegionCityName(['id'=>$info['city_id']]);
            if(!empty($city['city_name'])){
                $paramUrl=array('province'=>$city['province_code'],'city'=>$city['city_code'],);
                $url=url("index/City/index",$paramUrl);
                $this->assign('sys_city_web_url', $url);
                $this->assign('sys_city_web_title', $city['city_name'].'培训');
                $this->sys_city_name=$city['city_name'];
            }

            $type_list_right=$this->logicInfoType->getInfoTypeSelfSonChannel(['tid'=>$info['type_id']]);
//            print_r($info['company_id']);exit;
            //栏目处理
            $type=$this->logicInfoType->getInfoTypeInfo(['id'=>$info['type_id']]);
            //字段封装
            $rtnArray  = array(
                'type' => $type,
                'type_list_right' => $type_list_right,
                'field' => $info,
            );
            //更新点击
            $this->logicInfo->setInfoClick(['id'=>$this->iid]);
        }

        /*模板文件*/
        $tpfile='info_show.html';
        $this->typeinfo =$rtnArray ;
        $this->assign('fly', $this->typeinfo);
        return $this->fetch($tpfile);
    }


    /**
     * 分类课时显示
     *
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function lists($data = [])
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
        $where=[];
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

        $info_list_right=$this->logicInfo->getInfoList($where,"a.id,a.title,a.pubdate_time,a.litpic,a.city_id",'',false,10);
        $company_list_right=$this->logicMemberCompany->getMemberCompanyList($where,'a.name,a.litpic,a.intro,a.id,a.city_id','',false,10);
        $type_list_right=$this->logicInfoType->getInfoTypeSelfSonChannel($this->param);

        $tid=$type['id'];
        //栏目内容列表
        if(!empty($tid)){
            $son=$this->logicInfoType->getInfoTypeAllSon($tid);
            $son[]=$tid;
            $where['type_id2']=['in',$son];
        }
        $list=$this->logicInfo->getInfoList($where);

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
            $tpfile = 'info_list.html';
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
    public function adminindex(){
       return  $this->index($this->param);
    }

}
