<?php

	require_once('MySQLiController/MySQLiController.class.php');
	$MySQLiController = new MySQLiController( $dbr );

	require 'config_ignore.php'; // 获取salt
	if($_POST['act']==='add_merchant'){
		$merchant = $_POST['merchant'];

		$merchantHash = hash('md5', $merchant.SALT);
		$result = $MySQLiController->getRow('merchants', 'merchantID="'.$merchantHash. '"' );

		$result = $MySQLiController->insertRow('merchants', array('merchantID', 'merchantName'), array($merchantHash, $merchant));
		if($result == true){
			echo '加入商户成功';
		}
		else{
			echo $result;
		}
	}

	if($_POST['act']==='add_prize'){

		$merchant = $_POST['merchant'];
		$prizeName = $_POST['prize_name'];
		$probabilityPercent = $_POST['probability_percent'];
		$total= $_POST['total'];
		$remaining = $_POST['total'];

		$merchantHash = hash('md5', $merchant.SALT);
		$result = $MySQLiController->insertRow('prizes',
			array('merchantID', 'prizeName', 'probabilityPercent', 'total', 'remaining'),
			array($merchantHash, $prizeName, $probabilityPercent, $total, $remaining));

		if($result === true){
			echo '加入奖品成功';
		}
		else{
			echo $result;
		}
	}

	if($_GET['act']==='get_merchants'){
		$result = $MySQLiController->getAll('merchants');
		echo json_encode($result);
	}

	if($_GET['act']==='get_prizes'){
		$result = $MySQLiController->getAll('prizes', 'merchantID');
		echo json_encode($result);
	}



	$dbr->close();

?>
