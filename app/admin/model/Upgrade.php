<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
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

    private $server_url = "http://www.07fly.top";
    private $file;
    /**
     * 析构函数
     */
    function __construct()
    {
        $this->file = new \lqf\File();
    }

    /**
     * 获取可以升级的列表
     * Author: lingqifei created by at 2020/6/12 0012
     */
    public function getVersionList($version)
    {
        $url = $this->server_url . "/index/AuthVersion/get_version?ver=$version&sys=v2";
        $info = $this->file->read_file($url);//得到服务器返回包的地址
        $info = json_decode($info, true);
        return $info;
    }

    /**获取远程版本详细信息
     * @param null $version
     * @return bool
     * Author: lingqifei created by at 2020/4/1 0001
     */
    public function getVersionInfo($version)
    {
        $url = $this->server_url . "/index/AuthVersion/get_version_info/?ver=$version&sys=v2";
        $info = $this->file->read_file($url);
        $info = json_decode($info, true);
        return $info;
    }


    /**验证授权信息
     * @param null $version
     * @return bool
     * Author: lingqifei created by at 2020/4/1 0001
     */
    public function getAuthorizeInfo($domain,$syskey)
    {
        $url = $this->server_url . "/index/AuthDomain/client_check.html?u=$domain&k=$syskey";
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        return $result;
    }

    /**验证平台信息
     * @param null $version
     * @return bool
     * Author: lingqifei created by at 2020/4/1 0001
     */
    public function getSignalInfo()
    {
        $url = $this->server_url . "/index/AuthDomain/client_check.html?u=07fly.top&k=07fly.top";
        if (check_file_exists($url)) {
            $rtn = array('code' => 1, 'msg' => '<span class="text-success">通信正常</span>');
        } else {
            $rtn = array('code' => 0, 'msg' => '<span class="text-danger">通信异常</span>');
        }
        return $rtn;
    }

}
