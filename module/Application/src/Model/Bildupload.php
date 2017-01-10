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
		
		//Maximale Bildgr��e �berpr�fen
		if($_FILES["uploadedfile"]["size"] > $this->max_size)
		{
			echo "Bilder k&oumlnnen nicht mehr als 2 mb gro� sein!";
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

		$upload_folder = $this->upload_folder;
		
		//Pfad zusammensetzen
		$new_path = $this->upload_folder.$filename.'.'.$extension;
		$path = $this->img_folder.$filename.'.'.$extension;
		
		//Maximale Aufl�sung �berpr�fen
		$size = getimagesize($path);
		$width = $size[0];
		$height = $size[1];
		
		echo $width;
		
		if($width < 1024 AND $height < 1024)
		{
			echo "Die unterst&uumltzte Aufl&oumlsung von Bildern liegen bei 1024*1024";
			$path = false;
			return $path;
		}
		
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
			return $path;
		}
		else 
		{
			echo "Fehler beim Upload";
		}
		
	}
}