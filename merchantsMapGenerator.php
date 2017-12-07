<meta charset="utf-8">
<pre>
<?php

	define('SALT', 'aba'); // 随意指定一个不公开的字符串

	// $lotteryRules = array( // 所有的2C商家名称和奖项设置
	// 	'某某蛋糕店'=>array(
	// 		'一等奖'=> array(0.01, 10), // 一等奖中奖概率为1%，奖品总数10个
	// 		'二等奖'=> array(0.05, 20),
	// 		'三等奖'=> array(0.1, 30))
	// 	),
	// 	'某某零食店'=>array(array()
	// 		'买一送一'=> array(0.05, 30),
	// 		'8折'=> array(0.1, 50),
	// 	),
	// 	'某某便利店'=>array(array()
	// 		'5元优惠券'=> array(0.1, 100),
	// 		'10元优惠券'=> array(0.05, 50),
	// 		'15元优惠券'=> array(0.01, 30)
	// 	),
	// 	'某某便利店'=>array(array()
	// 		'奖品A'=> array(0.1, 30),
	// 		'奖品B'=> array(0.1, 30),
	// 		'奖品C'=> array(0.1, 30),
	// 		'奖品D'=> array(0.1, 30),
	// 		'奖品E'=> array(0.1, 30)
	// 	),
	// );

	$lotteryRules = json_decode(file_get_contents('lotteryRules.json'), true);




	// 下面的代码不用动 ---------------------------------------------------------

	$merchants = array_keys($lotteryRules);

	$aSha512 = array();
	foreach($merchants as $item){
		$aSha512[$item] = hash('sha512', $item.SALT);
	}
	print_r($aSha512);

	function draw(){

	}

?>
<pre>
