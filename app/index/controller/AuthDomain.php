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

class AuthDomain extends IndexBase{

    public $domain = '';
    public $syskey = '';


    /**
     * 验证域名是否授权
     * Author: lingqifei created by at 2020/6/5 0005
     */
    public function client_check(){
        if(empty($this->param['u']) ||empty($this->param['k'])){
            $rtn=['code'=>'0','message'=>'参数不全'];
        }else{
            $this->domain=$this->param['u'];
            $this->syskey=$this->param['k'];
            $where['syskey']=['=',$this->syskey];
            $where['domain']=['=',$this->domain];
            $info=$this->logicAuthDomain->getAuthDomainInfo($where);
            if($info){
                if($info['stop_date']<format_time()){
                    $rtn=['code'=>'0','message'=>'授权到期，到期时间'.$info['stop_date']];
                }else{
                    $rtn=['code'=>'1','message'=>'授权正常','data'=>$info];
                }
            }else{
                $rtn=['code'=>'0','message'=>'未查询到授权'];
            }
        }
        echo json_encode($rtn,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

}
