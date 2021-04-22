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

namespace app\admin\controller;

/**
 * 应用市市场控制器
 */
class Store extends AdminBase
{

	/**
	 * 基类初始化
	 */
	public function __construct()
	{
		// 执行父类构造方法
		parent::__construct();

//		$info=$this->logicStore->getCloudUserInfo();
//		$this->assign('userinfo', $info);
	}


	/**
	 * 显示备份例表
	 */
	public function user()
	{
		IS_POST && $this->jump($this->logicStore->cloudUserLogin($this->param));

		$info=$this->logicStore->getCloudUserInfo();
		$this->assign('info', $info);

		return $this->fetch('user');
	}

	/**
	 * 云会员登出
	 */
	public function userloginout()
	{
		$this->jump($this->logicStore->cloudUserLoginout($this->param));
	}

	/**
	 * 显示备份例表
	 */
	public function apps()
	{

		return $this->fetch('apps');
	}

	/**
	 * 显示备份例表
	 */
	public function apps_json()
	{
		return $this->logicStore->getCloudStoreList();
	}

	/**
	 * 注册信息
	 */
	public function reg()
	{
		$this->jump($this->logicUpgrade->upgrade_auth_reg($this->param));
	}

	/**
	 * 云应用安装
	 */
	public function install()
	{
		$userinfo=$this->logicStore->getCloudUserInfo();
		if(empty($userinfo)){
			return $this->user();
			exit;
		}else{
			$orderinfo=$this->logicStore->getCloudAppOrderInfo($this->param);
			$this->assign('userinfo', $userinfo);
			$this->assign('orderinfo', $orderinfo);
			return $this->fetch('install');
		}
	}

	/**
	 * 下载安装
	 */
	public function down_install()
	{
		$this->jump($this->logicStore->getCloudAppDownInstall($this->param));
	}

	/**
	 * 升级执行
	 */
	public function exec_install()
	{

		if(empty($this->param['version']) || empty($this->param['step'])){
			$rtn=['code'=>0,'msg'=>'选择需要升级的参数'];
		}else{
			switch ($this->param['step']){
				case '1':
					$res= $this->logicUpgrade->getUpgradeBack();
					if($res[0]==RESULT_SUCCESS){
						$rtn['code']='1';
						$rtn['step']='2';
						$rtn['msg']='执行第一步：备份程序成功，备份文件为：'.$res[1];
						$rtn['title']='数据升级，开始执行升级程序，请不要关闭浏览器...';
					}else{
						$rtn=['code'=>0,'msg'=>$res[1]];
					}
					break;
				case '2':
					$res= $this->logicUpgrade->getUpgradeExecute($this->param);
					if($res[0]==RESULT_SUCCESS){
						$rtn['code']='1';
						$rtn['step']='3';
						$rtn['msg']='执行第二步：解压程序成功，程序已经覆盖完成！';
						$rtn['title']='开始执行升级数据库，请不要关闭浏览器...';
					}else{
						$rtn=['code'=>0,'msg'=>$res[1]];
					}
					break;
				case '3':
					$res= $this->logicUpgrade->getUpgradeExecuteSql($this->param);
					if($res[0]==RESULT_SUCCESS){
						$rtn['code']='1';
						$rtn['step']='4';
						$rtn['msg']='执行第三步：数据库升级完成！'.$res[1];
						$rtn['title']='开始清除缓存数据，请不要关闭浏览器...';
					}else{
						$rtn=['code'=>0,'msg'=>$res[1]];
					}
					break;
				case '4':
					$res= $this->logicUpgrade->getUpgradeDel($this->param);
					if($res[0]==RESULT_SUCCESS){
						$rtn['code']='1';
						$rtn['step']='-1';
						$rtn['msg']='执行第四步：缓存数据清除完成，升级完成！'.$res[1];
						$rtn['title']='请不要关闭浏览器...';
					}else{
						$rtn=['code'=>0,'msg'=>$res[1]];
					}
					break;

			}
			$rtn['url']=url('upgrade/execute',array('version'=>$this->param['version']));
			$rtn['version']=$this->param['version'];
		}
		return $rtn;
	}
}
