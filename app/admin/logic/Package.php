<?php
// +----------------------------------------------------------------------
// | 07FLYCRM [基于ThinkPHP5.0开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2021 http://www.07fly.xyz
// +----------------------------------------------------------------------
// | Professional because of focus  Persevering because of happiness
// +----------------------------------------------------------------------
// | Author: 开发人生 <goodkfrs@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\logic;

/**
 * 模块逻辑
 */
class Package extends AdminBase
{


    private $app_path = '';
    private $app_upload_path = '';
    private $app_pack_path = '';
    private $app_download_path = '';

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
        //应用模块目录
        $path = PATH_APP;
        !is_dir($path) && mkdir($path, 0755, true);
        $this->app_path = $path;

        //模块包上传目录
        $path = PATH_DATA . 'app' . DS . 'upload' . DS;
        !is_dir($path) && mkdir($path, 0755, true);
        $this->app_upload_path = $path;

        //模块打包目录
        $path = PATH_DATA . 'app/zippack/';
        !is_dir($path) && mkdir($path, 0755, true);
        $this->app_pack_path = $path;

        //模块下载目录
        $path = PATH_DATA . 'app' . DS . 'download' . DS;
        !is_dir($path) && mkdir($path, 0755, true);
        $this->app_download_path = $path;

    }

    /**
     * 模块打包=>创建zip
     * @param array $data
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public function createPack($data = [])
    {
        //保存数据
        $this->modelSysModule->setInfo($data);

        //查询模块信息
        $info = $this->modelSysModule->getInfo(['id' => $data['id']], true);
        if (empty($info)) {
            return [RESULT_ERROR, '本模块数据不存在'];
            exit;
        }
        $module_name = $info['name'];

        //1、把app目录复制到打包目录下
        $module_dir = $this->app_path . $module_name;
        if (!is_dir($module_dir)) {
            return [RESULT_ERROR, '模块文件目录不存在'];
            exit;
        }
        $pack_dir = $this->app_pack_path . $module_name . DS;
        $file = new \lqf\File();
        $result = $file->handle_dir($module_dir, $pack_dir, 'copy', true);
        if ($result == false) {
            return [RESULT_ERROR, '复制模块文件目录失败'];
            exit;
        }

        //1、加密文件
        if (!empty($data['is_encode'])) {
            $pack_list_file = $file->list_dir_info($pack_dir . 'logic' . DS, true, 'php');
            foreach ($pack_list_file as $key => $filename) {
                $this->encodeFile($filename);
            }
        }
        // 2.1导出左侧菜单信息到配置文件 mneu.php
        $this->modelSysModule->exportModuleMenu($module_name, $pack_dir);

        //2.2生成模块信息到 info.php
        $this->modelSysModule->mkModuleInfo($info, $pack_dir);

        //2.3导出模块数据表 table-1.sql 文件
        $res = $this->modelSysModule->exportModuleTable(array('module_dir' => $pack_dir . 'data' . DS, 'tables' => $info['tables'], 'sqlfilename' => 'backup'));
        if ($res[0] == RESULT_ERROR) return $res;


        //需要移出的文件
        $encode_files = [
            'data/backup-1.sql',
        ];
        foreach ($encode_files as $key => $filename) {
           // $file->unlink_file($pack_dir . $filename);
        }

        //3、压缩包zip文件
        $pack_zip = $this->app_pack_path . $module_name . '.zip';
        $result = $this->createZip($pack_zip, $pack_dir);
        if ($result == false) {
            return [RESULT_ERROR, '打包模块失败'];
            exit;
        }
        //4、复制文件成对应的版本包
        $file->handle_file($pack_zip,$this->app_pack_path . $module_name .'-V'. $info['version'] .'.zip');

        $datalog = '打包文件:' . $pack_zip;
        return $result ? [RESULT_SUCCESS, '备份操作成功', '', $datalog] : [RESULT_ERROR, $this->modelSysModule->getError()];
    }

    /**
     * 模块打包=>创建框架升级文件
     * @param array $data
     * Author: lingqifei created by at 2020/6/4 0004
     */
    public function createSys($data = [])
    {

        ini_set('max_execution_time', '0');
        if (empty($data['version'])) {
            return [RESULT_ERROR, '升级包版本号和升级文件不能为空'];
            exit;
        }

        $version = $data['version'];

        //导出栏目菜单
        $this->modelSysModule->exportModuleMenu('admin', $this->app_path . 'admin' . DS);

        //写入版本号
        file_put_contents($this->app_path . 'admin' . DS . 'data' . DS . 'version', $data['version']);

        //创建打包的临时目录
        $version_dir = $this->app_pack_path .'acsys/'. $version . DS;
        !is_dir($version_dir) && mkdir($version_dir, 0755, true);

        //2、升级包=移动需要打包的文件
        $handle_list = str2arr($data['upgrade_list'], "\r\n");

        //2、安装包=移动需要打包的文件
        $handle_list_intsll = str2arr($data['install_list'], "\r\n");

        //判断是升级包，安装包
        if (!empty($data['install'])) {
            $handle_list = array_merge($handle_list, $handle_list_intsll);
        }

        $logtxt = '【移动文件】<hr>';
        //循环升级包移动文件
        $file = new \lqf\File();
        foreach ($handle_list as $filepath) {
            $source = ROOT_PATH . $filepath;//源位置
            $topath = $version_dir . $filepath;//目的位置
            $logtxt .= '<br>' . $source . '=>' . $topath;
            if (!file_exists($source)) {
                return [RESULT_ERROR, '打包模文件不存在'];
                exit;
            }
            if (!is_dir($source)) {
                $file->handle_file($source, $topath, 'copy', true);
            } else {
                $file->handle_dir($source, $topath, 'copy', true);
            }
        }
        //系统框架中移出打包功能
        $encode_files = [
            'app/admin/controller/Package.php',
            'app/admin/logic/Package.php',
        ];

        $logtxt .= '<hr>【移出打包文件】<hr>';
        foreach ($encode_files as $key => $filename) {
            //$filename=str_replace('/',DS,$version_dir .$filename);
            //$this->encodeFile( $filename);
            $file->unlink_file($version_dir . $filename);
            $logtxt .= '<br>' . $filename;
        }

        //3、压缩包zip文件
        $pack_zip = $this->app_pack_path .'acsys/'. $version . '.zip';
        $result = $this->createZip($pack_zip, $version_dir);
        if ($result == false) {
            return [RESULT_ERROR, '打包模块失败'];
            exit;
        }
        $logtxt .= '<hr>打包生成文件：' . $pack_zip;
        return $result ? [RESULT_SUCCESS, '打包成功', '', $logtxt] : [RESULT_ERROR, '打包失败'];
    }

    /**
     * 返回随机字符串
     * @param string $length
     * @return string
     * Author: 开发人生 goodkfrs@qq.com
     * Date: 2022/3/21 0021 15:24
     */
    public function randAbc($length = "")
    {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        return str_shuffle($str);
    }

    /**
     * 加密文件
     * @param $filename
     * Author: 开发人生 goodkfrs@qq.com
     * Date: 2022/3/21 0021 15:24
     */
    public function encodeFile($filename)
    {
        $vstr = file_get_contents($filename);//要加密的文件
        $T_k1 = $this->randAbc();//随机密匙1
        $T_k2 = $this->randAbc();//随机密匙2
        $v1 = base64_encode($vstr);
        $c = strtr($v1, $T_k1, $T_k2);//根据密匙替换对应字符。
        $c = $T_k1 . $T_k2 . $c;
        $q1 = "O00O0O";
        $q2 = "O0O000";
        $q3 = "O0OO00";
        $q4 = "OO0O00";
        $q5 = "OO0000";
        $q6 = "O00OO0";
        $s = '$' . $q6 . '=urldecode("%6E1%7A%62%2F%6D%615%5C%76%740%6928%2D%70%78%75%71%79%2A6%6C%72%6B%64%679%5F%65%68%63%73%77%6F4%2B%6637%6A");$' . $q1 . '=$' . $q6 . '{3}.$' . $q6 . '{6}.$' . $q6 . '{33}.$' . $q6 . '{30};$' . $q3 . '=$' . $q6 . '{33}.$' . $q6 . '{10}.$' . $q6 . '{24}.$' . $q6 . '{10}.$' . $q6 . '{24};$' . $q4 . '=$' . $q3 . '{0}.$' . $q6 . '{18}.$' . $q6 . '{3}.$' . $q3 . '{0}.$' . $q3 . '{1}.$' . $q6 . '{24};$' . $q5 . '=$' . $q6 . '{7}.$' . $q6 . '{13};$' . $q1 . '.=$' . $q6 . '{22}.$' . $q6 . '{36}.$' . $q6 . '{29}.$' . $q6 . '{26}.$' . $q6 . '{30}.$' . $q6 . '{32}.$' . $q6 . '{35}.$' . $q6 . '{26}.$' . $q6 . '{30};eval($' . $q1 . '("' . base64_encode('$' . $q2 . '="' . $c . '";eval(\'?>\'.$' . $q1 . '($' . $q3 . '($' . $q4 . '($' . $q2 . ',$' . $q5 . '*2),$' . $q4 . '($' . $q2 . ',$' . $q5 . ',$' . $q5 . '),$' . $q4 . '($' . $q2 . ',0,$' . $q5 . '))));') . '"));';
        $s = '<?php ' . $s . '?>';
        file_put_contents($filename, $s);
    }

    /**
     * 定义一个压缩CSS文件的函数
     * @param $css_back
     * @param $css_new
     * Author: 开发人生 goodkfrs@qq.com
     * Date: 2022/3/22 0022 16:54
     */
    public function cssCompress($css_back, $css_new = '')
    {
        //得到未压缩CSS文件的内容
        $str = @file_get_contents($css_back);
        //删除注释
        $str = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $str);
        //删除空格，换行等
        $str = str_replace(array("\r", "\n", "\t", '  ', '    ', '    '), '', $str);

        //将压缩后的css文件内容写入到一个新的CSS文件中
        if ($css_new) {
            @file_put_contents($css_new, $str);
        } else {
            @file_put_contents($css_back, $str);
        }
    }

    /**
     * 压缩包
     * @param $pack_zip 包名
     * @param $pack_dir 压缩的目录
     * @return bool
     * @throws \Exception
     * Author: 开发人生 goodkfrs@qq.com
     * Date: 2022/3/21 0021 16:39
     */
    public function createZip($pack_zip, $pack_dir)
    {
        $zip = new \lqf\Zip();
        $pack_dir = rtrim($pack_dir, DS);//打包前去掉最一个斜杠，防止ubuntu下解压目录多一个斜杠
        $result = $zip->zip($pack_zip, $pack_dir);
        if ($result == false) {
            return false;
        } else {
            return true;
        }
    }
}
