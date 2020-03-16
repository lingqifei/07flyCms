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
 * 散客 =》身份证信息=》逻辑
 */
class SkOrderIdcard extends LtasBase
{

    /**
     * 获取散客接送站表列表
     */
    public function getSkOrderIdcardList($where = [], $field ="a.*,o.idcards,o.tourist_name,o.tourist_mobile,o.arrive_date,t.trip_name", $order = 'a.sort asc', $paginate = false)
    {

        $this->modelSkTeam->alias('a');

            $join = [
                [SYS_DB_PREFIX . 'sk_order_trip t', 'a.id = t.team_id','LEFT'],
                [SYS_DB_PREFIX . 'sk_order o', 't.order_id = o.id','LEFT'],
            ];

        $this->modelSkTeam->join = $join;

        $list =$this->modelSkTeam->getList($where, $field, $order, $paginate)->toArray();

        $data=[];
        foreach ($list as  $key=>$record){
            $idcards=$record['idcards'];
            if($idcards){
                $rowArr = explode("\r\n",$idcards);
                foreach ($rowArr as $row){
                    if(count(trim($row))<=0) continue;
                    $row = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/" ,',' ,$row);
                    $cell  = explode(',',$row);
                    $tmp['arrive_date']=$record['arrive_date'];
                    $tmp['line_name']=$record['line_name'];
                    $tmp['trip_name']=$record['trip_name'];
                    $tmp['guide_name']=$record['guide_name'];
                    $tmp['tourist_name']=$record['tourist_name'];
                    $tmp['tourist_mobile']=$record['tourist_mobile'];
                    $tmp['name']=$cell[0];
                    $tmp['idcard']=(count($cell)<=1)?'':$cell[1];

                    $data[]=array_merge($tmp,get_idcard_info($tmp['idcard']));
                }
            }
        }
        return $data;
    }

    /**
     * 散客订单=》身份证=》下载
     */
    public function getSkOrderIdcardListDown($where = [], $field = "", $order = 'a.sort asc')
    {
        $list=$this->getSkOrderIdcardList($where, $field, $order,  $paginate = false);

        $titles = "抵达日期,导游,游客姓名,游客电话,线路,行程,门票类型,证件类型,证件号码,证件姓名";
        $keys   = "arrive_date,guide_name,tourist_name,tourist_mobile,line_name,trip_name,ticket,type,idcard,name";

        action_log('下载', '下载身份证（散客）信息列表');

        export_excel($titles, $keys, $list, '身份证（散客）信息');

    }


    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = '';

        !empty($data['keywords']) && $where['a.remark|a.guide_name|a.line_name|a.trip_name|a.driver_name|o.tourist_name|o.tourist_mobile'] = ['like', '%'.$data['keywords'].'%'];

        !empty($data['guide_id']) && $where['a.guide_id'] = ['=', $data['guide_id']];

        !empty($data['date_se']) && $where['a.team_date'] = ['=', $data['date_se']];
        !empty($data['date_s']) && $where['a.team_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.team_date'] = ['<', $data['date_e']];
        !empty($data['date_s']) &&   !empty($data['date_e']) && $where['a.team_date'] = ['between', [$data['date_s'],$data['date_e']]];

        return $where;
    }


}