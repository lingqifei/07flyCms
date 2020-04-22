<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Arctypeor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2020-02-12
 */

namespace app\cms\logic;

use app\common\logic\TableField;

/**
 * 栏目逻辑
 */
class Arctype extends CmsBase
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
     * 模型管理处列表
     */
    public function getArctypeList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list=$this->modelArctype->getList($where, $field, $order, $paginate)->toArray();
        if($paginate===false) $list['data']=$list;
        foreach ($list['data'] as &$row){
            $row['ispart_text']=$this->modelArctype->ispart_text($row['ispart']);
            $row['channel_text']=$this->modelChannel->getValue(['id'=>$row['channel_id']],'name');
        }
        return $list;
    }
    
    /**
     * 模型添加
     */
    public function arctypeAdd($data = [])
    {
        
        $validate_result = $this->validateArctype->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateArctype->getError()];
        }

        $url = url('show');

        $result = $this->modelArctype->setInfo($data);

        $result && action_log('新增', '新增cms栏目，name：' . $data['typename']);
        
        return $result ? [RESULT_SUCCESS, '栏目添加成功', $url] : [RESULT_ERROR, $this->modelArctype->getError()];
    }
    
    /**
     * 模型编辑
     */
    public function arctypeEdit($data = [])
    {
        
        $validate_result = $this->validateArctype->scene('edit')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateArctype->getError()];
        }
        $result = $this->modelArctype->setInfo($data);
        $result && action_log('编辑', '编辑栏目，name：' . $data['typename']);
        $url = url('show');
        return $result ? [RESULT_SUCCESS, '栏目编辑成功', $url] : [RESULT_ERROR, $this->modelArctype->getError()];
    }
    
    /**
     * 模型删除
     */
    public function arctypeDel($where = [])
    {

        $result = $this->modelArctype->deleteInfo($where,true);
        
        $result && action_log('删除', '删除模型，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '模型删除成功'] : [RESULT_ERROR, $this->modelArctype->getError()];
    }
    
    /**
     * 模型管理处信息
     */
    public function getArctypeInfo($where = [], $field = true)
    {
        return $this->modelArctype->getInfo($where, $field);
    }

    /**
     * 获得栏目信息--》详细内容
     * @param int $typeid
     * @return
     */
    public function getArctypeInfoDetail($typeid=0)
    {
        $where['a.id']=['=',$typeid];

        $this->modelArctype->alias('a');
        $join = [
            [SYS_DB_PREFIX . 'channel c', 'c.id = a.channel_id'],
        ];

        $this->modelArctype->join = $join;

        return $this->modelArctype->getInfo($where, 'a.*,c.nid,c.maintable,c.addtable')->toArray();
    }


    //得到数形参数
    public function getArctypeListTree($where='')
    {
        $list = $this->getArctypeList($where,'','sort asc',false);
        $tree= list2tree($list['data'],0,0,'id','parent_id','typename');
        return $tree;
    }


    //输出树形参数
    function getArctypeListHtml($tree) {
        $html = '';
        foreach ( $tree as $row ) {
            $kg=($row['visible']=='0')?'[ 隐藏 ]':'';
            for($x=1;$x<$row['level'];$x++) {
                $kg .="<i class='fly-fl'>|—</i>";
            }
            if ( $row[ 'nodes' ] == '' ) {
                $html .= "<li><div class='row lines'>
                                <div class='col-sm-1'>ID:".$row['id']."</div>
								<i class='col-sm-1'>&nbsp;&nbsp;</i>
								<div  class='col-sm-6'>".$kg . $row['typename']."</div>
								<div class='col-sm-1'>文章</div>
								<div  class='col-sm-2'>
									<a class='ajax-open' data-url='".url('Arctype/add',array('id'=>$row["id"]))."'>增加下级</a> 
									<a class='ajax-open' data-url='".url('Arctype/edit',array('id'=>$row["id"]))."'>修改</a> 
									<a class='ajax-del' data-url='".url('Arctype/del',array('id'=>$row["id"]))."' >删除</a>
								</div>
								<div class='col-sm-1'>
								    <input type='text'  data-url='".url('Arctype/set_sort',array('id'=>$row["id"]))."'  value='".$row['sort']."' class='form-control ajax-sort' title='排序'/>
								 </div>
							</div>
						  </li>";
            } else {
                $html .= "<li>
                                <div class='row lines'>
                                <div class='col-sm-1'>ID:".$row['id']."</div>
								<lable class='col-sm-1'>+</lable>
								<div  class='col-sm-6'>".$kg. $row['typename']."</div>
								<div class='col-sm-1'>文章</div>
								<div  class='col-sm-2'>
									<a class='ajax-open' data-url='".url('Arctype/add',array('id'=>$row["id"]))."'>增加下级</a> 
									<a class='ajax-open' data-url='".url('Arctype/edit',array('id'=>$row["id"]))."'>修改</a> 
									<a class='ajax-del' data-url='".url('Arctype/del',array('id'=>$row["id"]))."' >删除</a>
								</div>
								<div class='col-sm-1'>
								    <input type='text'  data-url='".url('Arctype/set_sort',array('id'=>$row["id"]))."'  value='".$row['sort']."' class='form-control ajax-sort' title='排序'/>
								 </div>
							</div>
							";
                $html .= $this->getArctypeListHtml( $row[ 'nodes' ] );
                $html .= "</li>";
            }
        }
        return $html ? '<ul>' . $html . '</ul>': $html;
    }

    //输出树形参数
    function getArctypeListSelect($tree) {
        if(!empty($tree)){
            foreach ( $tree as $key=>$info ) {
                $info[ 'name' ] 	= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', --$info['level']).'|--'.$info['typename'];
                if ( $info[ 'nodes' ] == '' ) {
                    array_push(self::$dataSelect, $info);
                } else {
                    array_push(self::$dataSelect, $info);
                    $this->getArctypeListSelect( $info[ 'nodes' ]);
                }
                unset($info);
            }
        }
        return self::$dataSelect;
    }

    /**获得所有指定id所有父级
     * @param int $typeid
     * @param array $data
     * @return array
     */
    public function getArctypeAllPid($typeid=0, $data=[])
    {
        $where['id']=['=',$typeid];
        $info = $this->modelArctype->getInfo($where,true);
        if(!empty($info) && $info['parent_id']){
            $data[]=$info['parent_id'];
            return $this->getArctypeAllPid($info['parent_id'],$data);
        }
        return $data;
    }

    /**获得所有指定id所有子级
     * @param int $typeid
     * @param array $data
     * @return array
     */
    public function getArctypeAllSon($typeid=0, $data=[])
    {
        $where['parent_id']=['=',$typeid];
        $sons = $this->modelArctype->getList($where,true,'sort asc',false);
        if (count($sons) > 0) {
            foreach ($sons as $v) {
                $data[] = $v['id'];
                $data = $this->getArctypeAllSon($v['id'], $data); //注意写$data 返回给上级
            }
        }
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
        return $data;
    }

    /**获得所有指定id 所有同级
     * @param int $typeid
     * @param array $data
     * @return array
     */
    public function getArctypeAllSelf($typeid=0, $data=[])
    {

        $pid = $this->modelArctype->getValue(['id'=>$typeid],'parent_id');
        $where['parent_id']=['=',$typeid];
        $data = $this->modelArctype->getColumn(['parent_id'=>$pid],'id');
        return $data;
    }

}
