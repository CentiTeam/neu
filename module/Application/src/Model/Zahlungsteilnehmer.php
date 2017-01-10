<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Zahlungsteilnehmer {
	protected  $u_id;
	protected  $z_id;
	protected  $status;
	protected  $anteil;
	protected  $zahlungsempfaenger; 
	
	public function __construct() {
			
	}
	
	public function anlegen () {
	
		$db = new DB_connection();
	
		$query = "INSERT INTO zahlungsteilnehmer (u_id, z_id, status, anteil, zahlungsempfaenger) VALUES (
				'".$this->u_id."',
				'".$this->z_id."',
				'".$this->status."',
				'".$this->anteil."',
				'".$this->zahlungsempfaenger."'
				)" ;
	
		$result = $db->execute($query);
	
		return $result;
	}
	
	
	// Getter und Setter
	
	public function getU_id () {
		return $this->u_id;
	}
	
	public function setU_Id($u_id) {
		$this->u_id= $u_id;
	}
	
	public function getZ_id () {
		return $this->z_id;
	}
		
	public function setZ_Id($z_id) {
		$this->z_id= $z_id;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setStatus($status) {
		$this->status= $status;
	}
	
	public function getAnteil() {
		return $this->anteil;
	}
	
	public function setAnteil($anteil) {
		$this->anteil= $anteil;
	}
	
	public function getZahlungsempfaenger() {
		return $this->zahlungsempfaenger;
	}
	
	public function setZahlungsempfaenger($zahlungsempfaenger) {
		$this->zahlungsempfaenger= $zahlungsempfaenger;
	}
}