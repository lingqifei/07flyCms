<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * MemberRealnameor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\portalmember\logic;

/**
 * 会员等级升级管理=》逻辑层
 */
class Login extends MemberBase
{
    /**
     * 登录处理
     */
    public function loginHandle($username = '', $password = '', $verify = '')
    {

        //$validate_result = $this->validateLogin->scene('login')->check(compact('username', 'password', 'verify'));
        $validate_result = $this->validateLogin->scene('portalmemberlogin')->check(compact('username', 'password'));
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateLogin->getError()];
        }

        $user = $this->modelMember->getInfo(['username' => $username]);
//echo data_md5_key($password) ;exit;
        if (!empty($user['password']) && data_md5_key($password) == $user['password']) {

            $this->modelMember->setFieldValue(['id' => $user['id']], 'last_login', TIME_NOW);
            $auth = ['member_id' => $user['id'], TIME_UT_NAME => TIME_NOW];

            session('member_info', $user);
            session('member_auth', $auth);
            session('member_auth_sign', data_auth_sign($auth));

            //登录积分
            $this->logicMemberIntegral->memberIntegralAdd('member_login',$user['id']);

            action_log('登录', '登录操作，username：' . $username);
            return [RESULT_SUCCESS, '登录成功', url('portalmember/index/index')];

        } else {
            $error = empty($user['id']) ? '用户账号不存在' : '密码输入错误';
            return [RESULT_ERROR, $error];
        }
    }

    /**
     * 注销当前用户
     */
    public function logout()
    {
        clear_member_login_session();
        return [RESULT_SUCCESS, '注销成功', url('portalmember/Login/login')];
    }

    /**
     * 清理缓存
     */
    public function clearCache()
    {
        \think\Cache::clear();
        return [RESULT_SUCCESS, '清理成功', url('portalmember/Login/login')];
    }

}
