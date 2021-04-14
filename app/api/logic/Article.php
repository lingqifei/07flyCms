<?php
/**
 * 零起飞-(07FLY-ERP)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * AuthDomainor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\api\logic;

use app\common\logic\Article as CommonArticle;

/**
 * 文章接口逻辑
 */
class Article extends ApiBase
{
    
    public static $commonArticleLogic = null;
    
    /**
     * 基类初始化
     */
    public function __construct()
    {
        // 执行父类构造方法
        parent::__construct();
        
        empty(static::$commonArticleLogic) && static::$commonArticleLogic = get_sington_object('Article', CommonArticle::class);
    }
    
    /**
     * 获取文章分类列表
     */
    public function getArticleCategoryList()
    {
        
        return static::$commonArticleLogic->getArticleCategoryList([], 'id,name', 'id desc', false);
    }
    
    /**
     * 获取文章列表
     */
    public function getArticleList($data = [])
    {
        
        $where = [];
        
        !empty($data['category_id']) && $where['a.category_id'] = $data['category_id'];
        
        return static::$commonArticleLogic->getArticleList($where, 'a.id,a.name,a.category_id,a.describe,a.create_time', 'a.create_time desc');
    }
    
    /**
     * 获取文章信息
     */
    public function getArticleInfo($data = [])
    {
        
        return static::$commonArticleLogic->getArticleInfo(['a.id' => $data['article_id']], 'a.*,m.nickname,c.name as category_name');
    }
}
