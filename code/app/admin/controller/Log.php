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

namespace app\admin\controller;

/**
 * 行为日志控制器
 */
class Log extends AdminBase
{
    
    /**
     * 日志列表
     */
    public function show()
    {
        
        $this->assign('list', $this->logicLog->getLogList());
        
        return $this->fetch('show');
    }

    /**
     * 日志列表
     */
    public function show_json()
    {
        $where = "";
        if(!empty($this->param['keywords'])){
            $where['name|username|ip|url|describe']=['like','%'.$this->param['keywords'].'%'];
        }
       return $this->logicLog->getLogList($where);

    }
  
    /**
     * 日志删除
     */
    public function del($id = 0)
    {
        
        $this->jump($this->logicLog->logDel(['id' => $id]));
    }
  
    /**
     * 日志清空
     */
    public function clear()
    {
        $where['id']=['>','0'];
        $this->jump($this->logicLog->logDel($where));
    }
}
