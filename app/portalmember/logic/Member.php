<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Memberor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\portalmember\logic;

/**
 * 会员列表管理=》逻辑层
 */
class Member extends MemberBase
{

    /**
     * 会员列表
     * @param array $data
     * @return array
     */
    public function memberEdit($data = [])
    {
        $url = url('index/index');
        $result = $this->modelMember->updateInfo(['id' => MEMBER_ID], $data);
        return $result ? [RESULT_SUCCESS, '编辑成功', $url] : [RESULT_ERROR, $this->modelMember->getError()];
    }

    /**会员列表信息
     * @param array $where
     * @param bool $field
     * @return
     */
    public function getMemberInfo($where = [], $field = true)
    {
        $info = $this->modelMember->getInfo($where, $field);
        $info['province_name'] = $this->logicRegion->getRegionListName($info['province_id']);
        $info['city_name'] = $this->logicRegion->getRegionListName($info['city_id']);
        $info['county_name'] = $this->logicRegion->getRegionListName($info['county_id']);
        $info['level'] = $this->modelMemberLevel->getInfo(['id'=>$info['level_id']]);
        return $info;
    }


    /**会员列表信息
     * @param array $where
     * @param bool $field
     * @return
     */
    public function chkMemberIntegral($integral='0')
    {
        $member_integral = $this->modelMember->getValue(['id'=>MEMBER_ID], 'member_integral');
        if($member_integral>=$integral){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 会员洽谈代码
     *
     * @param array $where
     * @param bool $field
     * @return
     */
    public function memberTalkcodeEdit($data = [])
    {
        if (empty($data['id'])) {
            return [RESULT_ERROR, '请输入参数哟~~'];
            exit;
        }
        $info = $this->modelMember->getInfo(['id' => $data['id']], 'level_id');
        if (!empty($info)) {
            if ($info['level_id'] > 0) {
                $result = $this->modelMember->updateInfo(['id' => MEMBER_ID], $data);
                return $result ? [RESULT_SUCCESS, '修改成功'] : [RESULT_ERROR, $this->modelMember->getError()];
            } else {
                return [RESULT_ERROR, '请您帐号未开通此项目功能~~'];
                exit;
            }
        }
    }

}
