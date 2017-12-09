<meta charset="utf-8">
<?php

	function receiveErrLog($sErr, $id=''){
		file_put_contents('logs/receive/' . ($id?$id:'common') . '.txt'
							, date('Y-m-d H:i:s', time()) .' -- '. $id .' -- '. $sErr . "\r\n"
							, FILE_APPEND);
	}




	// 没带merchantID参数
	if(!isset($_GET['state']) || strlen($_GET['state'])!==32){
		echo '<h1>地址错误</h1>';
		exit;
	}


	// 没有收到code
	if( !isset($_GET['code']) || empty($_GET['code']) ){
		echo '<h1>系统错误，请稍后再试。</h1>';
		receiveErrLog('回调错误', $_GET['state']);
		exit;
	}


	// 请求OpenID
	// require 'config_ignore.php';
	// require 'GetOpenID.class.php';
    //
	// $getOpenID = new GetOpenID($_GET['code'], APPID, APPSECRET);
	// $getOpenIDResult = $getOpenID->getOpenID();
    //
	// if($getOpenIDResult->err_code){
	// 	echo '<h1>系统错误，请稍后再试。</h1>';
	// 	receiveErrLog('获取OpenID错误：' . $getOpenIDResult->err_msg);
	// 	exit;
	// }
	// else{
	// 	$openID = $getOpenIDResult->open_id;
	// }

	$openID = 666;


	// 连接数据库
	require_once('MySQLiController/MySQLiController.class.php');
	$MySQLiController = new MySQLiController( $dbr );


	// 查询该merchantID
	$getMerchantIDResult = $MySQLiController->getRow('merchants', 'merchantID="'.$_GET['state']. '"' );

	if(!is_array($getMerchantIDResult)){ // 查询merchantID出错
		echo '<h1>系统错误，请稍后再试。</h1>';
		receiveErrLog('查询商户错误：' . sErr, $_GET['state']);
		exit;
	}

	if(!count($getMerchantIDResult)){ // 没有该商户
		echo '<h1>地址错误</h1>';
		exit;
	}


	// 查询该OpenID
	$tableName = 'winners';
	$where = 'merchantID="'.$_GET['state']. '" AND openid="' .$openID. '"';
	$queryOpenIDResult = $MySQLiController->getRow($tableName, $where);

	if(!is_array($queryOpenIDResult)){ // 查询OpenID出错
		echo '<h1>系统错误，请稍后再试。</h1>';
		receiveErrLog('查询OpenID错误：' . sErr, $_GET['state']);
	}


	// 兑奖和显示已兑奖
	if(count($queryOpenIDResult)){ // 中奖
		$time = $queryOpenIDResult[0]['receivedTime'];
		if($time==0){
			$time = time();
			$updateResult = $MySQLiController->updateData($tableName, array('receivedTime'), array($time), $where);
			if($updateResult===true){
				echo '<h1>' . $queryOpenIDResult[0]['prizeName'] . ' 兑奖成功。<br />兑奖时间: ' . date('Y-m-d H:i:s', $time) . '</h1>';
			}
		}
		else{
			echo '<h1>' . $queryOpenIDResult[0]['prizeName'] . ' 已兑奖。<br />兑奖时间: ' . date('Y-m-d H:i:s', $time) . '</h1>';
		}
	}
	else{
		echo '<h1>没有你的中奖记录</h1>';
	}


	$dbr->close();


?>
