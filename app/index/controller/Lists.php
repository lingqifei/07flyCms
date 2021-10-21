<?php
/**
 * 零起飞07FLY-CMS
 * ============================================================================
 * 版权所有 2018-2028 成都零起飞科技有限公司，并保留所有权利。
 * 网站地址: http://www.07fly.com
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * Author: 开发人生 <goodkfrs@qq.com>
 * Date: 2021-01-01-3
 */

namespace app\index\controller;

use think\Controller;

class Lists extends IndexBase
{

    public $tid = '';
    public $type = '';

    /**
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function index($data = [])
    {

        $tid = input("param.tid/s", '');

        /*获取当前栏目ID以及模型ID*/
        $page_tmp = input('param.page/s', 0);

        if (empty($tid) || !is_numeric($page_tmp)) {
            abort(404, '页面不存在');
        }

        if (!is_numeric($tid) || strval(intval($tid)) !== strval($tid)) {
            $map = array('typedir' => $tid);
        } else {
            $map = array('id' => $tid);
        }

        $type = $this->logicArctype->getArctypeInfo($map);
		$type['title']=$type['typename'];
        if (empty($type)) {
            echo "tid错误~";
            //$this->redirect('/404.html');
            abort(404, '页面不存在');
            exit;
        }

        $rtnArray = array(
            'field' => $type,
            'param' => $this->param,//输出自带参数
        );

		/*模板文件*/
		$this->nid = $this->logicChannel->getChannelValue(['id' => $type['channel_id']], 'nid');
		$tpfile = 'lists_' . $this->nid;
		//判断栏目类型0=列表，1=封面
		if ($type['ispart'] == 0) {
			$tpfile = $type['temp_list'];
		} else if ($type['ispart'] == 1) {
			$tpfile = $type['temp_index'];
		}
        $viewfile = !empty($tpfile) ? strtolower($tpfile) : $tpfile;
        $this->typeinfo = $rtnArray;
        $this->assign('fly', $this->typeinfo);
        return $this->fetch($viewfile);
    }

    /**
     * 后台调用方法，可以配合路由配置
     * @return mixed
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/11/2 0002
     */
    public function adminindex()
    {
        return $this->index($this->param);
    }

}
