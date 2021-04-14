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

use app\common\logic\LogicBase;
use app\api\error\CodeBase;

/**
 * Api基础逻辑
 */
class ApiBase extends LogicBase
{

	/**
	 * API返回数据
	 */
	public function apiReturn($code_data = [], $return_data = [], $return_type = 'json')
	{

		if (is_array($code_data) && array_key_exists(API_CODE_NAME, $code_data)) {

			!empty($return_data) && $code_data['data'] = $return_data;

			$result = $code_data;

		} else {

			$result = CodeBase::$success;

			$result['data'] = $code_data;
		}

		$return_result = $this->checkDataSign($result);

		$return_result['exe_time'] = debug('api_begin', 'api_end');

		return $return_type == 'json' ? json($return_result) : $return_result;
	}

	/**
	 * 检查是否需要响应数据签名
	 */
	public function checkDataSign($data)
	{

		$info = $this->modelApi->getInfo(['api_url' => URL]);

		$info['is_response_sign'] && !empty($data['data']) && $data['data']['data_sign'] = create_sign_filter($data['data']);

		return $data;
	}

	/**
	 * API错误终止程序
	 */
	public function apiError($code_data = [])
	{
		return throw_response_exception($code_data);
	}

	/**
	 * API提交附加参数检查
	 */
	public function checkParam($param = [])
	{

		$info = $this->modelApi->getInfo(['api_url' => URL]);

		empty($info) && $this->apiError(CodeBase::$apiUrlError);

		(empty($param['access_token']) || $param['access_token'] != get_access_token()) && $this->apiError(CodeBase::$accessTokenError);

		if ($info['is_user_token'] && empty($param['user_token'])) {

			$this->apiError(CodeBase::$userTokenNull);

		} elseif ($info['is_user_token']) {

			$decoded_user_token = decoded_user_token($param['user_token']);

			is_string($decoded_user_token) && $this->apiError(CodeBase::$userTokenError);
		}

		$info['is_request_sign'] && (empty($param['data_sign']) || create_sign_filter($param) != $param['data_sign']) && $this->apiError(CodeBase::$dataSignError);
	}

	/**
	 * API提交解析user_token
	 */
	public function checkUserTockeParam($param = [])
	{

		if (empty($param['user_token'])) {

			$this->apiError(CodeBase::$userTokenNull);

		} else {

			$decoded_user_token = decoded_user_token($param['user_token']);

			is_string($decoded_user_token) && $this->apiError(CodeBase::$userTokenError);
		}

		return $decoded_user_token;
	}



}
