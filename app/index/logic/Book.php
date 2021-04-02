<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Channelor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\index\logic;
use \think\Db;
/**
 * 频道栏目管理=》逻辑层
 */
class Book extends IndexBase
{

    /**文章列表查询
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int $paginate
     * @return mixed
     * Author: lingqifei created by at 2020/2/27 0027
     */
    public function getBookList($where = [], $field = true, $order = '', $paginate = 15)
    {
        $this->modelBook->alias('a');
        $list= $this->modelBook->getList($where, $field, $order, $paginate)->toArray();

        $paginate===false && $list['data']=$list;

        foreach ($list['data'] as &$row){
            $row['litpic'] =get_picture_url($row['litpic']);
            $row['target'] = ($row['target'] == 1) ? 'target="_blank"' : 'target="_self"';
        }

        return $list;
    }

    /**信息
     * @param array $where
     * @param bool $field
     * @return
     */
    public function getBookInfo($where = [], $field = true)
    {
        return $this->modelBook->getInfo($where, $field);
    }

    /**文章列表查询
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int $paginate
     * @return mixed
     * Author: lingqifei created by at 2020/2/27 0027
     */
    public function getBookChapList($where = [], $field = true, $order = '', $paginate = 15)
    {
        $list=Db::name('book_chap')
            ->where($where)
            ->field($field)
            ->order('id asc')
            ->select();
        return $list;
    }

    /**信息
     * @param array $where
     * @param bool $field
     * @return
     */
    public function getBookChapInfo($where = [], $field = true)
    {
        return $this->modelBookChap->getInfo($where, $field);
    }

}