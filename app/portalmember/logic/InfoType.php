<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Infoor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\portalmember\logic;

use think\Db;

/**
 * 信息分类管理=》逻辑层
 */
class InfoType extends MemberBase
{
    /**
     * 信息分类列表
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int|mixed $paginate
     * @return
     */
    public function getInfoTypeList($where = [], $field = '*', $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list= $this->modelInfoType->getList($where, $field, $order, $paginate);
        return $list;
    }

    /**
     * 组合下拉框选项信息
     */
    public function combineOptions($id = 0, $list = [], $default_option_text = '')
    {

        $data = "<option value =''>$default_option_text</option>";

        foreach ($list as $vo)
        {
            $data .= "<option ";

            if ($id == $vo['id']) : $data .= " selected "; endif;

            $data .= " value ='" . $vo['id'] . "'>" . $vo['typename'] . "</option>";
        }
        return $data;
    }

    /**
     * 信息分类列表=>id=key name=value
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int|mixed $paginate
     * @return array
     */
    public function getInfoTypeListName($where = [])
    {
        $cache_key = 'cache_info_type_' . md5(serialize($where));
        $cache_list = cache($cache_key);
        if (!empty($cache_list)){
            $list=$cache_list;
        }else{
            $list = Db::name('info_type')->where($where)->field(true)->select();
            !empty($list) && cache($cache_key, $list);
        }
        $id = array_column($list, 'id');
        $name = array_column($list, 'typename');
        $namelist = array_combine($id, $name);
        $namelist['0']='';
        return $namelist;
    }

}
