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


namespace app\portalmember\logic;

use think\Image;

/**
 * 文件处理逻辑
 */
class File extends MemberBase
{

    /**
     * 获取图片URL路径
     */
    public function getPictureUrl($id = 0)
    {
        $info = $this->modelPicture->getInfo(['id' => $id], 'path,url');
        if (!empty($info['url'])) {
            return config('static_domain') . SYS_DS_PROS . $info['url'];
        }
        $root_url = get_file_root_path();
        if (!empty($info['path'])) {
            return $root_url . 'upload/picture/'.$info['path'];
        }
        return $root_url . 'static/module/admin/img/onimg.png';
    }

    /**
     * 获取图片URL路径
     */
    public function getPictureWebUrl($path = '')
    {
        $root_url = get_file_root_path();
        if (!empty($path)) {
            return $root_url . 'upload/picture/'.$path;
        }
        return $root_url . 'static/module/admin/img/onimg.png';
    }

    /**
     * 获取文件URL路径
     */
    public function getFileUrl($id = 0)
    {
        
        $info = $this->modelFile->getInfo(['id' => $id], 'path,url');
        
        if (!empty($info['url'])) {

            return config('static_domain') . SYS_DS_PROS . $info['url'];
        }

        if (!empty($info['path'])) {

            $root_url = get_file_root_path();

            return $root_url . 'upload/file/'.$info['path'];
        }

        return '暂无文件';
    }

    /**
     * 获取指定目录下的所有文件
     * @param null $path
     * @return array
     */
    public function getFileByPath($path = null)
    {
        $dirs = new \FilesystemIterator($path);
        $arr = [];
        foreach ($dirs as $v)
        {
            if($v->isdir())
            {
                $_arr = $this->getFileByPath($path ."/". $v->getFilename());
                $arr = array_merge($arr,$_arr);
            }else{
                $arr[] = $path . "/" . $v->getFilename();
            }
        }
        return $arr;
    }

    public function checkPictureExists($param = []) {
        return $this->modelPicture->where('sha1',$param['sha1'])->find();
    }

    public function checkFileExists($param = []) {
        return $this->modelFile->where('sha1',$param['sha1'])->find();
    }


}
