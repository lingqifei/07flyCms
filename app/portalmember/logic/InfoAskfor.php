<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * InfoAskforor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\portalmember\logic;

/**
 * 会员积分管理=》逻辑层
 */
class InfoAskfor extends MemberBase
{
    /**
     * 会员积分列表
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int|mixed $paginate
     * @return
     */
    public function getInfoAskforList($where = [], $field = '', $order = 'a.id desc', $paginate = DB_LIST_ROWS)
    {

        if(empty($field)){
            $field = 'a.*,t.typename,t2.typename as typename2,p.shortname as privonce_name,c.shortname as city_name';
        }
        $this->modelInfoAskfor->alias('a');
        $join = [
            [SYS_DB_PREFIX . 'info_type t', 't.id = a.type_id','LEFT'],
            [SYS_DB_PREFIX . 'info_type t2', 't.id = a.type_id2','LEFT'],
            [SYS_DB_PREFIX . 'region p', 'p.id = a.province_id','LEFT'],
            [SYS_DB_PREFIX . 'region c', 'c.id = a.city_id','LEFT'],
        ];

        $this->modelInfoAskfor->join = $join;

        $list= $this->modelInfoAskfor->getList($where, $field, $order, $paginate);

        return $list;
    }

    /**
     * 信息删除
     * @param array $where
     * @return array
     */
    public function infoAskforDel($data = [])
    {
        $where['id']=['in',$data['id']];
        $result = $this->modelInfoAskfor->deleteInfo($where,true);
        $url=url('show');
        return $result ? [RESULT_SUCCESS, '删除成功',] : [RESULT_ERROR, $this->modelInfo->getError()];
    }

    /**
     * 报名查看
     * @param array $where
     * @return array
     */
    public function infoAskforView($data = [])
    {
        $where['id']=['in',$data['id']];

        //查看信息扣分
        $res=$this->logicMemberIntegral->memberIntegralAdd('info_askfor_view',MEMBER_ID);
        if($res[0]==RESULT_ERROR) return $res;

        $updata=['isview'=>'1'];
        $result = $this->modelInfoAskfor->updateInfo($where,$updata);
        return $result ? [RESULT_SUCCESS, '操作成功',] : [RESULT_ERROR, $this->modelInfoAskfor->getError()];
    }

    /**
     * 报名查看
     * @param array $where
     * @return array
     */
    public function infoAskforFind($data = [])
    {
        $where['id']=['=',$data['id']];
        //找学员扣分
        $res=$this->logicMemberIntegral->memberIntegralAdd('info_askfor_find',MEMBER_ID);
        if($res[0]==RESULT_ERROR) return $res;

        //复制导入我的信息
        $intodata=$this->modelInfoAskfor->getInfo($where)->toArray();
        $intodata['member_id']=MEMBER_ID;
        $intodata['isview']=1;
        $intodata['create_time']=format_time();
        $intodata['update_time']=format_time();
        unset($intodata['id']);
        $result = $this->modelInfoAskfor->setInfo($intodata);

        //标记
        $updata=['find_member'=>$intodata['find_member'].','.MEMBER_ID];
        $result = $this->modelInfoAskfor->updateInfo($where,$updata);

        return $result ? [RESULT_SUCCESS, '信息导入我的学员，请到我学员信息中查看',] : [RESULT_ERROR, $this->modelInfoAskfor->getError()];
    }

}
