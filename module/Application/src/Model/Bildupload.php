<?php
namespace Application\Model;

class Bildupload
{
	private $upload_folder    			= "public/img/";	
	private $allowed_extensions			= array("png", "jpg", "jpeg", "gif");
	private $max_size					= 2*1024*1024;
	private $allowed_types				= array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
	private $img_folder					= "/img/";

	public function bildupload($uploadedfile) {

		//Pfadangabe	
		$filename = pathinfo($_FILES["uploadedfile"]["name"], PATHINFO_FILENAME);
		$extension = strtolower(pathinfo($_FILES["uploadedfile"]["name"], PATHINFO_EXTENSION));
		
		//Dateiendung überprüfen
		if(!in_array($extension, $this->allowed_extensions))
		{
			exit ("Ungültige Dateiendung! Nur png, jpg, jpeg und gif Dateien!");
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

		$upload_folder = $this->upload_folder;
		
		//Pfad zusammensetzen
		$new_path = $this->upload_folder.$filename.'.'.$extension;
		$path = $this->img_folder.$filename.'.'.$extension;
		
		//Falls Dateiname bereits vorhanden, Erweiterung des Pfades um nächsthöhere Nummer
		if(file_exists($new_path))
		{
			$id = 1;
			do 
			{
				$new_path = $this->upload_folder.$filename.'_'.$id.'.'.$extension;
				$path = $this->img_folder.$filename.'_'.$id.'.'.$extension;
				$id++;
			} while(file_exists($new_path));
			
		
		}
		
		//Das Bild wird in den Ordner Bilder mit absoluten Pfad verschoben, der relative Pfad wird zurückgegeben
		if (move_uploaded_file($_FILES["uploadedfile"]["tmp_name"], $new_path))
		{	
			return $path;
		}
		else 
		{
			echo "Fehler beim Upload";
		}
		
	}
}