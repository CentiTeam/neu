<?php
namespace Application\Model;

class DB_connection
{
	private $server    	= "localhost";
	private $database 	= "gpDB";
	private $user 		= "root";
	private $password 	= "Fup7bytM";


	public function execute($query) {
		$conn = new \mysqli($this->server, $this->user, $this->password, $this->database);

		if ($conn->connect_error)
		{
			die("Es konnte keine Verbindung zur Datenbank hergestellt werden: " . $this->conn->connect_error);
		}

		$result = mysqli_query($conn, $query);
		echo mysqli_error($conn);
		return $result;
	}
}
