<?php
function UsernameLink($Username){
	return "<a href='".$DOMAIN."/Forum/memberlist.php?mode=viewprofile&un=".$Username."'>".$Username."</a>";
}

function calculate_age($birthday)
{
   $age = '';

 if ($birthday)
 {
     list($bday_day, $bday_month, $bday_year) = array_map('intval', explode('-', $birthday));

     if ($bday_year)
      {
        $now = getdate(time() + $user->timezone + $user->dst - date('Z'));

        $diff = $now['mon'] - $bday_month;
         if ($diff == 0)
         {
           $diff = ($now['mday'] - $bday_day < 0) ? 1 : 0;
        }
        else
       {
           $diff = ($diff < 0) ? 1 : 0;
       }

       $age = (int) ($now['year'] - $bday_year - $diff);
        return $age;
     }
  }   
  return 'Unknown';
}

function GetDomain($url)
{
	$nowww = ereg_replace('www\.','',$url);
	$domain = parse_url($nowww);
	if(!empty($domain["host"])){
		return $domain["host"];
	}else{
		return $domain["path"];
	}
}

function auto_version($file)
{
  if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
    return $file;

  $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}

function artwork_correct($file)
{
	global $DOMAIN;
	
   if(substr($file, 0, 4)=="http"){
       return $DOMAIN."/NoArtwork/NoShot.png";
   }else{
       return $DOMAIN."/".$file;
   }
}

function link_correct($file)
{
   if(substr($file, 0, 7)=="http://"){
       return $file;
   }else{
       return "http://".$file;
   }
}

function name_correct_XML($file){

	$file = str_replace("'", '', $file);
	$file = str_replace("&", 'and', $file);
	return $file;
}

function name_correct($file){

	$file = str_replace("'", "''", $file);
	
	return $file;
}

class SimpleImage {
 
   var $image;
   var $image_type;
 
   function load($filename) {
 
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
	  if($this->image == false){
		return 0;
	  }else{
		return 1;
	  }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
	  $returnvalue = -1;
      if( $image_type == IMAGETYPE_JPEG ) {
         $returnvalue=imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         $returnvalue=imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         $returnvalue=imagepng($this->image,$filename);
      }
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
	  return $returnvalue;
   }
   function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   function getWidth() {
 
      return imagesx($this->image);
   }
   function getHeight() {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }      
 
}
?>