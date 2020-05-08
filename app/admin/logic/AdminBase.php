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

namespace app\admin\logic;

use app\common\logic\LogicBase;

/**
 * Admin基础逻辑
 */
class AdminBase extends LogicBase
{

    /**
     * 权限检测
     * url  当前访问的地址
     * url_list[] 当前授权地址数据
     */
    public function authCheck($url = '', $url_list = [])
    {

        $pass_data = [RESULT_SUCCESS, '权限检查通过'];
        $allow_url = config('allow_url');
        $allow_url_list = parse_config_attr($allow_url);

        if (IS_ROOT) {
            return $pass_data;
        }
        //放行配置允许通过的地址
        if (!empty($allow_url_list)) {
            foreach ($allow_url_list as $v) {
                if(!empty($v)){
                    if (strpos($url, strtolower($v)) !== false) {
                        return $pass_data;
                    }
                }
            }
        }

        //判断访问地址，是否存在授权地址数组中
        $result = in_array(strtolower($url), array_map("strtolower", $url_list)) ? true : false;

        !('index/index' == $url && !$result) ?: clear_login_session();

        return $result ? $pass_data : [RESULT_ERROR, '未授权操作,检查权限'];
    }

    /**
     * 获取过滤后的菜单树
     */
    public function getMenuTree($menu_list = [], $url_list = [])
    {

        foreach ($menu_list as $key => $menu_info) {

            list($status, $message) = $this->authCheck(strtolower($menu_info['url']), $url_list);

            [$message];
            //提取为菜单
            if ((!IS_ROOT && RESULT_ERROR == $status) || empty($menu_info['is_menu'])) {

                unset($menu_list[$key]);
            }
        }

        return $this->getListTree($menu_list);
    }

    /**
     * 获取列表树结构
     */
    public function getListTree($list = [])
    {

        if (is_object($list)) {

            $list = $list->toArray();
        }

        return list_to_tree(array_values($list), 'id', 'pid', 'child');
    }

    /**
     * 通过完整URL获取检查标准URL
     */
    public function getCheckUrl($full_url = '')
    {

        $temp_url = sr($full_url, URL_ROOT);

        $url_array_tmp = explode(SYS_DS_PROS, $temp_url);

        //获得真地址
        $subscript = DATA_NORMAL;
        !defined('BIND_MODULE') && $subscript++;
        $return_url = $url_array_tmp[$subscript] . SYS_DS_PROS . $url_array_tmp[++$subscript];

        //$return_url = $url_array_tmp[1] . SYS_DS_PROS . $url_array_tmp[2]. SYS_DS_PROS . $url_array_tmp[3];
        $index = strpos($return_url, '.');

        $index !== false && $return_url = substr($return_url, DATA_DISABLE, $index);

        return $return_url;
    }

    /**
     * 过滤页面内容权限地方，不存在权限直接过滤掉
     */
    public function filter($content = '', $url_list = [])
    {

        $results = [];

        preg_match_all('/<lqf_link>.*?[\s\S]*?<\/lqf_link>/', $content, $results);

        foreach ($results[0] as $a) {

            $match_results = [];

            preg_match_all('/data-url="(.+?)"|url="(.+?)"/', $a, $match_results);

            $full_url = '';

            if (empty($match_results[1][0]) && empty($match_results[2][0])) {
                continue;
            } elseif (!empty($match_results[1][0])) {
                $full_url = $match_results[1][0];
            } else {
                $full_url = $match_results[2][0];
            }

            //正则到内容在的地址，判断是否有权限
            if (!empty($full_url)) {
                $url=$this->getCheckUrl($full_url);
                $result = $this->authCheck($url, $url_list);
                $result[0] != RESULT_SUCCESS && $content = sr($content, $a,'<i class="text-danger fa fa-power-off"></i>');
            }
        }
        return $content;
    }

    /**
     * 获取首页数据
     */
    public function getIndexData()
    {

        $query = new \think\db\Query();
        $system_info_mysql = $query->query("select version() as v;");

        // 系统信息
        $data['lqf_version'] = SYS_VERSION;
        $data['think_version'] = THINK_VERSION;
        $data['os'] = PHP_OS;
        $data['software'] = $_SERVER['SERVER_SOFTWARE'];
        $data['mysql_version'] = $system_info_mysql[0]['v'];
        $data['upload_max'] = ini_get('upload_max_filesize');
        $data['php_version'] = PHP_VERSION;

        // 产品信息
        $data['product_name'] = '零起飞管理系统';
        $data['author'] = '零起飞';
        $data['website'] = 'www.07fly.top';
        $data['qun'] = '<a href="//shang.qq.com/wpa/qunwpa?idkey=b587b0c97d7a7e17b805c05f5c2e4aa1a2a16958edee01c2d5208ac675e6d4aa" target="_blank">575085787</a>';
        $data['document'] = '<a target="_blank" href="http://www.07fly.top">http://www.07fly.top</a>';

        return $data;
    }

    /**
     * 数据状态设置
     */
    public function setStatus($model = null, $param = null, $index = 'id')
    {

        if (empty($model) || empty($param)) {

            return [RESULT_ERROR, '非法操作'];
        }

        $status = (int)$param[DATA_STATUS_NAME];

        $model_str = LAYER_MODEL_NAME . $model;

        $obj = $this->$model_str;

        is_array($param['ids']) ? $ids = array_extract((array)$param['ids'], 'value') : $ids[] = (int)$param['ids'];

        $result = $obj->setFieldValue([$index => ['in', $ids]], DATA_STATUS_NAME, $status);

        $result && action_log('数据状态', '数据状态调整' . '，model：' . $model . '，ids：' . arr2str($ids) . '，status：' . $status);

        return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, $obj->getError()];
    }

    /**
     * 数据排序设置
     */
    public function setSort($model = null, $param = null)
    {

        $model_str = LAYER_MODEL_NAME . $model;

        $obj = $this->$model_str;

        $result = $obj->setFieldValue(['id' => (int)$param['id']], 'sort', (int)$param['value']);

        $result && action_log('数据排序', '数据排序调整' . '，model：' . $model . '，id：' . $param['id'] . '，value：' . $param['value']);

        return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, $obj->getError()];
    }

    /**
     * 数据设置
     */
    public function setField($model = null, $param = null)
    {
        $model_str = LAYER_MODEL_NAME . $model;

        $obj = $this->$model_str;

        $result = $obj->setFieldValue(['id' => (int)$param['id']], $param['name'], (int)$param['value']);

        $result && action_log('数据更新', '数据更新调整' . '，model：' . $model . '，id：' . $param['id'] . '，name：' . $param['name']. '，value：' . $param['value']);

        return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, $obj->getError()];
    }

}