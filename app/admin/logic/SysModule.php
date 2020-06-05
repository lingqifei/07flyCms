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

namespace app\admin\logic;

/**
 * 模块逻辑
 */
class SysModule extends AdminBase
{


    private  $app_path='';
    private  $app_upload_path='';
    private  $app_pack_path='';
    private  $app_download_path='';

    public function __construct()
    {
        $this->initModuleDir();
    }

    /**
     * 初始模块目录
     * @param $module
     * @return string
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public function initModuleDir()
    {
        //模块目录
        $path = APP_PATH;
        !is_dir($path) && mkdir($path, 0755, true);
        $this->app_path=$path;

        //模块上传目录
        $path = PATH_UPLOAD.'app/upload/';
        !is_dir($path) && mkdir($path, 0755, true);
        $this->app_upload_path=$path;

        //模块打包目录
        $path = PATH_UPLOAD.'app/pack/';
        !is_dir($path) && mkdir($path, 0755, true);
        $this->app_pack_path=$path;

        //模块下载目录
        $path = PATH_UPLOAD.'app/download/';
        !is_dir($path) && mkdir($path, 0755, true);
        $this->app_download_path=$path;

    }


    /**
     * 初始模块目录
     * @param $module
     * @return string
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public function createModuleDir($module_name)
    {
        //模块目录
       $module_dir=$this->app_path.$module_name;

//        if (is_dir($module_dir)) {
//            return [RESULT_ERROR, '模块存在'];
//            exit;
//        }
        //创建模块目录
        !is_dir($module_dir) && mkdir($module_dir, 0755, true);

        //控制器
        $dir_list=['controller','logic','model','service','validate'];
        foreach ($dir_list as $dir_name){
            $action_dir=$module_dir.'/'.$dir_name;
            !is_dir($action_dir) && mkdir($action_dir, 0755, true);
            $this->mkModuleDirFile(['name'=>$module_name,'dirname'=>$dir_name]);
        }
        //模块模板
        $action_dir=$module_dir.'/view';
        !is_dir($action_dir) && mkdir($action_dir, 0755, true);

        return true;
    }

    /**
     * 模块列表
     */
    public function getSysModuleList($where = [], $field = true, $order = 'create_time desc', $paginate = DB_LIST_ROWS)
    {
        $list=$this->modelSysModule->getList($where, $field, $order, $paginate)->toArray();
        if(DB_LIST_ROWS===false) $list['data']=$list;
        foreach ($list['data'] as &$row){
            $row['status_arr']=$this->modelSysModule->status($row['status']);
        }
        return $list;

    }
    
