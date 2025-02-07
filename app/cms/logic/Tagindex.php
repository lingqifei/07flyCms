<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Tagindexor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\cms\logic;

use app\common\logic\TableField;
use think\Db;

/**
 * 网站管理=》逻辑层
 */
class Tagindex extends CmsBase
{
    /**
     * 列表
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int|mixed $paginate
     * @return
     */
    public function getTagindexList($where = [], $field = true, $order = '', $paginate = DB_LIST_ROWS)
    {
        return $this->modelTagindex->getList($where, $field, $order, $paginate)->toArray();
    }

    /**
     * 标签文章
     */
    public function getTagindexArchives($data = [])
    {
        $where['a.tid'] = ['=', $data['tid']];
        if (!empty($data['keywords'])) {
            $where['arc.title|arc.description'] = ['like', '%' . $data['keywords'] . '%'];
        }
        $this->modelTaglist->alias('a');
        $join = [
            [SYS_DB_PREFIX . 'archives arc', 'arc.id = a.aid', 'LEFT'],
            [SYS_DB_PREFIX . 'arctype t', 't.id = a.typeid', 'LEFT'],
        ];
        $this->modelTaglist->join = $join;
        $field = 'a.aid,arc.title,arc.create_time,arc.update_time,arc.click,arc.writer,t.typename';
        $list = $this->modelTaglist->getList($where, $field, 'arc.id desc', DB_LIST_ROWS);
        return $list;
    }

    /**
     * 配置编辑
     */
    public function tagindexEdit($data = [])
    {
        $validate_result = $this->validateTagindex->scene('edit')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTagindex->getError()];
        }
        $result = $this->modelTagindex->setInfo($data);
        $result && action_log('编辑', '编辑标签，name：' . $data['name']);
        $url = url('tagindexList', array('group' => $data['group'] ? $data['group'] : 0));
        return $result ? [RESULT_SUCCESS, '编辑成功', $url] : [RESULT_ERROR, $this->modelTagindex->getError()];
    }

    /**
     * 配置删除
     */
    public function tagindexDel($data = [])
    {
        if (empty($data['id'])) {
            throw_response_error('参数错误');
        }
        $where['id'] = ['in', $data['id']];
        $result = $this->modelTagindex->deleteInfo($where, true);

        $where2['tid'] = ['in', $data['id']];
        $result && $this->modelTaglist->deleteInfo($where2, true);
        $result && action_log('删除', '删除标签，where：' . http_build_query($where));
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelTagindex->getError()];
    }

    public function getTagindexInfo($where = [], $field = true)
    {
        return $this->modelTagindex->getInfo($where, $field);
    }

    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {
        $where = [];
        //关键字查
        !empty($data['keywords']) && $where['tag'] = ['like', '%' . $data['keywords'] . '%'];

        !empty($data['date_s']) && !empty($data['date_e']) && $where['a.driver_date'] = ['between', [$data['date_s'], $data['date_e']]];
        return $where;
    }

    /**
     * 获取排序条件
     */
    public function getOrderBy($data = [])
    {
        $order_by = 'create_time desc';
        //排序操作
        if (!empty($data['orderField'])) {
            $orderField = $data['orderField'];
            $orderDirection = $data['orderDirection'];
            if ($orderField == 'count') {
                $order_by = "count $orderDirection";
            } else if ($orderField == 'total') {
                $order_by = "total $orderDirection";
            }
        }
        return $order_by;
    }


    public function tagindexArchivesCount($data = [])
    {
        if (empty($data['id'])) {
            throw_response_error('参数错误');
        }
        $where['id'] = ['in', $data['id']];
        $tagindexList = $this->modelTagindex->getColumn('', 'tag', 'id');
        foreach ($tagindexList as $tagId => $tagName) {
            $archivesCount = $this->modelTaglist->stat(['tid' => $tagId]);
            $result = $this->modelTagindex->updateInfo(['id' => $tagId], ['total' => $archivesCount]);
        }
        $result && action_log('标签文章数量统计', '标签文章数量统计，where：' . http_build_query($where));
        return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, $this->modelTagindex->getError()];
    }
}
