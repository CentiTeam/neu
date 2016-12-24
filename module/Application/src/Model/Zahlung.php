<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Gruppe {
	protected  $z_id;
	protected  $zahlungsbeschreibung;
	protected  $erstellungsdatum;
	protected  $zahlungsdatum;
	protected  $betrag;
	protected  $k_id;
	protected  $aenderungsdatum;
	
	public function __construct($zahlung_id = null) {
		
		$this->z_id= $zahlung_id;
		echo "$this->z_id";
			
	}
	
	// Getter und Setter
	
	public function getZ_id () {
		return $this->z_id;
	}
	
	public function setZ_Id($z_id) {
		$this->z_id= $z_id;
	}

	public function getZzahlungsbeschreibung () {
		return $this->zahlungsbeschreibung;
	}
	
	public function setZahlungsbeschreibung($zahlungsbeschreibung) {
		$this->zahlungsbeschreibung= $zahlungsbeschreibung;
	}
	
	public function getErstellungsdatum () {
		return $this->erstellungsdatum;
	}
	
	public function setErstellungsdatum($erstellungsdatum) {
		$this->erstellungsdatum= $erstellungsdatum;
	}
	
	public function getZahlungsdatum () {
		return $this->zahlungsdatum;
	}
	
	public function setZahlungsdatum($zahlungsdatum) {
		$this->zahlungsdatum= $zahlungsdatum;
	}
	
	public function getBetrag () {
		return $this->betrag;
	}
	
	public function setBetrag($betrag) {
		$this->betrag= $betrag;
	}
	
	public function getK_id () {
		return $this->k_id;
	}
	
	public function setK_Id($k_id) {
		$this->k_id= $k_id;
	}
	
	public function getAenderungsdatum () {
		return $this->aenderungsdatum;
	}
	
	public function setAenderungsdatum($aenderungsdatum) {
		$this->aenderungsdatum= $aenderungsdatum;
	}
	
	
}