    /**
     * 模块添加
     */
    public function sysModuleAdd($data = [])
    {
        
        $validate_result = $this->validateSysModule->scene('add')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSysModule->getError()];
        }

        //创建目录结构
        $this->createModuleDir($data['name']);
        unset($data['comm_file']);
        unset($data['module_dir']);
        $result = $this->modelSysModule->setInfo($data);
        $url = url('show');
        $result && action_log('新增', '新增模块：name' . $data['name']);
        return $result ? [RESULT_SUCCESS, '模块添加成功', $url] : [RESULT_ERROR, $this->modelSysModule->getError()];
    }
    
    /**
     *模块编辑
     */
    public function sysModuleEdit($data = [])
    {
        
        $validate_result = $this->validateSysModule->scene('edit')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSysModule->getError()];
        }

        $result = $this->modelSysModule->setInfo($data);
        $result && action_log('编辑', '编辑模块，name：' . $data['username']);
        $url = url('sysModule');
        return $result ? [RESULT_SUCCESS, '模块辑成功', $url] : [RESULT_ERROR, $this->modelSysModule->getError()];
    }
    
    /**
     *模块删除
     */
    public function sysModuleDel($where = [])
    {
        $this->sysModuleUninstall($where);
        $result = $this->modelSysModule->deleteInfo($where,true);
        $result && action_log('删除', '删除模块，where：' . http_build_query($where));
        return $result ? [RESULT_SUCCESS, '模块删除成功'] : [RESULT_ERROR, $this->modelSysModule->getError()];
    }
    
    /**
     * 模块信息
     */
    public function getSysModuleInfo($where = [], $field = true)
    {
        return $this->modelSysModule->getInfo($where, $field);
    }

    /**
     * 安装模块
     * @param array $data
     * @return array
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public  function  sysModuleInstall($data=[]){

        $this->initModuleDir();
        $info=$this->modelSysModule->getInfo(['id'=>$data['id']], true);
        $moduel_name=$info['name'];
        $pack_zip=$this->app_download_path.$moduel_name.'.zip';
        $zip_name=$moduel_name.'.zip';
        if (!file_exists($pack_zip)) {
            return [RESULT_ERROR, '模块安装包不存在'];
            exit;
        }

        $zip=new \lqf\Zip();
        $res=$zip->unzip($pack_zip, $this->app_path);
        if(!$res){
            return [RESULT_ERROR, '模块安装包解压失败'];
            exit;
        }

        // 2.1导入菜单栏目
        $this->importModuleMenu($moduel_name);

        $result=$this->modelSysModule->setFieldValue(['id'=>$data['id']],'status','1');
        return $result ? [RESULT_SUCCESS, '模块安装成功'] : [RESULT_ERROR, $this->modelSysModule->getError()];
    }

    /**
     * 卸载模块
     * @param array $data
     * @return array
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public  function  sysModuleUninstall($data=[]){

        $this->initModuleDir();
        $info=$this->modelSysModule->getInfo(['id'=>$data['id']], true);
        //备份模块
        $this->sysModulePack($info['name']);

        //删除模块
        $module_dir=$this->app_path.$info['name'];
        if (!is_dir($module_dir)) {
            return [RESULT_ERROR, '模块不存在'];
            exit;
        }
        $file=new \lqf\File();
        $res=$file->remove_dir($module_dir, true);
        if(!$res){
            return [RESULT_ERROR, '模块删除失败'];
            exit;
        }

        //删除栏目
        $this->delModuleMenu($info['name']);

        $result=$this->modelSysModule->setFieldValue(['id'=>$data['id']],'status','0');

        return $result ? [RESULT_SUCCESS, '模块卸载成功'] : [RESULT_ERROR, $this->modelSysModule->getError()];
    }

    /**
     * 备份模块
     * @param array $data
     * @return array
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public  function  sysModuleBackup($data=[]){

        $this->initModuleDir();
        $info=$this->modelSysModule->getInfo(['id'=>$data['id']], true);

        //备份模块
        $this->sysModulePack($info['name']);

        $this->sysModuleDown($info['name']);

    }

    /**
     * 模块打包
     * @param array $data
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public  function  sysModulePack($moduel_name){

        $this->initModuleDir();

        //1、把app目录复制到打包目录 下
        $module_dir=$this->app_path.$moduel_name;
        if (!is_dir($module_dir)) {
            return [RESULT_ERROR, '模块不存在'];
            exit;
        }

        //2、复制模块到打包目录
        $pack_dir=$this->app_pack_path.$moduel_name;
        $file=new \lqf\File();
        $result=$file->handle_dir($module_dir, $pack_dir, 'copy', true);
        if($result==false){
            return [RESULT_ERROR, '复制模块失败'];
            exit;
        }

        // 2.1导出菜单栏目
        $this->exportModuleMenu($moduel_name);
        //2.2生成模块信息
        $info=$this->modelSysModule->getInfo(['name'=>$moduel_name], true);
        $this->mkModuleInfo($info);

        //3、压缩包
        $pack_zip=$this->app_download_path.$moduel_name.'.zip';
        $zip_name=$moduel_name.'.zip';
        $zip=new \lqf\Zip();
        $result = $zip->zip($pack_zip, $pack_dir);
        if($result==false){
            return [RESULT_ERROR, '打包模块失败'];
            exit;
        }


        return $result ? [RESULT_SUCCESS , $pack_zip]  : [RESULT_ERROR, $this->modelSysModule->getError()];
    }


    /**
     * 模块下载
     * @param array $data
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public  function  sysModuleDown($moduel_name){
        $pack_zip=$this->app_download_path.$moduel_name.'.zip';
        $zip_name=$moduel_name.'.zip';
        if (!file_exists($pack_zip)) {
            return [RESULT_ERROR, '模块包不存在'];
            exit;
        }
        download($pack_zip,$zip_name);
    }


    /**
     * 模块下载
     * @param array $data
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public  function  sysModuleUpload($data=[]){

        $object_info = request()->file('filename');
        $object = $object_info->move($this->app_upload_path);
        $save_name = $object->getSaveName();
        $file_dir_name = substr($save_name, 0, strrpos($save_name, DS));
        $filename = $object->getFilename();
    }

    /**
     * 导出模块的栏目数据到打包文件
     * @param $modulename
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public function exportModuleMenu($modulename){
        $module_dir=$this->app_pack_path.$modulename;
        $menus=$this->logicSysMenu->sysMenuExport($modulename);
        $content= json_encode($menus, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        file_put_contents($module_dir.'/menu.php', $content);
    }

    /**
     * 模块栏目导入
     * @param $modulename
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public function importModuleMenu($modulename){
        $module_menu=$this->app_path.$modulename.'/menu.php';
        if(file_exists($module_menu)){
            $content= file_get_contents($module_menu);
            $result=isJson($content,true);
            if($result){
                $result=$this->logicSysMenu->sysMenuImport($result,$modulename);
            }else{
                return [RESULT_ERROR, '模块栏目格式有错'];
                exit;
            }
        }else{
            return [RESULT_ERROR, '模块栏目文件不存在'];
            exit;
        }


    }


    /**
     * 导出模块的栏目数据到打包文件
     * @param $modulename
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public function delModuleMenu($modulename){
        $this->logicSysMenu->sysMenuDel(['module'=>$modulename]);
    }


    /**
     * 生成模块信息文件
     * @author lingqifei <364666827@qq.com>
     */
    private function mkModuleInfo($data = [])
    {
        // 配置内容
        $config = <<<INFO
<?php
// +----------------------------------------------------------------------
// | 07FLY系统 [基于ThinkPHP5.0开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2021 http://www.07fly.top
// +----------------------------------------------------------------------
// | 07FLY承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 开发人生 <574249366@qq.com>
// +----------------------------------------------------------------------
/**
 * 模块基本信息
 */
return [
    // 模块名[必填]
    'name'        => '{$data['name']}',
    // 模块标题[必填]
    'title'       => '{$data['title']}',
    // 模块唯一标识[必填]，格式：模块名.[应用市场ID].module.[应用市场分支ID]
    'identifier'  => '{$data['identifier']}',
    // 主题模板[必填]，默认default
    'theme'        => 'default',
    // 模块图标[选填]
    'icon'        => '{$data['icon']}',
    // 模块简介[选填]
    'intro' => '{$data['intro']}',
    // 开发者[必填]
    'author'      => '{$data['author']}',
    // 开发者网址[选填]
    'author_url'  => '{$data['url']}',
    // 版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
    // 主版本号【位数变化：1-99】：当模块出现大更新或者很大的改动，比如整体架构发生变化。此版本号会变化。
    // 次版本号【位数变化：0-999】：当模块功能有新增或删除，此版本号会变化，如果仅仅是补充原有功能时，此版本号不变化。
    // 修订版本号【位数变化：0-999】：一般是 Bug 修复或是一些小的变动，功能上没有大的变化，修复一个严重的bug即发布一个修订版。
    'version'     => '{$data['version']}',
];
INFO;
        return file_put_contents($this->app_pack_path. $data['name'] . '/info.php', $config);
    }


    /**
     * 生成模块目录信息文件
     * @author lingqifei <364666827@qq.com>
     */
    private function mkModuleDirFile($data = [])
    {
        $name_lo=strtolower($data['name']);
        $name_uc=ucwords(strtolower($data['name']));

        $action_lo =strtolower($data['dirname']);
        $action_uc =ucwords(strtolower($data['dirname']));

        $file_desc=<<<INFO
/*
*
* cms.Archives  内容发布系统-频道模型
*
* =========================================================
* 零起飞网络 - 专注于网站建设服务和行业系统开发
* 以质量求生存，以服务谋发展，以信誉创品牌 !
* ----------------------------------------------
* @copyright	Copyright (C) 2017-2021 07FLY Network Technology Co,LTD.
* @license    For licensing, see LICENSE.html or http://www.07fly.top/crm/license
* @author ：kfrs <goodkfrs@QQ.com> 574249366
* @version ：1.0
* @link ：http://www.07fly.top
*/
INFO;

        // 配置控器
        $config = <<<INFO
<?php
{$file_desc}
namespace app\\{$name_lo}\\{$action_lo};
use app\common\\{$action_lo}\\{$action_uc}Base;

/**
 * 模块基类
 */
class {$name_uc}Base extends {$action_uc}Base

}
?>
INFO;

        $filename=$this->app_path.$name_lo . '/'.$action_lo.'/'.$name_uc."Base.php";
        return file_put_contents($filename, $config);

    }

}
