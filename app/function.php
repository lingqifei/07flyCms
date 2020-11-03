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

// 扩展函数文件，系统研发过程中需要的函数建议放在此处，与框架相关函数分离
/**
 * 时间计算函数
 * @param int $time
 * @param int $caclVal  增加、减少的值
 * @param int $type  计算时间类型
 * @return string 完整的时间显示
 */
function date_calc($time = null, $caclVal="0", $type="day", $format = 'Y-m-d')
{
    if (null === $time) {
        $time = TIME_NOW;
    }
    return date($format,strtotime (" $caclVal $type", strtotime($time)));

}

/**
 * 获取两个日期之间所有日期
 * @param int $startDate 开始时间
 * @param int $endDate  结束时间
 * @return string 完整的时间显示
 */
function getDatesBetweenTwoDays($startDate, $endDate)
{
    $dates = [];
    if (strtotime($startDate) > strtotime($endDate)) {
        //如果开始日期大于结束日期，直接return 防止下面的循环出现死循环
        return $dates;
    } elseif ($startDate == $endDate) {
        //开始日期与结束日期是同一天时
        array_push($dates, $startDate);
        return $dates;
    } else {
        array_push($dates, $startDate);
        $currentDate = $startDate;
        do {
            $nextDate = date('Y-m-d', strtotime($currentDate . ' +1 days'));
            array_push($dates, $nextDate);
            $currentDate = $nextDate;
        } while ($endDate != $currentDate);
        return $dates;
    }
}

/**
 * 时间计算函数
 * @param int $time
 * @param int $caclVal  增加、减少的值
 * @param int $type  计算时间类型
 * @return string 完整的时间显示
 */
function date_to_day($dates = [])
{
    $days=[];
    foreach ($dates as $date){
        $days[]=date("d",strtotime($date));
    }

    return $days;
}


if (!function_exists('msubstr'))
{
    /**
     * 字符串截取，支持中文和其他编码
     *
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $suffix 截断显示字符
     * @param string $charset 编码格式
     * @return string
     */
    function msubstr($str='', $start=0, $length=NULL, $suffix=false, $charset="utf-8") {
        if(function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
            if(false === $slice) {
                $slice = '';
            }
        }else{
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }

        $str_len = strlen($str); // 原字符串长度
        $slice_len = strlen($slice); // 截取字符串的长度
        if ($slice_len < $str_len) {
            $slice = $suffix ? $slice.'...' : $slice;
        }
        return $slice;
    }
}

if (!function_exists('html_msubstr'))
{
    /**
     * 截取内容清除html之后的字符串长度，支持中文和其他编码
     *
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $suffix 截断显示字符
     * @param string $charset 编码格式
     * @return string
     */
    function html_msubstr($str='', $start=0, $length=NULL, $suffix=false, $charset="utf-8") {
        $str = htmlspecialchars_decode($str);
        $str = checkStrHtml($str);
        return msubstr($str, $start, $length, $suffix, $charset);
    }
}

if (!function_exists('text_msubstr'))
{
    /**
     * 针对多语言截取，其他语言的截取是中文语言的2倍长度
     *
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $suffix 截断显示字符
     * @param string $charset 编码格式
     * @return string
     */
    function text_msubstr($str='', $start=0, $length=NULL, $suffix=false, $charset="utf-8") {
        return msubstr($str, $start, $length, $suffix, $charset);
    }
}

if (!function_exists('htmlspecialchars_decode'))
{
    /**
     * 自定义只针对htmlspecialchars编码过的字符串进行解码
     *
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $suffix 截断显示字符
     * @param string $charset 编码格式
     * @return string
     */
    function htmlspecialchars_decode($str='') {
        if (is_string($str) && stripos($str, '&lt;') !== false && stripos($str, '&gt;') !== false) {
            $str = htmlspecialchars_decode($str);
        }
        return $str;
    }
}

if (!function_exists('checkStrHtml'))
{
    /**
     * 过滤Html标签
     *
     * @param     string  $string  内容
     * @return    string
     */
    function checkStrHtml($string){
        $string = trim_space($string);

        if(is_numeric($string)) return $string;
        if(!isset($string) or empty($string)) return '';

        $string = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','',$string);
        $string  = ($string);

        $string = strip_tags($string,""); //清除HTML如<br />等代码
        // $string = str_replace("\n", "", str_replace(" ", "", $string));//去掉空格和换行
        $string = str_replace("\n", "", $string);//去掉空格和换行
        $string = str_replace("\t","",$string); //去掉制表符号
        $string = str_replace(PHP_EOL,"",$string); //去掉回车换行符号
        $string = str_replace("\r","",$string); //去掉回车
        $string = str_replace("'","‘",$string); //替换单引号
        $string = str_replace("&amp;","&",$string);
        $string = str_replace("=★","",$string);
        $string = str_replace("★=","",$string);
        $string = str_replace("★","",$string);
        $string = str_replace("☆","",$string);
        $string = str_replace("√","",$string);
        $string = str_replace("±","",$string);
        $string = str_replace("‖","",$string);
        $string = str_replace("×","",$string);
        $string = str_replace("∏","",$string);
        $string = str_replace("∷","",$string);
        $string = str_replace("⊥","",$string);
        $string = str_replace("∠","",$string);
        $string = str_replace("⊙","",$string);
        $string = str_replace("≈","",$string);
        $string = str_replace("≤","",$string);
        $string = str_replace("≥","",$string);
        $string = str_replace("∞","",$string);
        $string = str_replace("∵","",$string);
        $string = str_replace("♂","",$string);
        $string = str_replace("♀","",$string);
        $string = str_replace("°","",$string);
        $string = str_replace("¤","",$string);
        $string = str_replace("◎","",$string);
        $string = str_replace("◇","",$string);
        $string = str_replace("◆","",$string);
        $string = str_replace("→","",$string);
        $string = str_replace("←","",$string);
        $string = str_replace("↑","",$string);
        $string = str_replace("↓","",$string);
        $string = str_replace("▲","",$string);
        $string = str_replace("▼","",$string);

        // --过滤微信表情
        $string = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return '';}, $string);

        return $string;
    }
}



