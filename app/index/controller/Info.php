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
        $tpfile='show_info.html';
        $this->typeinfo =$rtnArray ;
        $this->assign('fly', $this->typeinfo);
        return $this->fetch($tpfile);
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
