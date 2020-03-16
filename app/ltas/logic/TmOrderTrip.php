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
 * 团队订单行程表逻辑
 */
class TmOrderTrip extends LtasBase
{

    /**
     * 获取团队订单行程表列表
     *
     */
    public function getTmOrderTripList($where = [], $field = "", $order = 'trip_day asc', $paginate = false)
    {
        $this->modelTmOrderTrip->alias('a');

        $list = $this->modelTmOrderTrip->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 团队订单=>行程表添加
     * @param array $data [order_id,starte_date,days_id]
     */
    public function tmOrderTripInitAdd($data = [])
    {
        //验证数据
        $validate_result = $this->validateTmOrderTrip->scene('add')->check($data);
//        if (!$validate_result) {
//            return [RESULT_ERROR, $this->validateTmOrderTrip->getError()];
//        }


        //添加新的数据
        $result = $this->modelTmOrderTrip->setList($data);

        //$result && action_log('新增', '新增团队订单行程表，name：' . $data['tourist_name']);

        return $result ? $result : $this->modelTmOrderTrip->getError();
    }



    /**
     * 更新团队团编号id
     * @param team_id
     */
    public function tmOrderTripEdit($data = [])
    {

        $url = url('tmOrderTripList');

        $result = $this->modelTmOrderTrip->setFieldValue(['id' => ['in', $data['id']]], 'team_id', $data['team_id']);

        $result && action_log('编辑', '编辑团队订单行程表，name：' . $data['team_id']);

        return $result ? [RESULT_SUCCESS, '编辑成功', $url] : [RESULT_ERROR, $this->modelTmOrderTrip->getError()];
    }

    /**
     * 团队订单行程表删除
     */
    public function tmOrderTripDel($where = [])
    {

        $result = $this->modelTmOrderTrip->deleteInfo($where, true);

        $result && action_log('删除', '删除团队订单行程表，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '团队订单行程表删除成功'] : [RESULT_ERROR, $this->modelTmOrderTrip->getError()];
    }

    /**
     * 获取团队订单行程表信息
     */
    public function getTmOrderTripInfo($where = [], $field = "a.*")
    {
        $this->modelTmOrderTrip->alias('a');

//        $join = [
//            [SYS_DB_PREFIX . 'line l', 'a.line_id = l.id','LEFT'],//线路
//            [SYS_DB_PREFIX . 'agency ag', 'a.agency_id = ag.id','LEFT'],//办事处
//            [SYS_DB_PREFIX . 'saleman s', 'a.saleman_id = s.id','LEFT'],//业务员
//            [SYS_DB_PREFIX . 'days d', 'a.days_id = d.id','LEFT'],//日期
//        ];
//        $this->modelTmOrderTrip->join = $join;
        return $this->modelTmOrderTrip->getInfo($where, $field);
    }

}
