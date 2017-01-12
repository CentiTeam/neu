<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Nachricht {
	protected  $n_id;
	protected  $datum;
	protected  $text;
	protected  $u_id;
	protected  $g_id;
	
	public function __construct($nachricht_id = null) {
		
		$this->n_id= $nachricht_id;
		echo "$this->n_id";
			
	}
	
	
	public function sendMessage($n_id, $message, $u_id, $g_id) {
		
		$db = new DB_connection;
		$zeit = time ();
		
		$query = "INSERT INTO nachricht (n_id, datum, text, u_id, g_id) VALUES (
				
				'".$this->n_id."',
					$zeit,
				'".$this->message."',
				'".$this->u_id."',
				'".$this->g_id."',
						";
		
		
		$result = $db->execute($query);
		return $result;
	}
	
	// Getter und Setter
	
	public function getN_id () {
		return $this->n_id;
	}
	
	public function setN_Id($n_id) {
		$this->n_id= $n_id;
	}

	public function getDatum () {
		return $this->datum;
	}
	
	public function setDatum ($datum) {
		$this->datum=$datum;
	}
	
	public function getText () {
		return $this->text;
	}
	
	public function setText ($text) {
		$this->text=$text;
	}
	
	public function getG_id () {
		return $this->g_id;
	}
	
	public function setG_Id($g_id) {
		$this->g_id= $g_id;
	}
	
	public function getU_id () {
		return $this->u_id;
	}
	
	public function setU_Id($u_id) {
		$this->u_id= $u_id;
	}
}