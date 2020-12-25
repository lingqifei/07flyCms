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

namespace addon\editor\logic;

use app\common\logic\File as LogicFile;

/**
 * 编辑器插件上传逻辑
 */
class Upload
{

    /**
     * 图片上传
     */
    public function pictureUpload()
    {
        
        $fileLogic = get_sington_object('fileLogic', LogicFile::class);
        
        $result = $fileLogic->pictureUpload('imgFile');
        
        if (false === $result) : return [RESULT_ERROR => DATA_NORMAL, RESULT_MESSAGE => '文件上传失败']; endif;
        
        $url = get_picture_url($result['id']);
        
        return [RESULT_ERROR => DATA_DISABLE, RESULT_URL => $url];
    }
}
