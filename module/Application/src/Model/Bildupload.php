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
		
		//Dateiendung �berpr�fen
		if(!in_array($extension, $this->allowed_extensions))
		{
			echo "Ung&uumlltige Dateiendung! Nur png, jpg, jpeg und gif Dateien!";
			$path = false;
			return $path;
		}
		
		var_dump($_FILES["uploadedfile"]["size"]);
		
		//Maximale Bildgr��e �berpr�fen
		if($_FILES["uploadedfile"]["size"] > $this->max_size)
		{
			echo "Bilder d&oumlfen nicht grouml;&szlig;er als 2 MB sein!";
			$path = false;
			return $path;
		}
		
		
		//Erlaubte Dateiendung
		$detected_type = exif_imagetype($_FILES["uploadedfile"]["tmp_name"]);
		
		if(!in_array($detected_type, $this->allowed_types))
		{
			echo "Nur der Upload von Bildern ist erlaubt!";
			$path = false;
			return $path;
		}

		//L�nge des Filenamen bestimmen, falls der Name mehr als 12 Zeichen hat, werden diese gek�rzt
		//Es wird auf 12 Zeichen gek�rzt, damit im Falle von mehreren Dateien die Erweiterungen
		//nicht �ber 15 Zeichen hinausgehen kann
		$length = strlen($filename);
		
		if($length > 12) {
			$altfilename = substr($filename, 0, 12);
			$filename = $altfilename;
		}
		
		
		//Pfadangabe zum Ordner, in dem das Bild gespeichert wird
		$upload_folder = $this->upload_folder;
		
		//Pfad zusammensetzen
		$new_path = $this->upload_folder.$filename.'.'.$extension;
		$path = $this->img_folder.$filename.'.'.$extension;
		
		//Falls Dateiname bereits vorhanden, Erweiterung des Pfades um n�chsth�here Nummer
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
		
		//Das Bild wird in den Ordner Bilder mit absoluten Pfad verschoben, der relative Pfad wird zur�ckgegeben
		if (move_uploaded_file($_FILES["uploadedfile"]["tmp_name"], $new_path))
		{	
			//Maximale Aufl�sung �berpr�fen
			
			 $size = getimagesize($new_path);
			 $width = $size[0];
			 $height = $size[1];
				
			 if($width > 1024 OR $height > 1024)
			 {
			 echo "Die unterst&uumltzte Aufl&oumlsung von Bildern liegt bei 1024*1024";
			 unlink($new_path);
			 $path = false;
			 return $path;
			 }
			
			 return $path;
		}
		else 
		{
			echo "Fehler beim Upload";
		}
		
	}
}