<?php
/*
*
* cms.Archives  内容发布系统-频道模型
*
* =========================================================
* 零起飞网络 - 专注于网站建设服务和行业系统开发
* 以质量求生存，以服务谋发展，以信誉创品牌 !
* ----------------------------------------------
* @copyright	Copyright (C) 2017-2021 07FLY Network Technology Co,LTD.
* @license    For licensing, see LICENSE.html or http://www.07fly.xyz/crm/license
* @author ：kfrs <goodkfrs@QQ.com> 574249366
* @version ：1.0
* @link ：http://www.07fly.xyz
*/

namespace app\portalmember\controller;


/**
 * 信息管理=》首页
 */
class Info extends MemberBaseAuth
{

    /**
     * 登录
     */
    public function show()
    {

        $where['member_id'] = ['=', $this->member['id']];

        if (!empty($this->param['class'])) {
            $this->assign('class', $this->param['class']);
            $class = $this->param['class'];
        } else {
            $this->assign('class', 'all');
            $class = 'all';
        }

        switch ($class) {
            case 'noaudit':
                $where['status'] = ['=', '0'];
                break;
            case 'reject':
                $where['status'] = ['=', '2'];
                break;
            case 'istop':
                $where['istop'] = ['=', '2'];
                break;
            case 'nopay':
                $where['istop'] = ['=', '1'];
                break;
        }
        $list = $this->logicInfo->getInfoList($where);
        $pages = $list->render('info,pre,next,pageno', DB_LIST_ROWS);
        $this->assign('pages', $pages);
        $this->assign('list', $list);

        return $this->fetch('info_show');
    }


    /**
     * 上传
     */
    public function add()
    {
        IS_POST && $this->jump($this->logicInfo->infoAdd($this->param));
        $company = $this->logicMemberCompany->getMemberCompanyInfoAdd(MEMBER_ID);
        $this->assign('company', $company);
        return $this->fetch('info_add');
    }

    /**
     * 上传图片
     */
    public function edit()
    {
        IS_POST && $this->jump($this->logicInfo->infoEdit($this->param));

        $company = $this->logicMemberCompany->getMemberCompanyInfoAdd(MEMBER_ID);
        $this->assign('company', $company);
        $info = $this->logicInfo->getInfoInfo(['id' => $this->param['id']]);
        $this->assign('info', $info);
        return $this->fetch('info_edit');
    }

    /**
     * 删除
     */
    public function del()
    {
        $this->jump($this->logicInfo->infoDel($this->param));
    }

    /**
     * 刷新
     */
    public function refresh()
    {
        IS_GET && $this->jump($this->logicInfo->infoRefresh($this->param));
    }

    /**
     * 推广置顶
     */
    public function istop()
    {
        IS_POST && $this->jump($this->logicInfo->infoIstop($this->param));
        $map['id'] = $this->param['id'];
        $info = $this->logicInfo->getInfoInfo($map);
        $this->assign('info', $info);
        return $this->fetch('info_istop');
    }
}

?>