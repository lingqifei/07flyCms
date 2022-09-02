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
