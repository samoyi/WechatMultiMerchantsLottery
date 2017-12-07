<pre><?php   

require_once('MySQLiController.class.php');
$MySQLiController = new MySQLiController( $dbr );

$tableName = 'test';
$sCol = 'ns';
$where = 'name="name1"';


$result = $MySQLiController->increase($tableName, $sCol, $where, true);

var_dump( $result );

$dbr->close();


?></pre>