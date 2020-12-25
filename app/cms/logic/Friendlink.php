<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Friendlinkor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\cms\logic;

use app\common\logic\TableField;

/**
 * 友情链接管理=》逻辑层
 */
class Friendlink extends CmsBase
{
    /**
     * 友情链接列表
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int|mixed $paginate
     * @return
     */
    public function getFriendlinkList($where = [], $field = true, $order = '', $paginate = DB_LIST_ROWS)
    {
        return $this->modelFriendlink->getList($where, $field, $order, $paginate)->toArray();
    }

    /**
     * 友情链接添加
     * @param array $data
     * @return array
     */
    public function friendlinkAdd($data = [])
    {

        $validate_result = $this->validateFriendlink->scene('add')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateFriendlink->getError()];
        }
        $result = $this->modelFriendlink->setInfo($data);
        $url = url('show');
        $result && action_log('新增', '新增友情链接：' . $data['title']);

        return $result ? [RESULT_SUCCESS, '添加成功', $url] : [RESULT_ERROR, $this->modelFriendlink->getError()];
    }

    /**
     * 友情链接编辑
     * @param array $data
     * @return array
     */
    public function friendlinkEdit($data = [])
    {

        $validate_result = $this->validateFriendlink->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateFriendlink->getError()];
        }

        $url = url('show');

        $result = $this->modelFriendlink->setInfo($data);

        $result && action_log('编辑', '编辑友情链接，name：' . $data['title']);

        return $result ? [RESULT_SUCCESS, '编辑成功', $url] : [RESULT_ERROR, $this->modelFriendlink->getError()];
    }

    /**
     * 友情链接删除
     * @param array $where
     * @return array
     */
    public function friendlinkDel($where = [])
    {

        $result = $this->modelFriendlink->deleteInfo($where,true);

        $result && action_log('删除', '删除友情链接，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelFriendlink->getError()];
    }

    /**友情链接信息
     * @param array $where
     * @param bool $field
     * @return
     */
    public function getFriendlinkInfo($where = [], $field = true)
    {

        return $this->modelFriendlink->getInfo($where, $field);
    }

}
