<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Channelor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\index\logic;
use \think\Db;
/**
 * 频道栏目管理=》逻辑层
 */
class Archives extends IndexBase
{

    /**文章列表查询
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int $paginate
     * @return array
     * Author: lingqifei created by at 2020/2/27 0027
     */
    public function getArchivesList($where = [], $field = true, $order = '', $paginate = 15)
    {
        $this->modelArchives->alias('a');

        $list= $this->modelArchives->getList($where, $field, $order, $paginate)->toArray();

        $paginate===false && $list['data']=$list;

        foreach ($list['data'] as &$row){
            $row['litpic'] =get_picture_url($row['litpic']);
            $row['arcurl']=$this->getArchivesUrl($row);
        }
        return $list;
    }


    /**文章列表查询
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int $paginate
     * @return object 返回查询对像
     * Author: lingqifei created by at 2020/2/27 0027
     */
    public function getArchivesPageList($where = [], $field = true, $order = '', $paginate = 15)
    {
        $this->modelArchives->alias('a');
        $list= $this->modelArchives->getList($where, $field, $order, $paginate);
        return $list;
    }


    /**文章列表查询=》相关文章
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int $paginate
     * @return array
     * Author: lingqifei created by at 2020/2/27 0027
     */
    public function getArchiveslikeList($where = [], $field = true, $order = '', $paginate = false,$limit='')
    {
        $this->modelArchives->alias('a');

        if($limit) $this->modelArchives->limit=$limit;

        $list= $this->modelArchives->getList($where, $field, $order, false)->toArray();

        $paginate===false && $list['data']=$list;

        foreach ($list['data'] as &$row){
            $row['litpic'] =get_picture_url($row['litpic']);
            $row['arcurl']=$this->getArchivesUrl($row);
        }
        return $list;
    }



    /**转换一条文章的实际地址
     * @param array $data
     * @return mixed|string
     * Author: lingqifei created by at 2020/2/27 0027
     */
    public function getArchivesUrl($data=[])
    {
        if ($data['is_jump'] == 1 && $data['jump_url']) {
            $arcurl=$data['jump_url'];
        } else {
            $arcurl = url('index/view/index', array('aid'=>$data['id']));
        }
        return $arcurl;
    }


    /**获取文章详细
     * @param array $data
     * @return mixed|string
     * Author: lingqifei created by at 2020/2/27 0027
     */
    public function getArchivesInfo($where = [], $field = true)
    {
        $info=$this->modelArchives->getInfo($where, $field);
        if($info){
            is_object($info)&& $info=$info->ToArray();
            $info['arcurl']=$this->getArchivesUrl($info);//加载链接地址
            $addtable=$this->modelChannel->getValue(['id'=>$info['channel_id']],'addtable');
            $ext_info=Db::table($addtable)->where('id',$info['id'])->find();
            if($ext_info){
                return array_merge($info,$ext_info);
            }else{
                Db::table($addtable)->insert(['id'=>$info['id']]);
                return $info;
            }
        }
    }


    /**获取文档下一条
     * @param $aid
     * @return mixed|string
     * Author: lingqifei created by at 2020/3/18 0018
     */
    public function getArchivesNext($aid,$channelid,$typeid)
    {
        $map['id']=['gt',$aid];
        $map['channel_id']=['=',$channelid];
        $map['type_id']=['=',$typeid];
        $this->modelArchives->limit=1;
        $list= $this->modelArchives->getList($map, '', 'id asc', false)->toArray();

        if($list){
            $id=$list[0]['id'];
            return $this->getArchivesInfo(['id'=>$id]);
        }else{
            return '';
        }

    }

    /**获取文档上一条
     * @param $aid
     * @return mixed|string
     * Author: lingqifei created by at 2020/3/18 0018
     */
    public function getArchivesPre($aid,$channelid,$typeid)
    {

        $map['id']=['lt',$aid];
        $map['channel_id']=['=',$channelid];
        $map['type_id']=['=',$typeid];
        $this->modelArchives->limit=1;
        $list= $this->modelArchives->getList($map, '', 'id desc', false)->toArray();

        if($list){
            $id=$list[0]['id'];
            return $this->getArchivesInfo(['id'=>$id]);
        }else{
            return '';
        }

    }


    /**获取文章=字段信息
     * @param array $data
     * @return mixed|string
     * Author: lingqifei created by at 2020/2/27 0027
     */
    public function getFieldValue($where = [], $field = true)
    {
        $info=$this->modelArchives->getValue($where, $field);

        return  $info;

    }



    /**排序条件组合
     * @param $orderby
     * @param $orderWay
     * @param bool $isrand
     * @return string
     * Author: lingqifei created by at 2020/2/27 0027
     */
    public function getOrderBy($orderby, $orderWay, $isrand=false){
        switch ($orderby) {
            case 'hot':
            case 'click':
                $orderby = "a.click {$orderWay}";
                break;
            case 'id':
                $orderby = "a.id {$orderWay}";
                break;

            case 'now':
            case 'new': // 兼容织梦的写法
            case 'pubdate': // 兼容织梦的写法
                $orderby = "a.pubdate {$orderWay}";
                break;
            case 'create_time':
                $orderby = "a.create_time {$orderWay}";
                break;
            case 'update_time':
                $orderby = "a.update_time {$orderWay}";
                break;
            case 'sortrank': // 兼容织梦的写法
            case 'sort':
                $orderby = "a.sort {$orderWay}";
                break;
            case 'rand':
                if (true === $isrand) {
                    $orderby = "rand()";
                } else {
                    $orderby = "a.aid {$orderWay}";
                }
                break;

            default:
            {
                $orderby = "a.create_time  desc";
                break;
            }
        }
        return $orderby;
    }


}
