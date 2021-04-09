<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */
namespace app\admin\model;

/**
 * 升级模型
 */
class Upgrade extends AdminBase
{
//    private $server_url = "http://soft.s5.07fly.com";
    private $server_url='http://www.07fly.xyz';
    private $file;
    /**
     * 析构函数
     */
    function __construct()
    {
        $this->file = new \lqf\File();
        $this->ininServerUrl();
    }

    function  ininServerUrl(){
        $hostinfo=[
            "http://www.07fly.xyz",
            "http://soft.s5.07fly.com",
        ];
        foreach ($hostinfo as $oneurl){
            if(httpcode($oneurl)=='200'){
                $this->server_url=$oneurl;
                break;
            }
        }
    }

    /**
     * 获取可以升级的列表
     * Author: lingqifei created by at 2020/6/12 0012
     */
    public function getVersionList($version)
    {
        $url = $this->server_url . "/authorize/api.AuthVersion/get_version?ver=$version&sys=s1";
        $result = $this->getRemoteCotent($url);
        return $result;
    }

    /**获取远程版本详细信息
     * @param null $version
     * @return bool
     * Author: lingqifei created by at 2020/4/1 0001
     */
    public function getVersionInfo($version)
    {
        $url = $this->server_url . "/authorize/api.AuthVersion/get_version_info/?ver=$version&sys=s1";
        $result = $this->getRemoteCotent($url);
        return $result;
    }

    /**验证授权信息
     * @param null $version
     * @return bool
     * Author: lingqifei created by at 2020/4/1 0001
     */
    public function getAuthorizeInfo($domain,$syskey)
    {
        $url = $this->server_url . "/authorize/api.AuthDomain/client_check.html?u=$domain&k=$syskey";
        $result = $this->getRemoteCotent($url);
//        $result = json_decode($result, true);
        return $result;
    }

    /**验证平台信息
     * @param null $version
     * @return bool
     * Author: lingqifei created by at 2020/4/1 0001
     */
    public function getSignalInfo()
    {
        $url = $this->server_url . "/authorize/api.AuthDomain/client_check.html?u=07fly.xyz&k=07fly.xyz";
        if (httpcode($url)==200) {
            $rtn = array('code' => 1, 'msg' => '<span class="text-success">通信正常</span>');
        } else {
            $rtn = array('code' => 0, 'msg' => '<span class="text-danger">通信异常</span>');
        }
        return $rtn;
    }

    /**
     *获得远程请求远程地址内容
     * @param $url
     * @param array $postdata
     * @return Array()
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/12/25 0025
     */
    public function  getRemoteCotent($url, $postdata=[]){
        if (httpcode($url)==200) {
            $result=curl_post($url,$postdata);
            $result = json_decode($result, true);
        }else{
            $result =['code' => 0, 'msg' => '网络通信异常'];
        }
        return $result;
    }

}
