<?php
/*
 * This class can help you to upload images into your php web page.
 *
 * @author : Cokorda Gde Agung Smara Adnyana Putra
 * @email : cokorda.smara@gmail.com
 *
 */
 
class _Image {
	function upload_photo($fupload, $path, $folder, $fupload_name, $img_width, $img_height){
		//Direktori gambar
		$vdir_upload = $path."uploads/".$folder."/images/";
		$vfile_upload_thumb = $vdir_upload."thumb_".$fupload_name;
		$vfile_upload = $vdir_upload.$fupload_name;
		
		//Buat direktori folder gambar
		if (!is_dir($vdir_upload)) {
		    @mkdir($vdir_upload, 0777, true);
		}
		
		//Hapus gambar thumbnail lama
		if (is_file($vfile_upload_thumb)) {
			unlink($vfile_upload_thumb);
		}
		
		//Hapus gambar asli lama
		if (is_file($vfile_upload)) {
			unlink($vfile_upload);
		}
		
		//Simpan gambar dalam ukuran sebenarnya
		move_uploaded_file($_FILES[$fupload]["tmp_name"], $vfile_upload);
		
		//Baca tipe file
		$file_type = $_FILES[$fupload]["type"];
		
		//Identitas file asli
		if ($file_type == "image/jpg" or $file_type == "image/jpeg" or $file_type == "image/pjpeg") {
			$img_src = imagecreatefromjpeg($vfile_upload);
		} elseif ($file_type == "image/png") {
			$img_src = imagecreatefrompng($vfile_upload);
		}
		
		//Simpan ukuran gambar asli
		$src_width = imageSX($img_src);
		$src_height = imageSY($img_src);
		
		//Set ukuran gambar thumbnail
		$original_aspect = $src_width / $src_height;
  		$thumb_aspect = $img_width / $img_height;
		
  		if ($original_aspect >= $thumb_aspect) {
     		$new_height = $img_height;
     		$new_width = $src_width / ($src_height / $img_height);
  		} else {
     		$new_width = $img_width;
     		$new_height = $src_height / ($src_width / $img_width);
  		}
  		
		//Proses perubahan ukuran thumbnail
		$thumb = imagecreatetruecolor($img_width, $img_height);
		imagealphablending($thumb, false);
    	imagesavealpha($thumb, true);
    	$transparent_thumb = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
    	imagefilledrectangle($thumb, 0, 0, $src_width, $src_height, $transparent_thumb);
		imagecopyresampled($thumb, $img_src, 0 - ($new_width - $img_width) / 2, 0 - ($new_height - $img_height) / 2, 0, 0, $new_width, $new_height, $src_width, $src_height);
		
		//Set ukuran gambar asli 
		$dst_width = $img_width;
		$dst_height = ceil(($dst_width / $src_width) * $src_height);
		
		//Proses perubahan ukuran asli
		$img = imagecreatetruecolor($dst_width, $dst_height);
		imagealphablending($img, false);
    	imagesavealpha($img, true);
    	$transparent_img = imagecolorallocatealpha($img, 255, 255, 255, 127);
    	imagefilledrectangle($img, 0, 0, $src_width, $src_height, $transparent_img);
		imagecopyresampled($img, $img_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
		
		//Simpan gambar thumbnail dengan format .png
		imagepng($thumb, $vfile_upload_thumb);
		
		//Simpan gambar asli dengan format .png
		imagepng($img, $vfile_upload);
		
		//Hapus gambar di memori komputer
		imagedestroy($img_src);
		imagedestroy($thumb);
		imagedestroy($img);
	}
	
	function upload_image($fupload, $path, $folder, $fupload_name, $img_width){
		//Direktori gambar
		$vdir_upload = $path."uploads/".$folder."/images/";
		$vfile_upload = $vdir_upload.$fupload_name;
		
		//Buat direktori folder gambar
		if (!is_dir($vdir_upload)) {
		    @mkdir($vdir_upload, 0777, true);
		}
		
		//Hapus gambar lama
		if (is_file($vfile_upload)) {
			unlink($vfile_upload);
		}
		
		//Simpan gambar dalam ukuran sebenarnya
		move_uploaded_file($_FILES[$fupload]["tmp_name"], $vfile_upload);
		
		//Baca tipe file
		$file_type = $_FILES[$fupload]["type"];
		
		//Identitas file asli
		if ($file_type == "image/jpg" or $file_type == "image/jpeg" or $file_type == "image/pjpeg") {
			$img_src = imagecreatefromjpeg($vfile_upload);
		} elseif ($file_type == "image/png") {
			$img_src = imagecreatefrompng($vfile_upload);
		}
		
		$src_width = imageSX($img_src);
		$src_height = imageSY($img_src);
		
		//Set ukuran gambar hasil perubahan
		$dst_width = $img_width;
		$dst_height = ceil(($dst_width / $src_width) * $src_height);
		
		//Proses perubahan ukuran
		$im = imagecreatetruecolor($dst_width, $dst_height);
		imagealphablending($im, false);
    	imagesavealpha($im, true);
    	$transparent = imagecolorallocatealpha($im, 255, 255, 255, 127);
    	imagefilledrectangle($im, 0, 0, $src_width, $src_height, $transparent);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
		
		//Simpan gambar dengan format .png
		imagepng($im, $vfile_upload);
		
		//Hapus gambar di memori komputer
		imagedestroy($img_src);
		imagedestroy($im);
	}
	
	function show_image($class, $dir, $path, $image, $empty, $width, $height) {
		if (!empty($dir)) {
			$full_path = $dir.$path.$image;
		} else {
			$full_path = $path.$image;
		}
		
	  	if(!empty($image) and file_exists($full_path)) {
	    	return "<img class='".$class."' src='".$path.$image."' style='width:".$width."px;height:".$height."px;' />";
		} else {
			return "<img class='".$class."' src='".$empty."' style='width:".$width."px;height:".$height."px;' />";
		}
	}
	
	function get_image($class, $dir, $content, $empty, $width, $height) {
		$first_img = "";
	    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
	    for ($i = 0; $i < count($matches); $i++) {
	    	if (file_exists($dir.$matches[1][(count($matches)-$i)-1])) {
	  			$first_img = $matches[1][(count($matches)-$i)-1];
	  		}
	  	}
	  	
		if (!empty($dir)) {
			$full_path = $dir.$first_img;
		} else {
			$full_path = $first_img;
		}
		
	  	if(!empty($first_img) and file_exists($full_path)) {
	    	return "<img class='".$class."' src='".$first_img."' style='width:".$width."px;height:".$height."px;' />";
		} else {
			return "<img class='".$class."' src='".$empty."' style='width:".$width."px;height:".$height."px;' />";
		}
	}
}

$image = new _Image;
?>