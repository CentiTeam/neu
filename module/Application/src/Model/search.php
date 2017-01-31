<?php
use Application\Model\DB_connection;

$db= new DB_connection();

$query = "SELECT * FROM articles WHERE text LIKE '%" . $_GET['search'] . "%' LIMIT 5";

if ($result=$db->execute($query)) {
	while($row = mysql_fetch_object($result))
	{
		echo '
	';
		echo preg_replace('/(' . $_GET['search'] . ')/Usi', '<span class="result">\\1</span>', $row->text);
		echo '
	';
	}
}
?>