if (!function_exists('trim_space'))
{
    /**
     * 过滤前后空格等多种字符
     *
     * @param string $str 字符串
     * @param array $arr 特殊字符的数组集合
     * @return string
     */
    function trim_space($str, $arr = array())
    {
        if (empty($arr)) {
            $arr = array(' ', '　');
        }
        foreach ($arr as $key => $val) {
            $str = preg_replace('/(^'.$val.')|('.$val.'$)/', '', $str);
        }

        return $str;
    }
}

if (!function_exists('func_preg_replace'))
{
    /**
     * 替换指定的符号
     *
     * @param array $arr 特殊字符的数组集合
     * @param string $replacement 符号
     * @param string $str 字符串
     * @return string
     */
    function func_preg_replace($arr = array(), $replacement = ',', $str = '')
    {
        if (empty($arr)) {
            $arr = array('，');
        }
        foreach ($arr as $key => $val) {
            $str = preg_replace('/('.$val.')/', $replacement, $str);
        }

        return $str;
    }
}


if (!function_exists('is_http_url'))
{
    /**
     * 判断url是否完整的链接
     *
     * @param  string $url 网址
     * @return boolean
     */
    function is_http_url($url)
    {
        // preg_match("/^(http:|https:|ftp:|svn:)?(\/\/).*$/", $url, $match);
        preg_match("/^((\w)*:)?(\/\/).*$/", $url, $match);
        if (empty($match)) {
            return false;
        } else {
            return true;
        }
    }
}


if (!function_exists('cut_str'))
{

    /**字符串按符号截取
     * $str='123/456/789/abc';
    示例：
    echo cut_str($str,'/',0); //输出 123
    echo cut_str($str,'/',2); //输出 789
    echo cut_str($str,'/',-1);//输出 abc
    echo cut_str($str,'/',-3);//输出 456
     * @param $str
     * @param $sign
     * @param $number
     * @return string
     *
     * Author: lingqifei created by at 2020/2/29 0029
     */
    function cut_str($str, $sign, $number){
        $array=explode($sign, $str);
        $length=count($array);
        if($number<0){
            $new_array=array_reverse($array);
            $abs_number=abs($number);
            if($abs_number>$length){
                return 'error';
            }else{
                return $new_array[$abs_number-1];
            }
        }else{
            if($number>=$length){
                return 'error';
            }else{
                return $array[$number];
            }
        }
    }
}

if (!function_exists('download')) {

    /**
     * 文件下载函数
     * Author: lingqifei created by at 2020/6/4 0004
     */
    function download($filepath,$filename='downfile.zip')
    {
        // 检查文件是否存在
        if (!file_exists($filepath)) {
            $this->error('文件未找到');
        } else {
            // 打开文件
            $file1 = fopen($filepath, "r");
            // 输入文件标签
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length:" . filesize($filepath));
            Header("Content-Disposition: attachment;filename=" . $filename);
            ob_clean();     // 重点！！！
            flush();        // 重点！！！！可以清除文件中多余的路径名以及解决乱码的问题：
            //输出文件内容
            //读取文件内容并直接输出到浏览器
            echo fread($file1, filesize($filepath));
            fclose($file1);
            exit();
        }
    }
}


if (!function_exists("list2select")) {

    /**r把列表数据转为树形下拉
     * @param $list
     * @param int $pId
     * @param int $level
     * @param string $pk
     * @param string $pidk
     * @param string $name
     * @return array|string
     * Author: lingqifei created by at 2020/4/1 0001
     */
    function list2select($list, $pId = 0, $level = 0, $pk = 'id', $pidk = 'pid', $name = 'name',$data=[])
    {
        foreach ($list as $k => $v) {
            $v['treename'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . '|--' . $v[$name];
            if ($v[$pidk] == $pId) { //父亲找到儿子
                $data[] =$v;
                $data   = list2select($list, $v[$pk], $level + 1, $pk, $pidk, $name,$data);
            }
        }
        return $data;
    }
}

if (!function_exists('get_arr_column')) {
    /**
     * 获取数组中的某一列
     *
     * @param array $arr 数组
     * @param string $key_name 列名
     * @return array  返回那一列的数组
     */
    function get_arr_column($arr, $key_name)
    {
        if (function_exists('array_column')) {
            return array_column($arr, $key_name);
        }

        $arr2 = array();
        foreach ($arr as $key => $val) {
            $arr2[] = $val[$key_name];
        }
        return $arr2;
    }
}