<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Arcextor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\cms\logic;

use app\common\logic\TableField;
use think\Db;

/**
 *  文章扩展项目逻辑
 */
class Arcext extends CmsBase
{

    private $tablefield = null;


    /**
     * 析构函数
     */
    function __construct()
    {
        $this->tablefield = new TableField();
    }

    /**
     *  文章扩展项目处列表
     */
    public function getArcextList($where = [], $field = true, $order = '', $paginate = DB_LIST_ROWS)
    {
        return $this->modelArcext->getList($where, $field, $order, $paginate)->toArray();
    }

    /**
     *  文章扩展表单添加
     */
    public function arcextAdd($data = [])
    {

        $validate_result = $this->validateArcext->scene('add')->check($data);

        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateArcext->getError()];
        }

        $url = url('show');
        $data['maintable'] = 'arcext';//主表
        $data['addtable'] = 'arcext_' . $data['nid'];//扩展表

        //添加创建表
        $rtn = $this->tablefield->add_table(SYS_DB_PREFIX . $data['addtable'], $this->getAddTableSql(SYS_DB_PREFIX . $data['addtable']));
        if ($rtn[0] == RESULT_ERROR) return $rtn;

        $result = $this->modelArcext->setInfo($data);

        $result && action_log('新增', '新增文章扩展表单，name：' . $data['name']);

        return $result ? [RESULT_SUCCESS, '添加成功', $url] : [RESULT_ERROR, $this->modelArcext->getError()];
    }

    /**
     *  文章扩展表单编辑
     */
    public function arcextEdit($data = [])
    {

        $validate_result = $this->validateArcext->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateArcext->getError()];
        }

        $url = url('arcextList');

        $result = $this->modelArcext->setInfo($data);

        $result && action_log('编辑', '编辑 文章扩展表单，name：' . $data['name']);

        return $result ? [RESULT_SUCCESS, ' 文章扩展表单编辑成功', $url] : [RESULT_ERROR, $this->modelArcext->getError()];
    }

    /**
     *  文章扩展表单删除
     */
    public function arcextDel($where = [])
    {

        $list = $this->getArcextList($where);

        foreach ($list['data'] as $row) {
            $this->tablefield->drop_table(SYS_DB_PREFIX . $row['addtable']);
        }

        $result = $this->modelArcext->deleteInfo($where, true);

        $result && action_log('删除', '删除 文章扩展表单，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, ' 文章扩展表单删除成功'] : [RESULT_ERROR, $this->modelArcext->getError()];
    }

    /**
     *  文章扩展项目处信息
     */
    public function getArcextInfo($where = [], $field = true)
    {

        return $this->modelArcext->getInfo($where, $field);
    }


    /**封装orderby
     * @param array $data
     * Author: lingqifei created by at 2020/3/2 0002
     */
    public function getOrderby($data = [])
    {
        $order_by = '';
        //排序操作
        if (!empty($data['orderField'])) {
            $orderField = $data['orderField'];
            $orderDirection = $data['orderDirection'];
        } else {
            $orderField = "";
            $orderDirection = "";
        }
        if ($orderField == 'by_name') {
            $order_by = "name $orderDirection";
        } else if ($orderField == 'by_mobile') {
            $order_by = "mobile $orderDirection";
        } else if ($orderField == 'by_linkman') {
            $order_by = "linkman $orderDirection";
        } else if ($orderField == 'by_tel') {
            $order_by = "tel $orderDirection";
        } else if ($orderField == 'by_sort') {
            $order_by = "sort $orderDirection";
        } else {
            $order_by = "sort asc";
        }
        return $order_by;
    }


    /**
     * 创建扩展表SQL
     */
    public function getAddTableSql($table)
    {
        $sql = "
CREATE TABLE `$table` (
	`id` int(22) UNSIGNED NOT NULL AUTO_INCREMENT,
	`arcext_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`archives_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`sort` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`content` TEXT NULL,
	`title` VARCHAR(255) NOT NULL DEFAULT '',
	`litpic` VARCHAR(255) NOT NULL DEFAULT '',
	`pubdate` DATETIME NULL DEFAULT NULL,
	`create_time` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `arcext_id` (`arcext_id`)
)
COMMENT='文章扩展表单扩展表'
COLLATE='utf8_general_ci'
ENGINE=MyISAM;";
        return $sql;
    }


    /**
     * 扩展表信息列表数据
     * @param array $data
     * @return array
     * @throws \think\exception\DbException
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/7/1 0001
     */
    public function getArcextExtList($data=[])
    {
        if(empty($data['arcext_id'])){
            $map=['channel_id'=>$data['channel_id']];
        }else{
            $map=['id'=>$data['arcext_id']];
        }
        $table=$this->modelArcext->getInfo($map,'addtable,maintable')->toArray();

        //扩展数据处理

        if(!empty($data['arcext_id'])){
            $where['arcext_id']=['=',$data['arcext_id']];
        }
        $where['archives_id']=['=',$data['archives_id']];

        $list=Db::table(SYS_DB_PREFIX.$table['addtable'])
            ->where($where)
            ->order('create_time desc')
            ->paginate(DB_LIST_ROWS)
            ->toArray();
        foreach ($list['data'] as &$row){

        }
        return $list;
    }

    /**
     *  留言表单编辑
     */
    public function arcextExtAdd($data = [])
    {

        $table=$this->getArcextExtTableInfo($data['arcext_id']);

        $data['create_time']=format_time();
        $result=Db::table(SYS_DB_PREFIX.$table['addtable'])->insert($data);

        $url = url('ext_list');
        $result && action_log('编辑', '编辑内容：' . $data['content']);

        return $result ? [RESULT_SUCCESS, ' 操作成功', $url] : [RESULT_ERROR, $this->modelArcext->getError()];
    }

    /**
     *  留言表单编辑
     */
    public function arcextExtEdit($data = [])
    {

        $table=$this->getArcextExtTableInfo($data['arcext_id']);

        $where['arcext_id']=['=',$data['arcext_id']];
        $where['id']=['=',$data['id']];
        $result=Db::table(SYS_DB_PREFIX.$table['addtable'])->where($where)->update($data);

        $url = url('ext_list');
        $result && action_log('编辑', '编辑内容：' . $data['content']);

        return $result ? [RESULT_SUCCESS, ' 操作成功', $url] : [RESULT_ERROR, $this->modelArcext->getError()];
    }

    /**
     *  留言表单删除
     */
    public function arcextExtDel($data = [])
    {

        $table=$this->getArcextExtTableInfo($data['arcext_id']);

        $where['arcext_id']=['=',$data['arcext_id']];
        $where['id']=['=',$data['id']];
        $result=Db::table(SYS_DB_PREFIX.$table['addtable'])->where($where)->delete();

        $result && action_log('删除', '删除 留言表单，where：' . http_build_query($data));
        return $result ? [RESULT_SUCCESS, ' 删除成功'] : [RESULT_ERROR, $this->modelArcext->getError()];
    }

    /**
     *  留言表单管理处信息
     */
    public function getArcextExtInfo($data=[])
    {
        if(empty($data['arcext_id'])){
            return [RESULT_ERROR,'选择表单'];
        }

        $table=$this->getArcextExtTableInfo($data['arcext_id']);

        $where['arcext_id']=['=',$data['arcext_id']];
        $where['id']=['=',$data['id']];
        $info=Db::table(SYS_DB_PREFIX.$table['addtable'])
            ->where($where)
            ->find();
        $info['litpic_array']=explode(',',$info['litpic']);
        return $info;
    }

    /**获取表单数据表信息
     * @param int $gid
     * @return mixed
     * Author: lingqifei created by at 2020/3/2 0002
     */
    public function getArcextExtTableInfo($gid=0)
    {
        $info=$this->modelArcext->getInfo(['id'=>$gid],'addtable,maintable');
        return $info;
    }

}
