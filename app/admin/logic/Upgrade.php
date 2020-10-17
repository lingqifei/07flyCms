<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Agencyor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\admin\logic;

/**
 * 序列 逻辑
 */
class Upgrade extends AdminBase
{

    private $version = '20200808';//当前版本
    private $syskey_path = '';//注册码目录
    private $upgrade_path_back = '';//升级备份目录
    private $upgrade_path_down = '';//升级下载目录


    /**
     * 析构函数
     */
    function __construct()
    {
        $this->file = new \lqf\File();
        $this->zip = new \lqf\Zip();
        $this->initUpgradeDir();
    }


    /**
     * 初始模块目录
     * @param $module
     * @return string
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public function initUpgradeDir()
    {
        //授权码目录
        $path = ROOT_PATH . 'data/';
        !is_dir($path) && mkdir($path, 0755, true);
        $this->syskey_path = $path;

        //升级目录
        $path = PATH_UPLOAD . 'upgrade/' ;
        !is_dir($path) && mkdir($path, 0755, true);
        $this->upgrade_path = $path;

        //升级备份目录
        $path = PATH_UPLOAD . 'upgrade/back/';
        !is_dir($path) && mkdir($path, 0755, true);
        $this->upgrade_path_back = $path;

        //升级包下载目录
        $path = PATH_UPLOAD . 'upgrade/down/';
        !is_dir($path) && mkdir($path, 0755, true);
        $this->upgrade_path_down = $path;
    }

    /**
     * 返回当前授权码
     * @return string
     * Author: lingqifei created by at 2020/5/16 0016
     */
    public function getSysKey()
    {
        $syskey = $this->syskey_path . 'syskey';
        $txt = $this->file->read_file($syskey);
        if ($txt) {
            return $txt;
        } else {
            return '123456';
        }
    }

    /**
     * 返回当前版本号
     * @return string
     * Author: lingqifei created by at 2020/5/16 0016
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * 返回当前版本号
     * @return string
     * Author: lingqifei created by at 2020/5/16 0016
     */
    public function getVersionInfo()
    {
        $data = [
            'domain' => DOMAIN,
            'version' => $this->version,
        ];
        $list = $this->getUpgradeList();
        if ($list) {
            $data['upgrade'] = '<a class="label label-success ajax-goto" data-url="' . url("lists") . '">有新版,请点击升级</a>';
        } else {
            $data['upgrade'] = '';
        }
        return $data;
    }

    /**
     * 获取可以升级的列表
     * Author: lingqifei created by at 2020/6/12 0012
     */
    public function getUpgradeList()
    {
        //得到版本列表
        $info = $this->modelUpgrade->getVersionList($this->version);
        if (is_array($info['data'])) {
            $listdata = $info['data'];
            foreach ($listdata as &$row) {
                $status = $this->check_version_down($row['upgradefile']);
                if (!$status) {
                    $row['status'] = '<font color="red">文件没有下载</font>';
                    $row['operate'] = '<a href="javascript:void(0);" class="down" data-url="'.url('upgrade/down',array('version'=>$row['version'])).'">点击下载更新包文件HTTP</a>';
                } else {
                    $row['status'] = '<font color="green">文件已下载[文件完整]</font>';
                    $row['operate'] = '<a href="javascript:void(0);" class="execute" data-url="'.url('upgrade/execute',array('version'=>$row['version'])).'">点击升级更新包</a>';
                }
            }
        } else {
            $listdata = array();
        }
        return $listdata;
    }

    /**获取远程版本详细信息
     * @param null $version
     * @return bool
     * Author: lingqifei created by at 2020/4/1 0001
     */
    public function getUpgradeInfo($version)
    {
        return  $this->modelUpgrade->getVersionInfo($this->version);
    }


    /**下载升级文件
     * @param null $version
     * @return bool
     * Author: lingqifei created by at 2020/4/1 0001
     */
    public function getUpgradePack($version = null)
    {
        $packinfo=$this->modelUpgrade->getVersionInfo($version);
        $pakurl=!empty($packinfo)?$packinfo['filename']:'';
        $result = check_file_exists($pakurl);
        if ($result) {
            $finfo = $this->file->get_file_type("$pakurl");
            $res = $this->file->down_remote_file($pakurl, $this->upgrade_path_down, $finfo['basename'], $type = 1);
            if($res['error']==0){
                return [RESULT_SUCCESS, $res['save_path']];
            }
        } else {
            return [RESULT_ERROR, '下载升级文件不存在'];
        }
    }


