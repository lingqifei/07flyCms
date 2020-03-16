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
 * 团队司机行程表逻辑
 */
class TmOrderDriver extends LtasBase
{

    /**
     * 团队司机-列表
     */
    public function getTmOrderDriver($where = [], $field ="*", $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list =$this->modelTmOrderDriver->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     * 团队司机=》列表=》司机信息
     */
    public function getTmOrderDriverListInfo($where = [], $field ="a.*", $order = 'a.sort asc', $paginate = DB_LIST_ROWS)
    {
        $this->modelTmOrderDriver->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'driver d', 'd.id = a.driver_id','LEFT'],
        ];

        $this->modelTmOrderDriver->join = $join;

        $list =$this->modelTmOrderDriver->getList($where, $field, $order, $paginate);

        return $list;
    }

    /**
     * 获取团队司机订单信息
     */
    public function getTmOrderDriverInfo($where = [], $field = "*")
    {

        $info=$this->modelTmOrderDriver->getInfo($where, $field);

        return $info;
    }



    /**
     * 跟团-添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function tmOrderDriverAdd($data = [])
    {

        $validate_result = $this->validateTmOrderDriver->scene('add')->check($data);

        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmOrderDriver->getError()];
        }

        $result = $this->modelTmOrderDriver->setInfo($data);

        $result && action_log('新增', '新增团队跟团司机，司机：' . $data['driver_name']);

        return $result ? [RESULT_SUCCESS, '团队跟团司机添加成功', ""] : [RESULT_ERROR, $this->modelTmOrderDriver->getError()];
    }

    /**
     * 跟团-编辑
     */
    public function tmOrderDriverEdit($data = [])
    {
        $validate_result = $this->validateTmOrderDriver->scene('edit')->check($data);

        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmOrderDriver->getError()];
        }

        $result = $this->modelTmOrderDriver->setInfo($data);

        $result && action_log('编辑', '编辑团队跟团司机，司机：' .$data['driver_name']);

        return $result ? [RESULT_SUCCESS, '团队跟团司机编辑成功', ""] : [RESULT_ERROR, $this->modelTmOrderDriver->getError()];

    }
    /**
     * 团队接司机分配
     */
    public function tmOrderDriverArriveEdit($data = [])
    {

        $orderData=[
            "id"=>$data['order_id'],
            "arrive_train_id"=>$data['arrive_train_id'],
            "arrive_train_name"=>$data['arrive_train_name'],
            "arrive_station_id"=>$data['arrive_station_id'],
            "arrive_station_name"=>$data['arrive_station_name'],
            "arrive_time"=>$data['arrive_time'],
        ];

        $result = $this->modelTmOrder->setInfo($orderData);

        $driverData=[
            "id"=>$data['order_driver_id'],
            "type"=>'1',//表示接站
            "order_id"=>$data['order_id'],
            "train_id"=>$data['arrive_train_id'],
            "train_name"=>$data['arrive_train_name'],
            "station_id"=>$data['arrive_station_id'],
            "station_name"=>$data['arrive_station_name'],
            "driver_id"=>$data['driver_id'],
            "driver_name"=>$data['driver_name'],
            "driver_fee"=>$data['driver_fee'],
            "driver_date"=>$data['arrive_date'],
            "driver_time"=>$data['arrive_time'],
            "remark"=>$data['remark'],
        ];

        $result = $this->modelTmOrderDriver->setInfo($driverData);

        $result && action_log('编辑', '编辑团队分配司机，name：' );

        return $result ? [RESULT_SUCCESS, '团队司机分配成功', ""] : [RESULT_ERROR, $this->modelTmOrderDriver->getError()];

    }

    /**
     * 团队送司机分配
     */
    public function tmOrderDriverSendEdit($data = [])
    {

        $orderData=[
            "id"=>$data['order_id'],
            "leave_train_id"=>$data['leave_train_id'],
            "leave_train_name"=>$data['leave_train_name'],
            "leave_station_id"=>$data['leave_station_id'],
            "leave_station_name"=>$data['leave_station_name'],
            "leave_time"=>$data['leave_time'],
        ];

        $result = $this->modelTmOrder->setInfo($orderData);

        $driverData=[
            "id"=>$data['order_driver_id'],
            "type"=>'2',//表示送站
            "order_id"=>$data['order_id'],
            "train_id"=>$data['leave_train_id'],
            "train_name"=>$data['leave_train_name'],
            "station_id"=>$data['leave_station_id'],
            "station_name"=>$data['leave_station_name'],
            "driver_id"=>$data['driver_id'],
            "driver_name"=>$data['driver_name'],
            "driver_fee"=>$data['driver_fee'],
            "driver_date"=>$data['leave_date'],
            "driver_time"=>$data['leave_time'],
            "remark"=>$data['remark'],
        ];

        $result = $this->modelTmOrderDriver->setInfo($driverData);

        $result && action_log('编辑', '编辑送团队分配司机，name：' );

        return $result ? [RESULT_SUCCESS, '团队司机分配成功', ""] : [RESULT_ERROR, $this->modelTmOrderDriver->getError()];

    }

    /**
     * 团队订单删除
     */
    public function tmOrderDriverDel($where = [])
    {
        $result = $this->modelTmOrderDriver->deleteInfo($where,true);

        $result && action_log('删除', '删除跟团司机信息，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '跟团司机信息删除成功'] : [RESULT_ERROR, $this->modelTmOrderDriver->getError()];
    }

    //接送类型
    public function getTypeText($sType = '')
    {
        return $this->modelTmOrderDriver->getTypeText($sType);
    }

    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = '';

        //关键字查
        !empty($data['keywords']) && $where['a.driver_name|a.train_name|a.station_name|o.tourist_name|o.tourist_mobile|a.remark'] = ['like', '%'.$data['keywords'].'%'];

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
        if( $orderField=='by_driver_date' ){
            $order_by ="a.driver_date $orderDirection";
        }else if($orderField=='by_driver_type'){
            $order_by ="a.type $orderDirection";
        }else if($orderField=='by_tourist_name'){
            $order_by ="o.tourist_name $orderDirection";
        }else if($orderField=='by_tourist_mobile'){
            $order_by ="o.tourist_mobile $orderDirection";
        }else if($orderField=='by_all_num'){
            $order_by ="o.all_num $orderDirection";
        }else if($orderField=='by_train_name'){
            $order_by ="a.train_name $orderDirection";
        }else if($orderField=='by_driver_time'){
            $order_by ="a.driver_time $orderDirection";
        }else if($orderField=='by_station_name'){
            $order_by ="a.station_name $orderDirection";
        }else if($orderField=='by_driver_name'){
            $order_by ="a.driver_name $orderDirection";
        }else if($orderField=='by_driver_fee'){
            $order_by ="a.driver_fee $orderDirection";
        }else{
            $order_by ="a.driver_date desc";
        }
        return $order_by;
    }

    /**
     * 获取列表搜索=>送站
     */
    public function getWhereSend($data = [])
    {
        $where='';

        !empty($data['keywords']) && $where['a.tourist_name|a.tourist_mobile|a.leave_station_name|a.leave_train_name|d.driver_name'] = ['like', '%'.$data['keywords'].'%'];

        !empty($data['date_s']) && $where['a.leave_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.leave_date'] = ['<=', $data['date_e']];
        !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.leave_date'] = ['between', [$data['date_s'],$data['date_e']]];

        return $where;
    }

    /**
     * 获取排序条件=》送站
     */
    public function getOrderBySend($data = [])
    {
        //排序操作
        if(!empty($data['orderField'])){
            $orderField = $data['orderField'];
            $orderDirection = $data['orderDirection'];
        }else{
            $orderField="";
            $orderDirection="";
        }

        switch ($orderField)
        {
            case 'by_date':
                $order_by ="a.leave_date $orderDirection";
                break;
            case 'by_time':
                $order_by ="a.leave_time $orderDirection";
                break;
            case 'by_tourist_name':
                $order_by ="a.tourist_name $orderDirection";
                break;
            case 'tourist_mobile':
                $order_by ="a.tourist_mobile $orderDirection";
                break;
            case 'by_all_num':
                $order_by ="a.all_num $orderDirection";
                break;
            case 'by_train':
                $order_by ="a.leave_train_name $orderDirection";
                break;
            case 'by_station':
                $order_by ="a.leave_station_name $orderDirection";
                break;
            case 'by_driver_name':
                $order_by ="d.driver_name $orderDirection";
                break;
            case 'by_driver_fee':
                $order_by ="d.driver_fee $orderDirection";
                break;
            default:
                $order_by ="a.leave_date asc";
        }
        return $order_by;
    }

    /**
     * 获取列表搜索=>接站
     */
    public function getWhereArrive($data = [])
    {
        $where='';

        !empty($data['keywords']) && $where['a.tourist_name|a.tourist_mobile|a.arrive_station_name|a.arrive_train_name|d.driver_name'] = ['like', '%'.$data['keywords'].'%'];

        !empty($data['date_s']) && $where['a.arrive_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.arrive_date'] = ['<=', $data['date_e']];
        !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.arrive_date'] = ['between', [$data['date_s'],$data['date_e']]];

        return $where;
    }

    /**
     * 获取排序条件=》接站
     */
    public function getOrderByArrive($data = [])
    {
        //排序操作
        if(!empty($data['orderField'])){
            $orderField = $data['orderField'];
            $orderDirection = $data['orderDirection'];
        }else{
            $orderField="";
            $orderDirection="";
        }

        switch ($orderField)
        {
            case 'by_date':
                $order_by ="a.arrive_date $orderDirection";
                break;
            case 'by_time':
                $order_by ="a.arrive_time $orderDirection";
                break;
            case 'by_tourist_name':
                $order_by ="a.tourist_name $orderDirection";
                break;
            case 'tourist_mobile':
                $order_by ="a.tourist_mobile $orderDirection";
                break;
            case 'by_all_num':
                $order_by ="a.all_num $orderDirection";
                break;
            case 'by_train':
                $order_by ="a.arrive_train_name $orderDirection";
                break;
            case 'by_station':
                $order_by ="a.arrive_station_name $orderDirection";
                break;
            case 'by_driver_name':
                $order_by ="d.driver_name $orderDirection";
                break;
            case 'by_driver_fee':
                $order_by ="d.driver_fee $orderDirection";
                break;
            default:
                $order_by ="a.leave_date asc";
        }
        return $order_by;
    }


    /**
     * 司机订单=》列表下载=》下载
     */
    public function getTmOrderDriverListDown($where = [], $field = "a.*,o.tourist_name,o.tourist_mobile,o.line_name,o.agency_name,o.all_num,o.adult_num,o.child_num", $order = '', $paginate=false)
    {
        $this->modelTmOrderDriver->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'tm_order o', 'o.id = a.order_id','LEFT'],
        ];

        $this->modelTmOrderDriver->join = $join;

        $list =$this->modelTmOrderDriver->getList($where, $field, $order, $paginate)->toArray();

        $titles = "类型,日期,时间,站点,车次,司机姓名,游客姓名,游客电话,线路,办事处,总人数,成人人数,儿童人数";
        $keys   = "type,driver_date,driver_time,station_name,train_name,driver_name,tourist_name,tourist_mobile,line_name,agency_name,all_num,adult_num,child_num";

        action_log('下载', '下载司机（接送团队）列表');

        export_excel($titles, $keys, $list, '司机（接送团队）列表');

    }

}