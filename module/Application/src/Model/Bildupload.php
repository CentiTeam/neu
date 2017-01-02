<?php
namespace Application\Model;

class Bildupload
{
	private $upload_folder    			= "/img/";	
	private $allowed_extensions			= array("png", "jpg", "jpeg", "gif");
	private $max_size					= 2048*2048;
	private $allowed_types				= array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);


	public function bildupload($uploadedfile) {

		//Pfadangabe	
		$filename = pathinfo($_FILES["uploadedfile"]["name"], PATHINFO_FILENAME);
		$extension = strtolower(pathinfo($_FILES["uploadedfile"]["name"], PATHINFO_EXTENSION));
		
		//Dateiendung überprüfen
		if(!in_array($extension, $this->allowed_extensions))
		{
			die ("Ungültige Dateiendung! Nur png, jpg, jpeg und gif Dateien!");
		}
		
		//Maximale Bildgröße überprüfen
		if($_FILES["uploadedfile"]["size"] > $this->max_size)
		{
			die ("Bilder können nicht mehr als 2 mb groß sein!");
		}
		
		//Erlaubte Dateiendung
		$detected_type = exif_imagetype($_FILES["uploadedfile"]["tmp_name"]);
		
		if(!in_array($detected_type, $this->allowed_types))
		{
			die ("Nur der Upload von Bildern ist erlaubt!");
		}

		//Pfad zusammensetzen
		$new_path = $this->upload_folder.$filename.'.'.$extension;

		//Falls Dateiname bereits vorhanden, Erweiterung des Pfades um nächsthöhere Nummer
		if(file_exists($new_path))
		{
			$id = 1;
			do 
			{
				$new_path = $this->upload_folder.$filename.'_'.$id.'.'.$extension;
				$id++;
			} while(file_exists($new_path));
				
		}
		
		if (move_uploaded_file($_FILES["uploadedfile"]["tmp_name"], $new_path))
		{
			echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';
			return $new_path;
		}
		else 
		{
			echo "Fehler beim Upload";
		}
		
	}
}