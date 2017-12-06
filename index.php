<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<title>抽奖</title>
</head>
<body>

</body>
<?php

	// 公众号 APPID 和 APPSECRET
	define('APPID', '');
	define('APPSECRET', '');


	function recordErr($errMsg){
		$log = 'logs/err.txt';
		file_put_contents($log, date ('Y-m-d H:i:s', time()) .': '. $result->err_msg . "\n", FILE_APPEND);
	}


	if(!isset($_GET['code']) || empty($_GET['code'])){
		recordErr('no code');
		exit;
	}

	if(!isset($_GET['state']) || empty($_GET['state'])){
		recordErr('no merchantID');
		exit;
	}


	require 'GetOpenID.class.php';
	$getOpenID = new GetOpenID($_GET['code'], APPID, APPSECRET);
	$result = $getOpenID->getOpenID();
	if($result->err_code){
		recordErr($result->err_msg)
		exit;
	}
	$openID = $result->open_id;

?>
<script>
var sOpenID = '<?php echo $openID; ?>';
var sMerchantID = '<?php echo $_GET['state']; ?>';
</script>
</html>
