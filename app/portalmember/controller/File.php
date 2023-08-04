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

namespace app\portalmember\controller;

/**
 * 文件控制器
 */
class File extends MemberBaseAuth
{
    
    /**
     * 图片上传
     */
    public function pictureUpload()
    {
        
        $result = $this->logicFile->pictureUpload();

        return json($result);
    }

    /**
     * 图片上传
     */
    public function pictureUploadEditor()
    {

        //$result = $fileLogic->pictureUpload('imgFile');
        $result = $this->logicFile->pictureUpload('imgFile');

        if (false === $result) : return [RESULT_ERROR => DATA_NORMAL, RESULT_MESSAGE => '文件上传失败']; endif;

        $url = get_picture_url($result['id'],'portalmember');

        $data = [RESULT_ERROR => DATA_DISABLE, RESULT_URL => $url];

        return json($data);


    }
    
    /**
     * 文件上传
     */
    public function fileUpload()
    {
        
        $result = $this->logicFile->fileUpload();

        return json($result);
    }

    public function checkPictureExists() {
        $result = $this->logicFile->checkPictureExists($this->param);
        $return_result = [];
        if($result) {
            $return_result['code'] = 1;
            $return_result['msg'] = '该图片已存在';
            $return_result['data'] = $result;
        }else {
            $return_result['code'] = 0;
            $return_result['msg'] = '该图片不存在';
            $return_result['data'] = '';
        }
        return json($return_result);
    }

    public function checkFileExists() {
        $result = $this->logicFile->checkFileExists($this->param);
        $return_result = [];
        if($result) {
            $return_result['code'] = 1;
            $return_result['msg'] = '该文件已存在';
            $return_result['data'] = $result;
        }else {
            $return_result['code'] = 0;
            $return_result['msg'] = '该文件不存在';
            $return_result['data'] = '';
        }
        return json($return_result);
    }
}
