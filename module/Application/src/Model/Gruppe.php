<?php
namespace Application\Model;


use Application\Model\DB_connection;

class gruppe {
	private $g_id;
	private $gruppenname;
	private $gruppenbeschreibung;
	private $gruppenbildpfad;
	
	public function __construct() {
		$this->gruppenname = "";
		$this->gruppenbeschreibung = "";
		$this->gruppenbildpfad ="";
	}
	
	public function anlegen () {
		
		$db = new DB_connection();
		
		$query = "INSERT INTO gruppe (gruppenname, gruppenbeschreibung, gruppenbildpfad) VALUES (
				'".$this->gruppenname."', 
				'".$this->gruppenbeschreibung."',
				'".$this->gruppenbildpfad."')" ;

		$result = $db->execute($query);
		
		// var_dump($this->gruppenname);
		
		
		// GENAUE SYNTAX FEHLT!!
		$isOK = mysqli_affected_rows ($result) > 0;
		
		return $isOK;
		
	}
	
	public function loeschen () {
		
	}
	
	public function listeHolen() {
		
	}
	
	
	
	// Getter und Setter
	
	public function getG_id () {
		return $this->g_id;
	}
	
	public function setG_Id() {
		$this->g_id= $g_id;
	}
	
	public function getGruppenname () {
		return $this->gruppenname;
	}
	
	public function setGruppenname() {
		$this->gruppenname= $gruppenname;
	}
	
	public function getGruppenbeschreibung () {
		return $this->gruppenbeschreibung;
	}
	
	public function setGruppenbeschreibung() {
		$this->gruppenbeschreibung= $gruppenbeschreibung;
	}
	
	public function getGruppenbildpfad () {
		return $this->gruppenbildpfad;
	}
	
	public function setGruppenbildpfad() {
		$this->gruppenbildpfad= $gruppenbildpfad;
	}
	
	
}