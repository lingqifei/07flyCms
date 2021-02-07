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

class Ads extends IndexBase
{

    public $aid = '';
    public $type = '';

    /**
     * 广告位调用
     *
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function v($aid = '')
    {
        $this->aid = input("param.aid", '0');
        if (!is_numeric($this->aid) || strval(intval($this->aid)) !== strval($this->aid)) {
            abort(404, 'aid页面不存在');
        }
        $this->aid = intval($this->aid);
        if (empty($this->aid)) {
            abort(404, 'aid 页面不存在');
            exit;
        } else {
            /**广告位处理**/
            $info = $this->logicAds->getAdsInfo(['id' => $this->aid]);
            if (empty($info)) {
                abort(404, 'info 页面不存在');
                exit;
            }

            //广告列表
            $list = $this->logicAdsList->getAdsListList(['ads_id' => $this->aid]);

            if (!empty($list['data'])) {

                $one = array_rand_value($list['data'], 1);

                if (!empty($one[0])) {

                    $ads = $one[0];

                    //更新点击
                    $this->logicAdsList->updateAdsListView(['id' => $ads['id']]);

                    $text = '';
                    switch ($ads['ads_type']) {
                        case '0':
                            $text = '<a href="' . $ads['links'] . '" ' . $ads['target'] . '><img src="' . $ads['litpic'] . '" width="' . $info['width'] . '" height="' . $info['height'] . '"></a>';
                            break;
                        case '1':
                            $text = '<a href="' . $ads['links'] . '" ' . $ads['target'] . '>' . $ads['intro'] . '</a>';
                            break;
                        case '2':
                            $text = $ads['intro'];
                            break;
                    }
                    if ($ads['set_time'] == 0) {
                        $adbody = $text;
                    } else {
                        $ntime = time();
                        if ($ntime > $ads['stop_time'] || $ntime < $ads['start_time']) {
                            $adbody = $ads['expintro'];
                        } else {
                            $adbody = $text;
                        }
                    }
                    $adbody = str_replace('"', '\"', $adbody);
                    $adbody = str_replace("\r", "\\r", $adbody);
                    $adbody = str_replace("\n", "\\n", $adbody);
                    $adbody = "<!--\r\ndocument.write(\"{$adbody}\");\r\n-->\r\n";
                }
                echo $adbody;
            }
        }
    }

    /**
     * 单广调用
     *
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function p($aid = '')
    {
        $this->aid = input("param.aid", '0');
        if (!is_numeric($this->aid) || strval(intval($this->aid)) !== strval($this->aid)) {
            abort(404, 'aid页面不存在');
        }
        $this->aid = intval($this->aid);
        if (empty($this->aid)) {
            abort(404, 'aid 页面不存在');
            exit;
        } else {
            /**广告位信息**/
            $info = $this->logicAdsList->getAdsListInfo(['id' => $this->aid]);
            if (empty($info)) {
                abort(404, 'info 页面不存在');
                exit;
            }

            $text = '';
            switch ($info['ads_type']) {
                case '0':
                    $text = '<a href="' . $info['links'] . '" ' . $info['target'] . '><img src="' . get_picture_url($info['litpic']) . '"></a>';
                    break;
                case '1':
                    $text = '<a href="' . $info['links'] . '" ' . $info['target'] . '>' . $info['intro'] . '</a>';
                    break;
                case '2':
                    $text = $info['intro'];
                    break;
            }
            //时间设置
            if ($info['set_time'] == 0) {
                $adbody = $text;
            } else {
                $ntime = time();
                if ($ntime > $info['stop_time'] || $ntime < $info['start_time']) {
                    $adbody = $info['expintro'];
                } else {
                    $adbody = $text;
                }
            }
            $adbody = str_replace('"', '\"', $adbody);
            $adbody = str_replace("\r", "\\r", $adbody);
            $adbody = str_replace("\n", "\\n", $adbody);
            $adbody = "<!--\r\ndocument.write(\"{$adbody}\");\r\n-->\r\n";
        }
        echo $adbody;

        //更新点击
        $this->logicAdsList->updateAdsListView(['id' => $info['id']]);

    }

}
