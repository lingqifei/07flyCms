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


use \Firebase\JWT\JWT;

// 解密user_token
function decoded_user_token($token = '')
{
	vendor('Firebase/php-jwt/src/JWT');
    try {
        
        $decoded = JWT::decode($token, API_KEY . JWT_KEY, array('HS256'));

        return (array) $decoded;
        
    } catch (Exception $ex) {
        
        return $ex->getMessage();
    }
}

// 获取解密信息中的data
function get_member_by_token($token = '')
{
    
    $result = decoded_user_token($token);

    return $result['data'];
}

// 数据验签时数据字段过滤
function sign_field_filter($data = [])
{
    
    $data_sign_filter_field_array = config('data_sign_filter_field');
    
    foreach ($data_sign_filter_field_array as $v)
    {
        
        if (array_key_exists($v, $data)) {
            
            unset($data[$v]);
        }
    }
    
    return $data;
}

// 过滤后的数据生成数据签名
function create_sign_filter($data = [], $key = '')
{
    
    $filter_data = sign_field_filter($data);
    
    return empty($key) ? data_md5_key($filter_data, API_KEY) : data_md5_key($filter_data, $key);
}
