<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Gruppenmitglied {
	protected  $u_id;
	protected  $g_id;
	protected  $gruppenadmin;
	
	public function __construct($user_id = null, $gruppen_id = null) {
		
		$this->u_id = $user_id;
		$this->g_id= $gruppen_id;
	}
	
	
	public function anlegen () {
		
		echo "In Gruppenmitglied-Model angekommen";
		
		$db = new DB_connection();
		
		$query = "INSERT INTO gruppenmitglied (u_id, g_id, gruppenadmin) VALUES (
				'".$this->u_id."',
				'".$this->g_id."',
				'".$this->gruppenadmin."'
				";
		echo $query;
		
		$result = $db->execute($query);
		
		return $result;
	}
	
	public function bearbeiten ($admin) {
		
	}
		
	// Getter und Setter
	
	public function getU_id () {
		return $this->u_id;
	}
	
	public function setU_Id($u_id) {
		$this->u_id= $u_id;
	}
	
	public function getG_id () {
		return $this->g_id;
	}
	
	public function setG_Id($g_id) {
		$this->g_id= $g_id;
	}
	
	public function getGruppenadmin () {
		return $this->gruppenadmin;
	}
	
	public function setGruppenadmin($gruppenadmin) {
		$this->gruppenadmin= $gruppenadmin;
	}
	
}