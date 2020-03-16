<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Websiteor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\cms\logic;

use app\common\logic\TableField;

/**
 * 广告管理=》逻辑层
 */
class Website extends CmsBase
{
    /**
     * 广告列表
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int|mixed $paginate
     * @return
     */
    public function getWebsiteList($where = [], $field = true, $order = '', $paginate = DB_LIST_ROWS)
    {
        return $this->modelWebsite->getList($where, $field, $order, $paginate)->toArray();
    }

    /**
     * 广告编辑
     * @param array $data
     * @return array
     */
    public function setting($data = [])
    {

//        $validate_result = $this->validateWebsite->scene('setting')->check($data);
//
//        if (!$validate_result) {
//
//            return [RESULT_ERROR, $this->validateWebsite->getError()];
//        }

        foreach ($data as $key=>$value){

            $val=[
                'value'=>$value
            ];
            $map['name']=['=',$key];
            $result = $this->modelWebsite->setInfo($val,$map);
        }
        $url = url('show');

        $result && action_log('编辑', '编辑网站配置');

        return $result ? [RESULT_SUCCESS, '编辑成功', $url] : [RESULT_ERROR, $this->modelWebsite->getError()];
    }




    //得到系统配置参数
    public function getWebsiteInfoHtml($data){
        $where='';
        $string ='';
        if(!empty($data['groupid'])){
            $where['group']=['=',$data['groupid']];
        }
        $list=$this->modelWebsite->getList($where, true, 'id asc', false)->toArray();
        foreach($list as $key=>$row){
            $string .=$this->getCreateHtml($row);
        }
        return $string;
    }

    public function getCreateHtml($row){
        $htmltxt='';
        switch ( $row[ 'type' ] ) {
            case "varchar":
                $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "title" ] . '</label>
									<div class="col-sm-8">
										<input name="' . $row[ "name" ] . '" class="form-control" type="text" value="' . $row[ "value" ] . '"/>
										<span class="help-block m-b-none">' . $row[ 'describe' ] . '</span> 
									</div>
									<div class="col-sm-2">' . $row[ 'name' ] . '</div>
								</div>';
                break;
            case "textarea":
                $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "title" ] . '</label>
									<div class="col-sm-8">
										<textarea name="' . $row[ "name" ] . '" class="form-control" >' . $row[ "value" ] . '</textarea>
										<span class="help-block m-b-none">' . $row[ 'describe' ] . '</span> 
									</div>
									<div class="col-sm-2">' . $row[ 'name' ] . '</div>
								</div>';
                break;
            case "int":
                $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "title" ] . '</label>
									<div class="col-sm-8">
										<input name="' . $row[ "name" ] . '" class="form-control" type="text" value="' . $row[ "value" ] . '"/>
										<span class="help-block m-b-none">' . $row[ 'describe' ] . '</span> 
									</div>
									<div class="col-sm-2">' . $row[ 'name' ] . '</div>
								</div>';
                break;
            case "float":
                $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "title" ] . '</label>
									<div class="col-sm-8">
										<input name="' . $row[ "name" ] . '" class="form-control" type="text" value="' . $row[ "value" ] . '"/>
										<span class="help-block m-b-none">' . $row[ 'describe' ] . '</span> 
									</div>
									<div class="col-sm-2">' . $row[ 'name' ] . '</div>
								</div>';
                break;
            case "bool":
                $row[ "value" ] && $checked='checked="checked"';
                $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "title" ] . '</label>
									<div class="col-sm-8">';
                if($row['value']==1){
                    $htmltxt .= '<input name="' . $row[ "name" ] . '" type="radio" value="1" checked/>  开启';
                    $htmltxt .= '<input name="' . $row[ "name" ] . '" type="radio" value="0" />  关闭';
                }else{
                    $htmltxt .= '<input name="' . $row[ "name" ] . '" type="radio" value="1"/>  开启';
                    $htmltxt .= '<input name="' . $row[ "name" ] . '" type="radio" value="0" checked/>  关闭';
                }
                $htmltxt .='<span class="help-block m-b-none">' . $row[ 'describe' ] . '</span> 
									</div>
									<div class="col-sm-2">' . $row[ 'name' ] . '</div>
								</div>';
                break;
            default:
                $htmltxt .= '';
        }
        return $htmltxt;
    }


    //得到系统配置参数
    public function getWebsiteGroup(){
        $list=$this->modelWebsite->getGroup();
        return $list;
    }

}
