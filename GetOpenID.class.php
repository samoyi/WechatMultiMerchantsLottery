<?php
// 获取类型为 snsapi_base   接口地址 https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140842
	class GetOpenID
	{

		private $code;
		private $appID;
		private $appSecret;

		public function __construct($code, $appID, $appSecret){
			$this->code = $code;
			$this->appID = $appID;
			$this->appSecret = $appSecret;
		}

		private function httpGet($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			if( $curlErr = curl_error($ch) ){
				$result = array(
					'err_code'=> 1,
					'curl_err'=> $curlErr
				);
				return $result;
			}
			curl_close($ch);
			return array(
				'err_code'=> 0,
				'res'=> $output
			);
		}

		public function getOpenID(){

			$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' .$this->code. '&secret=' .$this->appSecret. '&code=' . $this->code . '&grant_type=authorization_code';
			$result = this->httpGet($url);
			if($result->err_code){
				return array(
					'err_code'=> 1,
					'err_msg'=> $result->curl_err
				);
			}
			$res = json_decode($result->res);
			if($res->errcode){
				return array(
					'err_code'=> $res->errcode,
					'err_msg'=> $res->errmsg;
				);
			}
			return array(
				'err_code'=> 0,
				'open_id'=> $res->openid;
			);
		}
	}

?>
