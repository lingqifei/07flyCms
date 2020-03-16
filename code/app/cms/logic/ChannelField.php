<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Channelor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\cms\logic;

use app\common\logic\TableField;

/**
 * 模型字段逻辑
 */
class ChannelField extends CmsBase
{
    /**
     * 析构函数
     */
    function  __construct() {
        $this->tablefield = new TableField();
    }
    /**
     * 模型字段处列表
     */
    public function getChannelFieldList($where = [], $field = true, $order = '', $paginate = DB_LIST_ROWS)
    {

        $list=$this->modelChannelField->getList($where, $field, $order, $paginate)->toArray();
        if($paginate===false) $list['data']=$list;
        foreach ($list['data'] as &$row){
            $row['field_type_text']=$this->modelChannelField->field_type_text($row['field_type']);
        }
        return $list;
    }

    /**
     * 模型字段处列表
     */
    public function getExtTableFieldList($main_table=null,$ext_table=null)
    {
        if($main_table && $ext_table){
            $where['main_table']=['=',$main_table];
            $where['ext_table']=['=',$ext_table];
            return $this->modelChannelField->getList($where, "", '', false)->toArray();
        }
    }

    /**
     * 模型字段处列表
     */
    public function getChannelTypeList()
    {

       return  $this->modelChannelField->field_type_text();
    }

    /**
     * 模型管理处信息
     */
    public function getChannelFieldInfo($where = [], $field = true)
    {

        return $this->modelChannelField->getInfo($where, $field);
    }

