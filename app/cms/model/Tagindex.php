<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */
namespace app\cms\model;

/**
 * 标签索引=》模型
 */
class Tagindex extends CmsBase
{
    /**
     * 文章标签添加接口，针对文档编辑关键字时调用
     * @param $keywords
     * @param $aid
     * @param $typeid
     * @return array
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/11/3 0003
     */
    public function tagindexAddArchives($keywords, $aid, $typeid)
    {
        $tagindex_list=$this->modelTagindex->getColumn('','tag','id');
        $keywords=preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)/" ,',' ,$keywords);
        $key_array=str2arr($keywords);
        foreach ($key_array as $onetag){
            if(in_array($onetag,$tagindex_list)){
                $tagid=array_search($onetag,$tagindex_list);
            }else{
                $tagid=$this->modelTagindex->setInfo(['tag'=>$onetag,'typeid'=>$typeid]);
            }
            $taglistdata[]=[
                'aid'=>$aid,
                'tid'=>$tagid,
                'typeid'=>$typeid,
                'tag'=>$onetag,
            ];
        }
        $this->modelTaglist->deleteInfo(['aid'=>$aid],true);//删除当前文章原有标签
        $this->modelTaglist->setList($taglistdata);//批量添加当前文章标签
    }
}
