<?php
use Application\Model\DB_connection;

$db= new DB_connection();

$query ="SELECT * FROM User WHERE systemadmin = 0
	AND deaktiviert = 0
	AND (username LIKE '%" . $_GET['search'] . "%'
		OR vorname LIKE '%" . $_GET['search'] . "%'
		OR nachname LIKE '%" . $_GET['search'] . "%'
		OR email LIKE '%" . $_GET['search'] . "%') LIMIT 5;";
	
	/**
	AND u_id NOT IN
		(SELECT u_id FROM User
			LEFT JOIN gruppenmitglied USING (u_id)
			LEFT JOIN gruppe USING (g_id)
				WHERE gruppenmitglied.g_id = '".$gruppen_id."'
							GROUP BY u_id)
				;";
			*/

// $dbconnect = mysql_connect("host", "benutzername", "passwort");
// mysql_set_charset("utf8");
// $query = "use datenbankname";

	if($_GET['search'] == "*")
		$sql = mysql_query("SELECT * FROM User ORDER BY username ASC;", $db);
		else
			$sql = mysql_query($query, $db);
			while($row = mysql_fetch_object($sql)) {
				echo "<br />".$row->name;
			}
?>
			
			
<?php
/**
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
*/
?>
