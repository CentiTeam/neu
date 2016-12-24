<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Gruppe {
	protected  $k_id;
	protected  $kategoriebeschreibung;
	
	public function __construct($kategorie_id = null) {
		
		$this->k_id= $kategorie_id;
		echo "$this->k_id";
			
	}
	
	// Getter und Setter
	
	public function getK_id () {
		return $this->k_id;
	}
	
	public function setK_Id($k_id) {
		$this->k_id= $k_id;
	}

	public function getKategoriebeschreibung () {
		return $this->kategoriebeschreibung;
	}
	
	public function setKategoriebeschreibung($kategoriebeschreibung) {
		$this->kategoriebeschreibung= $kategoriebeschreibung;
	}
	
	
}