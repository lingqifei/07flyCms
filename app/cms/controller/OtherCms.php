<?php
/*
*
* cms.Archives  内容发布系统-频道模型
*
* =========================================================
* 零起飞网络 - 专注于网站建设服务和行业系统开发
* 以质量求生存，以服务谋发展，以信誉创品牌 !
* ----------------------------------------------
* @copyright	Copyright (C) 2017-2018 07FLY Network Technology Co,LTD (www.07FLY.com) All rights reserved.
* @license    For licensing, see LICENSE.html or http://www.07fly.xyz/crm/license
* @author ：kfrs <goodkfrs@QQ.com> 574249366
* @version ：1.0
* @link ：http://www.07fly.xyz
*/
namespace app\cms\controller;

use think\Db;
/**
* 友情链接管理-控制器
*/

class OtherCms extends CmsBase
{

	public $db;
	public $prefix;

	/**
	 * 构造方法
	 */
	public function __construct()
	{

		// 执行父类构造方法
		parent::__construct();
		$dbconfig=session('dbconfig');
		$this->prefix=$dbconfig['prefix'];
		$this->db = Db::connect([
			// 数据库类型
			'type'      => 'mysql',
			// 服务器地址
			'hostname'    => $dbconfig['hostname'],
			// 数据库名
			'database'    => $dbconfig['database'],
			// 用户名
			'username'    => $dbconfig['username'],
			// 密码
			'password'    => $dbconfig['password'],
			// 端口
			'hostport'    => $dbconfig['hostport'],
		]);
	}

	// 使用方法配置数据库连接
	public function config ()
	{

		$dbconfig=session('dbconfig');

		if(IS_POST){
			$db=$this->param;
			if(empty($db['hostname']) ||empty($db['database']) ||empty($db['username']) ||empty($db['password']) ||empty($db['hostport'])){
				$this->jump([RESULT_ERROR,'请输入完整数据库配置参数']);
			}
			session('dbconfig', $this->param);

			$dbconfig=session('dbconfig');

			$this->db = Db::connect([
				// 数据库类型
				'type'      => 'mysql',
				// 服务器地址
				'hostname'    => $dbconfig['hostname'],
				// 数据库名
				'database'    => $dbconfig['database'],
				// 用户名
				'username'    => $dbconfig['username'],
				// 密码
				'password'    => $dbconfig['password'],
				// 端口
				'hostport'    => $dbconfig['hostport'],
			]);

			if($this->db){
				$this->jump([RESULT_SUCCESS,'数据库链接测试成功~']);
			}else{
				$this->jump([RESULT_ERROR,'请输入完整数据库配置参数']);
			}

		}

		$this->assign('dbconfig', $dbconfig);

		return $this->fetch('config');
//		// 查询数据，，，，和使用系统的DB类方法略有差异
//		$data = $this->db-> table("dede_arctype") -> select();
//		dump($data);

	}

	public function add(){
		$data=$this->param;

		switch ($data['step']){
			case 1:
				$list = $this->db->name($this->prefix."arctype")
					->field('id,reid,typename,typedir,channeltype,tempindex,templist,temparticle,seotitle,keywords,description,content,sortrank,ispart')
					->select();
				Db::name('arctype')->where('id','>',0)->delete();
				foreach($list as $key=>$row){
					$intoData=[
						'id'=>$row['id'],
						'parent_id'=>$row['reid'],
						'typename'=>$row['typename'],
						'typedir'=>cut_str($row['typedir'],'/',-1),
						'channel_id'=>$row['channeltype'],
						'temp_index'=>cut_str($row['tempindex'],'/',-1),
						'temp_list'=>cut_str($row['templist'],'/',-1),
						'temp_article'=>cut_str($row['temparticle'],'/',-1),
						'seotitle'=>$row['seotitle'],
						'keywords'=>$row['keywords'],
						'description'=>$row['description'],
						'content'=>$row['content'],
						'sort'=>$row['sortrank'],
						'ispart'=>$row['ispart'],
					];
					Db::name('arctype')->insert($intoData);
				}
				$rtn=['code'=>1,'msg'=>'1、栏目表导入完成'];
				break;

			case 2:
				$list = $this->db->name($this->prefix."archives")
					->where('arcrank','<>','-2')
					->field('id,typeid,channel,title,shorttitle,writer,source,keywords,description,keywords,description,litpic')
					->select();
				Db::name('archives')->where('id','>',0)->delete();
				foreach($list as $key=>$row){
					$intoData=[
						'id'=>$row['id'],
						'channel_id'=>$row['channel'],
						'type_id'=>$row['typeid'],
						'title'=>$row['title'],
						'shorttitle'=>$row['shorttitle'],
						'keywords'=>$row['keywords'],
						'description'=>$row['description'],
						'writer'=>$row['writer'],
						'source'=>$row['source'],
						'litpic'=>$row['litpic'],
						'pubdate'=>format_time(),
					];
					Db::name('archives')->insert($intoData);
				}
				$rtn=['code'=>1,'msg'=>'2、文章主表导入完成'];
				break;
			case 3:
				$list = $this->db->name($this->prefix."addonarticle")
					->field('aid,typeid,body')
					->select();
				//Db::name('archives')->where('id','>',0)->delete();
				foreach($list as $key=>$row){
					Db::name('archives_article')->delete($row['aid']);
					$intoData=[
						'id'=>$row['aid'],
						'type_id'=>$row['typeid'],
						'body'=>$row['body']
					];
					Db::name('archives_article')->insert($intoData);
				}
				$rtn=['code'=>1,'msg'=>'3、附表导入完成'];
				break;
			default:

				$rtn=['code'=>1,'step'=>-1,'msg'=>'=>>全部导入完成'];
				return $rtn;
				break;
		}

		$rtn['step']=$data['step']+1;
		$rtn['url']=url('OtherCms/add');
		return $rtn;
	}


}