    /**
     * 备份文件目录
     * @return array
     * @throws \Exception
     * Author: lingqifei created by at 2020/6/13 0013
     */
    public function getUpgradeBack()
    {
        $date = date("Ymd");
        $back_dir = array('app', 'addon', 'extend', 'vendor');
        $back_dir = array('addon');
        $pack_dir = $this->upgrade_path_back . $date;
        foreach ($back_dir as $dirname) {
            $result = $this->file->handle_dir(ROOT_PATH . $dirname, $pack_dir . '/' . $dirname, 'copy', true);
            if ($result == false) {
                return [RESULT_ERROR, '复制文件目录失败'];
                exit;
            }
        }
        $pack_zip = $this->upgrade_path_back . $date . '.zip';
        $result = $this->zip->zip($pack_zip, $pack_dir);
        if ($result == false) {
            return [RESULT_ERROR, '打包模块失败'];
            exit;
        } else {
            $this->file->remove_dir($pack_dir);
            return [RESULT_SUCCESS, $pack_zip];
        }

    }

    /**
     * 执行升级操作
     * @return array
     * @throws \Exception
     * Author: lingqifei created by at 2020/6/13 0013
     */
    public function getUpgradeExecute($data = [])
    {
        $pack_zip = $this->upgrade_path_down . $data['version'] . '.zip';
       if(check_file_exists($pack_zip)){
           $res=$this->zip->unzip($pack_zip, ROOT_PATH);
           if($res){
               return [RESULT_SUCCESS, '解压升级包成功'];
               exit;
           }else{
               return [RESULT_ERROR, '解压升级包失败'];
               exit;
           }
       }else{
           return [RESULT_ERROR, '升级包不存在'];
           exit;
       }
    }

    /**
     * 执行升级操作
     * @return array
     * @throws \Exception
     * Author: lingqifei created by at 2020/6/13 0013
     */
    public function getUpgradeExecuteSql($data = [])
    {
        return [RESULT_SUCCESS, '数据库升级成功了哟'];
    }

    /**
     * 执行升级操作
     * @return array
     * @throws \Exception
     * Author: lingqifei created by at 2020/6/13 0013
     */
    public function getUpgradeDel($data = [])
    {
        $pack_zip = $this->upgrade_path_down . $data['version'] . '.zip';
        if(check_file_exists($pack_zip)){
            $res=$this->file->unlink_file($pack_zip);
            if($res){
                return [RESULT_SUCCESS, '删除升级文件包成功'];
                exit;
            }else{
                return [RESULT_ERROR, '删除升级文件包失败'];
                exit;
            }
        }else{
            return [RESULT_ERROR, '升级文件包不存了'];
            exit;
        }
    }

    /**验证授权信息
     * @param null $version
     * @return bool
     * Author: lingqifei created by at 2020/4/1 0001
     */
    public function upgrade_auth_check()
    {
        $domain = $_SERVER['HTTP_HOST'];
        $syskey = $this->getSysKey();
        return $this->modelUpgrade->getAuthorizeInfo($domain,$syskey);
    }

    /**验证平台信息
     * @param null $version
     * @return bool
     * Author: lingqifei created by at 2020/4/1 0001
     */
    public function upgrade_signal_check()
    {
        return $this->modelUpgrade->getSignalInfo();
    }



    /**
     * 判断文件是否存在，支持本地及远程文件
     * @param String $file 文件路径
     * @return Boolean
     */
    public function check_version_down($version)
    {
        $downfile = $this->upgrade_path_down . $version;
        return check_file_exists($downfile);
    }

    /**
     * 授权注册
     * Author: lingqifei created by at 2020/6/6 0006
     */
    public function upgrade_auth_reg($data = [])
    {
        $filepath = $this->syskey_path . 'syskey';
        if (empty($data['syskey'])) {
            return [RESULT_ERROR, '授权码不能为空'];
        } else {
            file_put_contents($filepath, $data['syskey']);
            $res = $this->upgrade_auth_check();
            if ($res['code'] == '1') {
                return [RESULT_SUCCESS, '授权码注册成功'];
                $rtn = array('code' => 1, 'message' => '授权码注册成功');
            } else {
                return [RESULT_ERROR, $res['message']];
            }
        }
        return $rtn;
    }

}