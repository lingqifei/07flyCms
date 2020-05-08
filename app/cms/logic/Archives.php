<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Archivesor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2020-02-12
 */

namespace app\cms\logic;

use app\common\logic\TableField;
use think\Db;
/**
 * 内容管理逻辑
 */
class Archives extends CmsBase
{

    private $tablefield = null;

    // 菜单Select结构
    public static $dataSelect   = [];

    /**
     * 析构函数
     */
    function  __construct() {
        $this->tablefield = new TableField();
    }

    /**
     * 文档管理处列表
     */
    public function getArchivesList($where = [], $field = 'a.*,t.typename', $order = 'a.sort asc', $paginate = DB_LIST_ROWS)
    {

        $this->modelArchives->alias('a');
        $join = [
            [SYS_DB_PREFIX . 'arctype t', 't.id = a.type_id','LEFT'],
        ];
        $this->modelArchives->join = $join;

        $list=$this->modelArchives->getList($where, $field, $order, $paginate)->toArray();
        if($paginate===false) $list['data']=$list;

        foreach ($list['data'] as &$row){
           $row['flag_name']=$this->logicArcatt->getArcattName($row['flag']);
        }
        return $list;
    }
    
    /**
     * 文档添加
     */
    public function archivesAdd($data = [])
    {
        
        $validate_result = $this->validateArchives->scene('add')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateArchives->getError()];
        }

        //1、关键字处理
        $arctype=$this->logicArctype->getArctypeInfoDetail($data['type_id']);
        if(!empty($data['keywords'])){
            $keywords=$data['keywords'];
        }else{
            $keywords=getKeywords($data['title'],html_msubstr($data['body'],0));
            $keywords && $keywords=arr2str($keywords,',');
        }
        //简介处理
        if(!empty($data['description'])){
            $description=$data['description'];
        }else{
            $description=html_msubstr($data['body'],0,200);
        }

        //2、主表数据
        $main_data=[
            'channel_id'=>$arctype['channel_id'],
            'type_id'=>$data['type_id'],
            'type_id2'=>$data['type_id2'],
            'title'=>$data['title'],
            'shorttitle'=>$data['shorttitle'],
            'flag'=>(!empty($data['flag']))?implode(",",$data['flag']):'',
            'litpic'=>$data['litpic'],
            'keywords'=>$keywords,
            'description'=>$description,
            'click'=>$data['click'],
            'writer'=>$data['writer'],
            'source'=>$data['source'],
            'pubdate'=>$data['pubdate'],
        ];
        $aid = $this->modelArchives->setInfo($main_data);

        //3、添加附加表
        $ext_field=$this->logicChannelField->getExtTableFieldList($arctype['maintable'],$arctype['addtable']);
        $ext_data=array(
            "id"=>$aid,
            "type_id"=>$data['type_id'],
            "body"=>$data['body'],
        );
        foreach($ext_field as $row){
            $field=$row['field_name'];
            if(!empty($data[$field])){
                $ext_data=array_merge($ext_data,array("$field"=>$data[$field]));
            }
        }
        $result=Db::name($arctype['addtable'])->insert($ext_data);

        $url = url('show');
        $result && action_log('新增', '新增文档，name：' . $data['title']);
        return $result ? [RESULT_SUCCESS, '添加成功', $url] : [RESULT_ERROR, $this->modelArchives->getError()];
    }
    
    /**
     * 文档编辑
     */
    public function archivesEdit($data = [])
    {
        
        $validate_result = $this->validateArchives->scene('edit')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateArchives->getError()];
        }

        //1、关键字处理
        $arctype=$this->logicArctype->getArctypeInfoDetail($data['type_id']);
        if(!empty($data['keywords'])){
            $keywords=$data['keywords'];
        }else{
            $keywords=getKeywords($data['title'],html_msubstr($data['body'],0));
            $keywords && $keywords=arr2str($keywords,',');
        }

        //简介处理
        if(!empty($data['description'])){
            $description=$data['description'];
        }else{
            $description=html_msubstr($data['body'],0,200);
        }

        //2、主表数据
        $main_data=[
            'id'=>$data['id'],
            'channel_id'=>$arctype['channel_id'],
            'type_id'=>$data['type_id'],
            'type_id2'=>$data['type_id2'],
            'title'=>$data['title'],
            'shorttitle'=>$data['shorttitle'],
            'flag'=>(!empty($data['flag']))?implode(",",$data['flag']):'',
            'litpic'=>$data['litpic'],
            'keywords'=>$keywords,
            'description'=>$description,
            'click'=>$data['click'],
            'writer'=>$data['writer'],
            'pubdate'=>$data['pubdate'],
        ];
        $this->modelArchives->setInfo($main_data);


        //2、添加附加表
        $ext_field=$this->logicChannelField->getExtTableFieldList($arctype['maintable'],$arctype['addtable']);
        $ext_data=array(
            "id"=>$data['id'],
            "type_id"=>$data['type_id'],
            "body"=>get_picture_body($data['body']),
        );
        foreach($ext_field as $row){
            $field=$row['field_name'];
            if(!empty($data[$field])){
                $ext_data=array_merge($ext_data,array("$field"=>$data[$field]));
            }
        }
        Db::name($arctype['addtable'])->update($ext_data);

        $url = url('show');
        action_log('编辑', '编辑文档，name：' . $data['title']);
        return [RESULT_SUCCESS, '编辑成功', $url];
    }

    /**
     * 文档移动
     */
    public function archivesMove($data=[])
    {
        $where['id']=['in',$data['id']];
        $post_data=[
            'type_id'=>$data['type_id'],
        ];
        $result=$this->modelArchives->setInfo($post_data,$where);
        $url = url('show');
        $result && action_log('移动', '移动文档，name：' . $data['id']);
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelArchives->getError()];
    }
    
    /**
     * 文档删除
     */
    public function archivesDel($where = [])
    {
        $arclist=$this->modelArchives->getList($where,true,true,false);
        foreach ($arclist as $row){
            $arctype=$this->logicArctype->getArctypeInfoDetail($row['type_id']);
            Db::table($arctype['addtable'])->delete($row['id'],true);
            $result = $this->modelArchives->deleteInfo($where,true);
        }
        $result && action_log('删除', '删除文档，where：' . http_build_query($where));
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelArchives->getError()];
    }
    
    /**
     * 文档信息
     */
    public function getArchivesInfo($where = [], $field = true)
    {
        $info=$this->modelArchives->getInfo($where, $field)->toArray();
        if($info){
            $arctype=$this->logicArctype->getArctypeInfoDetail($info['type_id']);
            $ext_info=Db::table($arctype['addtable'])->where('id',$info['id'])->find();
            if($ext_info){
                return array_merge($info,$ext_info);
            }else{
                Db::table($arctype['addtable'])->insert(['id'=>$info['id']]);
                return $info;
            }
        }
    }

    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = [];
        //关键字查
        !empty($data['keywords']) && $where['a.title'] = ['like', '%'.$data['keywords'].'%'];
        if(!empty($data['type_id'])){
            $typeid=$this->logicArctype->getArctypeAllSon($data['type_id']);
            $typeid[]=$data['type_id'];
            $where['a.type_id'] = ['in', $typeid];
        }

        !empty($data['date_s']) && $where['a.driver_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.driver_date'] = ['<', $data['date_e']];
        !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.driver_date'] = ['between', [$data['date_s'],$data['date_e']]];
        return $where;
    }

    /**
     * 获取排序条件
     */
    public function getOrderBy($data = [])
    {
        //排序操作
        if(!empty($data['orderField'])){
            $orderField = $data['orderField'];
            $orderDirection = $data['orderDirection'];
        }else{
            $orderField="";
            $orderDirection="";
        }
        if( $orderField=='by_sort' ){
            $order_by ="a.sort $orderDirection";
        }else if($orderField=='by_type'){
            $order_by ="a.type_id $orderDirection";
        }else{
            $order_by ="a.create_time desc";
        }
        return $order_by;
    }

}