    /**
     * 模型添加
     */
    public function channelFieldAdd($data = [])
    {

        $validate_result = $this->validateChannelField->scene('add')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateChannelField->getError()];
        }

        //为表添加字段
        $rtn=$this->tablefield->add_field($data['ext_table'],$data['field_name'],$data['field_type'],$data['maxlength'],$data['default_value'], $data['desc']);
        if($rtn[0]==RESULT_ERROR)  return $rtn;

        $result = $this->modelChannelField->setInfo($data);
        $url = url('show');
        $result && action_log('新增', '新增模块字段，table：' . $data['ext_table'].',field:'. $data['field_name']);

        return $result ? [RESULT_SUCCESS, '添加成功', $url] : [RESULT_ERROR, $this->modelChannelField->getError()];
    }
    /**
     * 模型编辑
     */
    public function channelFieldEdit($data = [])
    {

        $validate_result = $this->validateChannelField->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateChannelField->getError()];
        }

        //为表添加字段
        $rtn=$this->tablefield->modify_field($data['ext_table'],$data['field_name'],$data['field_type'],$data['maxlength'],$data['default_value'], $data['desc']);
        if($rtn[0]==RESULT_ERROR)  return $rtn;

        $url = url('show');

        $result = $this->modelChannelField->setInfo($data);

        $result && action_log('编辑', '编辑模型字段，name：' .$data['ext_table'].',field:'. $data['field_name']);

        return $result ? [RESULT_SUCCESS, '编辑模型字段', $url] : [RESULT_ERROR, $this->modelChannelField->getError()];
    }
    /**
     * 字段删除
     */
    public function channelFieldDel($where = [])
    {

        $list=$this->getChannelFieldList($where);

        foreach ($list['data'] as $row){
            $this->tablefield->del_field($row['ext_table'],$row['field_name']);
        }

        $result = $this->modelChannelField->deleteInfo($where,true);

        $result && action_log('删除', '模型字段，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '字段删除成功'] : [RESULT_ERROR, $this->modelChannelField->getError()];
    }


    //扩展字段表单显示
    //pararm $ext_table 		[description] 扩展表名
    //pararm field_val_arr 		[description] 需要展示的字段html
    //return html string
    public function channelExtFieldHtml( $ext_table, $field_val_arr = [] ) {
        $where['visible']=['=','1'];
        $where['ext_table']=['=',$ext_table];
        $list = $this->modelChannelField->getList($where, "", 'sort asc', false)->toArray();
        $htmltxt = "";
        foreach ( $list as $key => $row ) {
            //是否存在字段值
            $field_value = array_key_exists( $row[ "field_name" ], $field_val_arr ) ? $field_val_arr[ $row[ "field_name" ] ] : "";
            switch ( $row[ 'field_type' ] ) {
                case "varchar":
                    $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "show_name" ] . '</label>
									<div class="col-sm-10">
										<input name="' . $row[ "field_name" ] . '" class="form-control" type="text" value="' . $field_value . '"/>
										<span class="help-block m-b-none">' . $row[ 'desc' ] . '</span> 
									</div>
								</div>';
                    break;
                case "textarea":
                    $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "show_name" ] . '</label>
									<div class="col-sm-10">
										<textarea name="' . $row[ "field_name" ] . '" class="form-control" >' . $field_value . '</textarea>
										<span class="help-block m-b-none">' . $row[ 'desc' ] . '</span> 
									</div>
								</div>';
                    break;
                case "int":
                    $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "show_name" ] . '</label>
									<div class="col-sm-10">
										<input name="' . $row[ "field_name" ] . '" class="form-control" type="text" value="' . $field_value . '"/>
										<span class="help-block m-b-none">' . $row[ 'desc' ] . '</span> 
									</div>
								</div>';
                    break;
                case "float":
                    $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "show_name" ] . '</label>
									<div class="col-sm-10">
										<input name="' . $row[ "field_name" ] . '" class="form-control" type="text" value="' . $field_value . '"/>
										<span class="help-block m-b-none">' . $row[ 'desc' ] . '</span> 
									</div>
								</div>';
                    break;
                case "datetime":
                    $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "show_name" ] . '</label>
									<div class="col-sm-10">
										<input name="' . $row[ "field_name" ] . '" class="form-control datetimepicker" type="text" value="' . $field_value . '"/>
										<span class="help-block m-b-none">' . $row[ 'desc' ] . '</span> 
									</div>
								</div>';
                    break;
                case "date":
                    $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "show_name" ] . '</label>
									<div class="col-sm-10">
										<input name="' . $row[ "field_name" ] . '" class="form-control datepicker" type="text" value="' . $field_value . '"/>
										<span class="help-block m-b-none">' . $row[ 'desc' ] . '</span> 
									</div>
								</div>';
                    break;
                case "option":
                    $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "show_name" ] . '</label>
									<div class="col-sm-10">
									  <select data-placeholder="选择' . $row[ "show_name" ] . '..." name="' . $row[ "field_name" ] . '" class="chosen-select ' . $row[ "field_name" ] . '-chosen-select" style="width: 200px;" tabindex="2">
								';
                    $option_arr = explode( ',', $row[ 'default' ] );
                    foreach ( $option_arr as $va ) {
                        $htmltxt .= '<option value="' . $va . '" hassubinfo="true">' . $va . '</option>';
                    }
                    $htmltxt .= '
									  </select>
									</div>
								</div>';
                    break;
                case "linkage":
                    $htmltxt .= '<div class="form-group">
									<label class="col-sm-2 control-label">' . $row[ "show_name" ] . '</label>
									<div class="col-sm-10">
									  <select data-placeholder="选择' . $row[ "show_name" ] . '..." name="' . $row[ "field_name" ] . '" class="chosen-select ' . $row[ "field_name" ] . '-chosen-select" style="width: 200px;" tabindex="2">
								';
                    $option_arr = explode( ',', $row[ 'default' ] );
                    $option_arr = $this->cst_field_ext_linkage( $row[ 'default' ] );
                    foreach ( $option_arr as $row ) {
                        $htmltxt .= '<option value="' . $row[ 'id' ] . '" hassubinfo="true">' . $row[ 'name' ] . '</option>';
                    }
                    $htmltxt .= '
									  </select>
									</div>
								</div>';
                    break;
                case "radio":
                    $htmltxt .= '<div class="form-group text-left">
									<label class="col-sm-2 control-label">' . $row[ "show_name" ] . '</label>
									<div class="col-sm-10">
										<div class="radio i-checks">
								';
                    $option_arr = explode( ',', $row[ 'default' ] );
                    foreach ( $option_arr as $va ) {
                        $checked = ( $va == $field_value ) ? "checked" : "";
                        $htmltxt .= '<input type="radio" name="' . $row[ "field_name" ] . '" value="' . $va . '" ' . $checked . ' /> ' . $va . ' ';
                    }
                    $htmltxt .= '
									  </div>
									</div>
								</div>';
                    break;
                case "checkbox":
                    $htmltxt .= '<div class="form-group text-left">
									<label class="col-sm-2 control-label">' . $row[ "show_name" ] . '</label>
									<div class="col-sm-10">
										<div class="checkbox i-checks">
								';
                    $option_arr = explode( ',', $row[ 'default' ] );
                    foreach ( $option_arr as $va ) {
                        $htmltxt .= '<input type="checkbox" name="' . $row[ "field_name" ] . '" value="' . $va . '" ' . $checked . '/> ' . $va . ' ';
                    }
                    $htmltxt .= '
									  </div>
									</div>
								</div>';
                    break;
                default:
                    $htmltxt .= '';
            }
        }
        return $htmltxt;
    }

}
