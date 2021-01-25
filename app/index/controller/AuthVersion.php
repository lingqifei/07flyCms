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
