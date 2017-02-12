<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Kategorie;
use Application\Model\Zahlung;
use Application\Model\Zahlungsteilnehmer;
use Application\Model\Gruppenereignis;


class ZahlungbearbeitenController extends AbstractActionController {

	function zahlungbearbeitenAction() {
		
		// Berechtigungspr�fung
		session_start();
		
		if($_SESSION['angemeldet'] == NULL || $_SESSION['systemadmin']) {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg
			]);
			$view->setTemplate('application/index/index.phtml');
			return $view;
		
		} 
		
		// Berechtigungaprüfung, ob Zahlungateilnehmer
		$zahlung= new Zahlung();
		$z_id=$_REQUEST['z_id'];
		$zahlung->laden($z_id);
		$teilnehmerliste = Zahlungsteilnehmer::zahlungsteilnehmerholen($z_id);
		
		$aktuser_id=$_SESSION['user']->getU_id();
		$gruppen_id=$_REQUEST['g_id'];
		$istTeilnehmer=false;
		
		foreach ($teilnehmerliste as $teilnehmer) {
			if ($aktuser_id==$teilnehmer->getUser()->getU_id() && $gruppen_id==$teilnehmer->getZahlung()->getGruppe()->getG_id()) {
				$istTeilnehmer=true;
			}
		}
		
		// Wenn kein Zahlungsteilnehmer, dann wird die Overview des jew. Users geladen
		if ($istTeilnehmer==false) {
			
			$msg="Nicht berechtigt!";
		
			$user=$_SESSION['user'];
			$uname=$user->getVorname();
		
			$view = new ViewModel([
					'uname' => $uname,
					'user' => array($user),
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/overview/overview.phtml');
		
			return $view;
		
		} else {

			//Holen der z_id aus Formular
			$z_id = $_REQUEST['z_id'];			
			
			//Laden des Objektes der Klasse Zahlung mit der �bergebenen z_id in die Variable $zahlung
			$zahlung = new Zahlung();
			$zahlung->laden($z_id);
			
			//Holen der u_id aus Session
			$user_id=$_SESSION['user']->getU_id();
			
			//�berpr�fen, ob User = ersteller
			$ersteller = new Zahlungsteilnehmer();
			$ersteller->laden($z_id, $user_id);		
			
			//Zahlungsteilnehmer der Zahlung holen
			$teilnehmerliste = Zahlungsteilnehmer::zahlungsteilnehmerholen($z_id);
			
			// BErechtigungsprüfung
			$aktuser_id=$_SESSION['user']->getU_id();
			$istTeilnehmer=false;
			
			foreach ($teilnehmerliste as $teilnehmer) {
				if ($aktuser_id==$teilnehmer->getUser()->getU_id()) {
					$istTeilnehmer=true;
				}
			}
			
			// Wenn kein Zahlungsteilnehmer, dann wird die Groupoverview des jew. Users geladen
			if ($istTeilnehmer==false) {
			
				$msg="Nicht berechtigt!";
			
				$user=$_SESSION['user'];
				$uname=$user->getVorname();
			
				$view = new ViewModel([
						'uname' => $uname,
						'user' => array($user),
						'msg' => $msg,
				]);
			
				$view->setTemplate('application/overview/overview.phtml');
			
				return $view;
			
			}
			
			
			$nochoffeneZahlungen=false;
			
			if ($ersteller->getZahlungsempfaenger()->getU_id()==$user_id) {
				
				// Abprüfen, ob die Zahlung bereits ganz oder teilweise beglichen worden ist
				foreach ($teilnehmerliste as $zahlungsteilnehmer)
				{
					//In dem Fall, dass der Restbetrag nicht dem Anteil entspricht, ist die Zahlung teils oder ganz beglichen
					if ($zahlungsteilnehmer->getAnteil()!=$zahlungsteilnehmer->getRestbetrag() AND $zahlungsteilnehmer->getUser()->getU_id()!=$user_id)
					{
						$beglichen++;
						$nochoffeneZahlungen=true;
					}
				}
			
			
				//Liste alle verf�gbaren Kateforien holen
				$kategorieliste = Kategorie::listeHolen();

				$g_id = $_REQUEST['g_id'];	
				$gruppe = new Gruppe();
				$gruppe->laden($g_id);
				
				$mitgliederliste = User::gruppenmitgliederlisteholen($g_id);
			
				// HEutigers Datum als akutellesdatum
				date_default_timezone_set("Europe/Berlin");
				$timestamp=time();
				$aktuellesdatum= date('Y-m-d', $timestamp);
		

				$saved= false;
				$veraenderbar=false;
			
				//Wenn die Variable beglichen auf Null steht, kann die Zahlung bearbeitet werden
				if ($beglichen==0 && $ersteller->getUser()->getU_id()==$aktuser_id) {
					
					$veraenderbar=true;
					
					if ($_REQUEST['speichern']) {
						
						
						
						
						
				
				
					// Anteile in Schleife speichern und überprüfen, ob Summe dem Gesamtbetrag entspricht
					$anteile=array();
					$i=0;
					$summe=0;

					foreach ($_POST['anteilsbetrag'] as $zaehler => $anteil) {
						$anteile[]=$anteil;
						$i++;
						$summe += $anteil;
					}
				
	

					if($summe != $_REQUEST["betrag"]){
						echo ("Die Anteile m&uuml;ssen zusammen der Gesamtsumme entsprechen.");
					}else {
				
						// Schritt 1:  Werte aus Formular einlesen
						$zahlungsbeschreibung=$_REQUEST["zahlungsbeschreibung"];
						$zahlungsdatum=$_REQUEST["zahlungsdatum"];
						$betrag=$_REQUEST["betrag"];
						$kategorie_id=$_REQUEST["kategorie"];
						$aenderungsdatum= date('Y-m-d',$timestamp);
						$gruppen_id=$zahlung->getGruppe()->getG_id();
						
						
						
						
						//Pr�fen ob Zahlungsdatum im Format YYYY-MM-DD vorliegt
						$bool_jahr_okay = false;
						$bool_erster_strich = false;
						$bool_monat_okay = false;
						$bool_zweiter_strich = false;
						$bool_tag_okay = false;
							
							
						//Teilen des Datums an den Bindestrichen
						$yyyy = substr($zahlungsdatum, 0, 4);
						//Testen ob die ersten vier Zeichen eine Zahl sind
						if(ctype_digit($yyyy) == true){
							$bool_jahr_okay = true;
						}
						
						//Testen, ob nach dem Jahr ein Strich kommt
						if(substr($zahlungsdatum, 4, 1) == "-"){
							$bool_erster_strich = true;
						}
						
						
						//Holen des Monats aus dem String
						$mm = substr($zahlungsdatum, 5, 2);
						//Testen, ob Monat richtig eingegeben wurde als Zahl zwischen 1 und 12
						
						//Mit ctype_digit pr�fen, ob jedes Zeichen in $mm eine Ziffer ist
						if(ctype_digit($mm) == true){
							//Konvertieren des Strings in einen Integer
							$mm_int = (int)$mm;
							//Pr�fen, ob Monat zwischen 1 und 12
							if($mm_int>0 && $mm_int<13){
								$bool_monat_okay = true;
							}
								
								
							//Testen, ob nach dem Monat ein Strich kommt
							if(substr($zahlungsdatum, 7, 1) == "-"){
								$bool_zweiter_strich = true;
									
									
							}
						
						
						
						
							//Holen des Tag aus dem String
							$tt = substr($zahlungsdatum, 8, 2);
							//Testen, ob Tag richtig eingegeben wurde als Zahl zwischen 1 und 12
						
							//Mit ctype_digit pr�fen, ob jedes Zeichen in $tt eine Ziffer ist
							if(ctype_digit($tt) == true){
								//Konvertieren des Strings in einen Integer
								$tt_int = (int)$tt;
									
								//Fuer 31-taegige Monate
								if($mm_int == "1" || $mm_int == "3" || $mm_int == "5" || $mm_int == "7" || $mm_int == "8" || $mm_int == "10" || $mm_int == "12" ){
									if($tt_int>0 && $tt_int<32){
										$bool_tag_okay = true;
									}
								}
									
								//Fuer 30-taegige Monate
								if($mm_int == "4" || $mm_int == "6" || $mm_int == "9" || $mm_int == "11"){
									if($tt_int>0 && $tt_int<31){
										$bool_tag_okay = true;
									}
								}
									
								//Fuer Februar
								//Pruefen ob Schaltjahr (Falls ja hat sj den Wert 1, sonst 0)
								$sj = date(L, mktime(1, 1, 1, 1, 1, $yyyy));
									
								//Wenn Februar und Schaltjahr
								if($mm_int = 2 && $sj == 1){
									if($tt_int>0 && $tt_int<30){
										$bool_tag_okay = true;
									}
								}
									
								//Wenn Februar und kein Schaltjahr
								if($mm_int = 2 && $sj == 0){
									if($tt_int>0 && $tt_int<29){
										$bool_tag_okay = true;
									}
								}
									
									
									
									
									
								
						
							if($bool_datum_okay == false){
								$datumspruefungsnachricht = "Das angegebene Datum ist nicht korrekt!";
								return new ViewModel([
										'gruppe' => array($gruppe),
										'zahlungsteilnehmer' => array($teilnehmer),
										'msg' => $msg,
										'kategorieListe' => $kategorieliste,
										'mitgliederListe' => $mitgliederliste,
										'erstellungsdatum' => $erstellungsdatum,
										'zahlung' => array($zahlung),
										'zahlungsteilnehmerliste' => $zahlungsteilnehmerliste,
										'veraenderbar' => $veraenderbar,
										'datumspruefungsnachricht' => $datumspruefungsnachricht
								]);
							}
						

						// verkn�pfte Models laden
						if ($kategorie_id != null) {
							$kategorie = new Kategorie();
							if (! $kategorie->laden ($kategorie_id)) {
								$errorStr .= "Keine g&uuml;ltige Kategorie angegeben!<br />";
							}
						}
						
						// verkn�pfte Models laden
						if ($gruppen_id != null) {
							$gruppe = new Gruppe();
							if (! $gruppe->laden ($gruppen_id)) {
								$errorStr .= "Keine g&uuml;ltige Gruppe angegeben!<br />";
							}
						}


						// Schritt 2: Daten pr�fen und Fehler in Array füllen
						$errorStr ="";
						$msg="";

						// #TODO Fehlerüberprüfung fehlt!


						// Zahlung-Objekt mit Daten aus Request-Array f�llen

						$zahlung->setZahlungsbeschreibung($zahlungsbeschreibung);
						$zahlung->setZahlungsdatum($zahlungsdatum);
						$zahlung->setBetrag($betrag);
						if ($kategorie_id != null){
							$zahlung->setKategorie($kategorie);
						}
						$zahlung->setAenderungsdatum($aenderungsdatum);
						
						
						
						// Fehlerabfrage, ob mehrere Teilnehmer ausgew�hlt wurden
						$anzahlteilnehmer=0;
							
						for($anzahl=0; $anzahl<$i; $anzahl++) {
							if ($anteile[$anzahl] != "") {
								$anzahlteilnehmer++;
							}
						}
						
						
						
						$zahlungsbeschreibung=$_POST['zahlungsbeschreibung'];
						/** Sollte rausfallen, da man nun auch Zahlungen für andere Erstellen darf, an denen man nicht teilnimmt
						if ($anzahlteilnehmer <= 1){
							$msg="Du bist momentan der einzige Zahlungsteilnehmer. W&auml;hl noch ein weiteres Gruppenmitglied aus!";
						
							return new ViewModel([
									'gruppe' => array($gruppe),
									'msg' => $msg,
									'kategorieListe' => $kategorieliste,
									'mitgliederListe' => $mitgliederliste,
									'erstellungsdatum' => $erstellungsdatum,
									'zahlung' => array($zahlung)
										
							]);
						}
						*/

							// Wenn tempor�res Objekt gef�llt wurde kann mit diesen Werten das Objekt �ber die Bearbeiten-Fkt in die DB geschrieben werden
							if ($errorStr == "" && $zahlung->bearbeiten()) {

								

								// array_push($msg, "Gruppe erfolgreich gespeichert!");
								//  $msg .= "Gruppe erfolgreich gespeichert!";
								$saved = true;

								// Neue Z_id durch Laden der neu erstellten Gruppe ins Objekt laden
								$zahlung->laden($z_id);
								
								// hier war der Code nach if ($_REQUEST['speichern']) { zuvor
								
								//Bisherige Zahlungsteilnehmer l�schen
								Zahlungsteilnehmer::loeschen($zahlung->getZ_id());
									
								$counter=0;
								// Legt die zugeh�rigen Zahlungsteilnehmer Datens�tze an, au�er f�r sich selbst (info wird aber f�r Anteil ben�tigt!)
								foreach ($_POST['zahlungsteilnehmer'] as $key => $value) {

									if($anteile[$counter]=="" && $value !=$user_id) {
											
										$counter++;
									
									} else {
									
										//Aus der ID des �bergebenen Arrays User-Objekt erstellen
										$akt_benutzer = new User();
										$akt_benutzer->laden($value);
									
										// Variablen befuellen
										$zahlungsteilnehmer=new Zahlungsteilnehmer();

										$zahlungs_id=$zahlung->getZ_id();
										
										if($value == $user_id) {
											//Der Status bei dem, der die Zahlung erstellt hat ist "ersteller", dies erleichtert die nachtr�gliche Bearbeitung von
											//Zahlungen, da Zahlungen nur bearbeitet werden d�rfen, wenn sie nicht den Status "beglichen" Aufweisen.
								 		$status="ersteller";
								 		$restbetrag="0";
										} else {
											//Der Status f�r alle anderen Zahlungsteilnehmer ist offen.
								 		$status="offen";
								 		$restbetrag=$anteil;
										}
										
										// Wenn der Ersteller nicht mitzahlt
										if($anteile[$counter]) {
											$anteil=$anteile[$counter];
										} else {
											$anteil="0";
										}

										$zahlungsempfaenger=$_SESSION['user'];

										// Variablenwerte in Objekt einlesen
										$zahlungsteilnehmer->setUser($akt_benutzer);
										$zahlungsteilnehmer->setZahlung($zahlung);
										$zahlungsteilnehmer->setStatus($status);
										$zahlungsteilnehmer->setAnteil($anteil);
										$zahlungsteilnehmer->setZahlungsempfaenger($zahlungsempfaenger);
										$zahlungsteilnehmer->setRestbetrag($restbetrag);
	
										$zahlungsteilnehmer->anlegen();
										
										$counter++;
									}
								}
								
								//Schreiben des Ereignisses das die Zahlung bearbeitet wurde in die Ereignistabelle der Datenbank
								Gruppenereignis::zahlungbearbeitenEreignis($zahlung, $gruppe);
								
								
								$teilnehmerliste = Zahlungsteilnehmer::zahlungsteilnehmerholen($z_id);
								
								$view = new ViewModel([
										'gruppe' => array($gruppe),
										'errors'   => $errors,
										'msg' => $msg,
										'zahlung' => array($zahlung),
										'veraenderbar' => $veraenderbar,
										'teilnehmerliste' => $teilnehmerliste
 										
											
								]);

								$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
									
								return $view;
									
								//$zahlungsteilnehmerListe=Zahlungsteilnehmer::listeHolen();
									
								//  Wenn es einen Fehler beim Speichern gab
							} elseif ($errorStr == "") {

								// array_push($msg, "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!");
								$msg .= "Datenprüfung in Ordnung, Fehler beim Speichern der Zahlung!";
								$saved = false;

							} else {

								// array_push($msg, "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!");
								$msg .= "Fehler bei der Datenprüfung. Zahlung nicht gespeichert!";
								$saved = false;

							}
								
						}
					}
				}
				else {
					$veraenderbar=false;
					echo "Diese Zahlung wurde bereits teilweise oder vollst&aumlndig beglichen und kann daher nicht mehr bearbeitet werden";
					
					$view = new ViewModel([
							'gruppe' => array($gruppe),
							'errors' => $errors,
							'msg' => $msg,
							'zahlung' => array($zahlung),
							'teilnehmerliste' => $teilnehmerliste,
							'veraenderbar' => $veraenderbar
					]);
				
					$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
					return $view;
				}
			}
			else {
				$veraenderbar=false;
				echo "Sie k&oumlnnen diese Zahlung nicht bearbeiten, da Sie sie nicht erstellt haben";
				
				$view = new ViewModel([
						'gruppe' => array($gruppe),
						'errors' => $errors,
						'msg' => $msg,
						'zahlung' => array($zahlung),
						'teilnehmerliste' => $teilnehmerliste,
						'veraenderbar' => $veraenderbar
				]);
				
				$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
				return $view;
			}

			$zahlungsteilnehmerliste=Zahlungsteilnehmer::zahlungsteilnehmerholen($z_id);

			return new ViewModel([
					'gruppe' => array($gruppe),
					'zahlungsteilnehmer' => array($teilnehmer),
					'msg' => $msg,
					'kategorieListe' => $kategorieliste,
					'mitgliederListe' => $mitgliederliste,
					'erstellungsdatum' => $erstellungsdatum,
					'zahlung' => array($zahlung),
					'zahlungsteilnehmerliste' => $zahlungsteilnehmerliste,
					'veraenderbar' => $veraenderbar
			]);
		}
	}
}