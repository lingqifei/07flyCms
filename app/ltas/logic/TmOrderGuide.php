<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 团队导游行程表逻辑
 */
class TmOrderGuide extends LtasBase
{
    /**
     * 跟团安排-列表
     */
    public function getTmOrderGuideList($where = [], $field ="*", $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        $list =$this->modelTmOrderGuide->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 团队导游=》列表=》导游信息
     */
    public function getTmOrderGuideListInfo($where = [], $field ="a.*", $order = 'a.sort asc', $paginate = DB_LIST_ROWS)
    {
        $this->modelTmOrderGuide->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'guide g', 'g.id = a.guide_id','LEFT'],
        ];

        $this->modelTmOrderGuide->join = $join;

        $list =$this->modelTmOrderGuide->getList($where, $field, $order, $paginate);

        return $list;
    }


    /**
     * 获取团队导游订单信息
     */
    public function getTmOrderGuideInfo($where = [], $field = "*")
    {

        $info=$this->modelTmOrderGuide->getInfo($where, $field);

        return $info;
    }


    /**
     * 跟团-添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function tmOrderGuideAdd($data = [])
    {

        $validate_result = $this->validateTmOrderGuide->scene('add')->check($data);

        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmOrderGuide->getError()];
        }

        $result = $this->modelTmOrderGuide->setInfo($data);

        $result && action_log('新增', '新增团队跟团导游，导游：' . $data['guide_name']);

        return $result ? [RESULT_SUCCESS, '团队跟团导游添加成功', ""] : [RESULT_ERROR, $this->modelTmOrderGuide->getError()];
    }

    /**
     * 跟团-编辑
     */
    public function tmOrderGuideEdit($data = [])
    {
        $validate_result = $this->validateTmOrderGuide->scene('edit')->check($data);

        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmOrderGuide->getError()];
        }

        $result = $this->modelTmOrderGuide->setInfo($data);

        $result && action_log('编辑', '编辑团队跟团导游，导游：' .$data['guide_name']);

        return $result ? [RESULT_SUCCESS, '团队跟团导游编辑成功', ""] : [RESULT_ERROR, $this->modelTmOrderGuide->getError()];

    }

    /**
     * 团队订单删除
     */
    public function tmOrderGuideDel($where = [])
    {
        $result = $this->modelTmOrderGuide->deleteInfo($where,true);

        $result && action_log('删除', '删除跟团导游信息，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '跟团导游信息删除成功'] : [RESULT_ERROR, $this->modelTmOrderGuide->getError()];
    }

}