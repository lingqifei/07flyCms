<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Agencyor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\admin\logic;

use think\Cookie;

/**
 * 序列 逻辑
 */
class Store extends AdminBase
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
        $path = PATH_DATA;
        !is_dir($path) && mkdir($path, 0755, true);
        $this->syskey_path = $path;

        //升级目录
        $path = PATH_DATA . 'store/' ;
        !is_dir($path) && mkdir($path, 0755, true);
        $this->upgrade_path = $path;

        //升级备份目录
        $path = PATH_DATA . 'store/back/';
        !is_dir($path) && mkdir($path, 0755, true);
        $this->upgrade_path_back = $path;

        //升级包下载目录
        $path = PATH_DATA . 'store/down/';
        !is_dir($path) && mkdir($path, 0755, true);
        $this->upgrade_path_down = $path;
    }


	/**
	 * 登录云
	 * @return string
	 * Author: lingqifei created by at 2020/5/16 0016
	 */
	public function cloudUserLogin($data=[])
	{
		$info = $this->modelStore->getCloudUserLogin($data);
		if($info['code']==0){
			$user=$info['data'];
			Cookie::set('stroe_user',$user,360000,'/');
			Cookie::set('username',$user['username'],360000,'/');
			Cookie::set('user_token',$user['user_token'],360000,'/');
			return [RESULT_SUCCESS, '登录成功'];
		}else{
			return [RESULT_ERROR, $info['msg']];
		}
	}

	/**
	 * 登出云
	 * @return string
	 * Author: lingqifei created by at 2020/5/16 0016
	 */
	public function cloudUserLoginout($data=[])
	{
		Cookie::clear('stroe_user');
		return [RESULT_SUCCESS, '登录成功'];
	}

	/**
	 * 云用户信息
	 * @return string
	 * Author: lingqifei created by at 2020/5/16 0016
	 */
	public function getCloudUserInfo($data=[])
	{
		$userinfo=[];
		if(Cookie::has('stroe_user')){
			$userinfo=Cookie::Get('stroe_user');
		}
		return $userinfo;
	}

	/**
	 * 获取应用插件的列表
	 * Author: lingqifei created by at 2020/6/12 0012
	 */
	public function getCloudStoreList()
	{
		//得到云服务应用插件列表
		$info = $this->modelStore->getCloudStoreList();

		$listdata = array();
		if($info['code']===0){
			if(!empty($info['data'])){
				$listdata = $info['data'];
				foreach ($listdata['data'] as &$row) {
					switch ($row['classify_id']){
						case '1'://模块

							$local=$this->modelSysModule->getInfo(['name'=>$row['name']],'id,version');
							if($local){
								$row['isinstall']=1;
								$row['isupdate']=($local['version']<$row['version'])?1:0;//判断最新版
							}else{
								$row['isinstall']=0;
								$row['isupdate']=0;
							}
							break;
						case '2'://插件
							$row['isinstall']=$this->modelAddon->stat(['name'=>$row['name']],'count');
							break;
						case '3'://服务
							break;
						case '4'://模板

							break;
					}
				}
			}
		}
		return $listdata;
	}


	/**
	 * 获取应用插件的基本信息
	 * 1、授权购买=》返回插件app_key
	 * 2、未购买生成购买订单=>返回插件 order_id
	 * @return string
	 * Author: lingqifei created by at 2020/5/16 0016
	 */
	public function getCloudAppInfo($data=[])
	{
		$info = $this->modelStore->getCloudAppInfo($data);
		if($info['code']===0){
			return $info['data'];
		}else{
			return [RESULT_ERROR, $info['msg']];
		}
	}

	/**
	 * 获取应用插件的安装信息
	 * 1、授权购买=》返回插件app_key
	 * 2、未购买生成购买订单=>返回插件 order_id
	 * @return string
	 * Author: lingqifei created by at 2020/5/16 0016
	 */
	public function getCloudAppOrderInfo($data=[])
	{
		$info = $this->modelStore->getCloudAppOrderInfo($data);
		if($info['code']===0){
			return $info['data'];
		}else{
			return [RESULT_ERROR, $info['msg']];
		}
	}


	/**
	 * 获取应用插件的安装信息
	 * 1、授权购买=》返回插件app_key
	 * 2、未购买生成购买订单=>返回插件 order_id
	 * @return string
	 * Author: lingqifei created by at 2020/5/16 0016
	 */
	public function getCloudAppDownInstall($data=[])
	{
		$result = $this->modelStore->getCloudAppDownFile($data);
		if (!isset($result['code']) || $result['code'] != 1) {
			return [RESULT_ERROR, $result['code']];
		}
		$filepath = $result['filepath'];
		$filename = $result['filename'];
		$dirpath = $result['dirpath'];

		$tmppath=$dirpath.rtrim($filename,'.zip').DS;
		if (file_exists($filepath)) {
			$zip=new \lqf\Zip();
			$res=$zip->unzip($filepath, $tmppath);
			if($res!=true){
				return [RESULT_ERROR, '模块包解压失败'];
			}

			//1、解压应用插件包
			$fp=new \lqf\Dir();
			$dirlist=$fp->listFile($tmppath);
			$app_path = !empty($dirlist) ? $dirlist[0]['pathname'] : '';
			$app_name = !empty($dirlist) ? $dirlist[0]['filename'] : '';
			if (empty($app_path)) {
				return [RESULT_ERROR, '应用插件压缩包缺少目录文件'];
			}

			//2、增加到本地模块
			$module_info_file=$app_path.'/data/info.php';

			if (file_exists($module_info_file)) {

				$moduel_info=include($module_info_file);

//				$validate_result = $this->validateSysModule->scene('add')->check($moduel_info);
//				if (!$validate_result) {
//					return [RESULT_ERROR, $this->validateSysModule->getError()];
//				}
//				$res = $this->modelSysModule->setInfo($moduel_info);

				$file = new \lqf\File();
				$result = $file->handle_dir($app_path, APP_PATH.$app_name, 'copy', true);
				if ($result == false) {
					return [RESULT_ERROR, '复制模块文件目录失败'];
					exit;
				}


				// 2.1导入菜单栏目
				$res = $this->logicSysModule->importModuleMenu($app_name);
				if ($res[0] == RESULT_ERROR) return $res;

				//导入数据SQL脚本
				$res = $this->logicSysModule->importModuleTableRestore(array('time' => time(), 'module' => $app_name));
				if ($res[0] == RESULT_ERROR) return $res;


				return $result ? [RESULT_SUCCESS, '模块下载本地解压部署成功'] : [RESULT_ERROR, $this->modelSysModule->getError()];
				exit;
			}else{
				return [RESULT_ERROR, '模块目录中模块信息文件info.php不存在'];
				exit;
			}

		}

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