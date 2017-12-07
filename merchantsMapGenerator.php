<meta charset="utf-8">
<pre>
<?php






	$lotteryRules = json_decode(file_get_contents('lotteryRules.json'), true);




	// 下面的代码不用动 ---------------------------------------------------------
	require 'config.php'; // 获取salt
	
	$merchants = array_keys($lotteryRules);

	$aMD5 = array();
	foreach($merchants as $item){
		$aMD5[$item] = hash('md5', $item.SALT);
	}
	print_r($aMD5);

	function draw(){

	}

?>
<pre>
