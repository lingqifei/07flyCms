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
 * 团队 =》身份证信息=》逻辑
 */
class TmOrderIdcard extends LtasBase
{

    /**
     * 获取团队列表
     */
    public function getTmOrderIdcardList($where = [], $field ="a.*,g.guide_name,g.guide_id", $order = 'a.sort asc', $paginate = false)
    {

        $this->modelTmOrder->alias('a');

            $join = [
                [SYS_DB_PREFIX . 'tm_order_guide g', 'a.id = g.order_id','LEFT'],
            ];

        $this->modelTmOrder->join = $join;

        $list =$this->modelTmOrder->getList($where, $field, $order, $paginate)->toArray();

        $data=[];
        foreach ($list as  $key=>$record){
            $idcards=$record['idcards'];
            if($idcards){
                $rowArr = explode("\r\n",$idcards);
                foreach ($rowArr as $row){
                    $row = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/" ,',' ,$row);
                    $cell  = explode(',',$row);
                    $tmp['line_name']=$record['line_name'];
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
     * 团队订单=》身份证=》下载
     */
    public function getTmOrderIdcardListDown($where = [], $field = "", $order = 'a.sort asc')
    {
        $list=$this->getTmOrderIdcardList($where, $field, $order,  $paginate = false);

        $titles = "导游,游客姓名,游客电话,线路,门票类型,证件类型,证件号码,证件姓名";
        $keys   = "guide_name,tourist_name,tourist_mobile,line_name,ticket,type,idcard,name";

        action_log('下载', '下载身份证（团队）信息列表');

        export_excel($titles, $keys, $list, '身份证（团队）信息');

    }


    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = '';

        !empty($data['keywords']) && $where['g.guide_name|a.remark|a.line_name|a.tourist_name|a.tourist_mobile'] = ['like', '%'.$data['keywords'].'%'];

        !empty($data['guide_id']) && $where['g.guide_id'] = ['=', $data['guide_id']];

        !empty($data['date_s']) && $where['a.arrive_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.arrive_date'] = ['<', $data['date_e']];
        !empty($data['date_s']) &&   !empty($data['date_e']) && $where['a.arrive_date'] = ['between', [$data['date_s'],$data['date_e']]];

        return $where;
    }


}