<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Gruppe {
	protected  $u_id;
	protected  $teilnehmerbildpfad;
	
	public function __construct($teilnehmer_id = null) {
		
		$this->u_id= $teilnehmer_id;
		echo "$this->u_id";
			
	}
	
	// Getter und Setter
	
	public function getU_id () {
		return $this->u_id;
	}
	
	public function setU_Id($u_id) {
		$this->u_id= $u_id;
	}

	public function getTeilnehmerbildpfad () {
		return $this->teilnehmerbildpfad;
	}
	
	public function setTeilnehmerbildpfad($teilnehmerbildpfad) {
		$this->teilnehmerbildpfad= $teilnehmerbildpfad;
	}
	
	
}