<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\cms\model;

use app\common\model\ModelBase;

/**
 * Admin基础模型
 */
class CmsBase extends ModelBase
{
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
