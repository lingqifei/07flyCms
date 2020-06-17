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

class AuthVersion extends IndexBase{

    public $sys = '';
    public $ver = '';


    /**
     * 查询升级文件
     * Author: lingqifei created by at 2020/6/5 0005
     */
    public function get_version(){
        if(empty($this->param['sys']) ||empty($this->param['ver'])){
            $rtn=['code'=>'0','message'=>'上传参数不全'];
        }else{
            $this->sys=$this->param['sys'];
            $this->ver=$this->param['ver'];
            $where['system']=['=',$this->sys];
            $where['version']=['>',$this->ver];
            $rtn=$this->logicAuthVersion->getAuthVersionList($where,'','pubdate desc',false);
        }
        echo json_encode($rtn,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    /**
     * 单个升级文件详细
     * Author: lingqifei created by at 2020/6/5 0005
     */
    public function get_version_info(){
        if(empty($this->param['sys']) ||empty($this->param['ver'])){
            $rtn=['code'=>'0','message'=>'上传参数不全'];
        }else{
            $this->sys=$this->param['sys'];
            $this->ver=$this->param['ver'];
            $where['system']=['=',$this->sys];
            $where['version']=['=',$this->ver];
            $rtn=$this->logicAuthVersion->getAuthVersionInfo($where);
        }
        echo json_encode($rtn,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
