<?php
/*
 * This class can help you to upload any files into your php web page.
 *
 * @author : Cokorda Gde Agung Smara Adnyana Putra
 * @email : cokorda.smara@gmail.com
 *
 */
 
class _Uploader {
	function upload_file($fupload, $path, $folder, $fupload_name) {
		//Direktori file
		$vdir_upload = $path."uploads/".$folder."/files/";
		$vfile_upload = $vdir_upload.$fupload_name;
		
		//Buat direktori folder file
		if (!is_dir($vdir_upload)) {
		    @mkdir($vdir_upload, 0777, true);
		}
		
		//Hapus file lama
		if (is_file($vfile_upload)) {
			unlink($vfile_upload);
		}
		
		//Simpan dan pindahkan file yang di upload
		move_uploaded_file($_FILES[$fupload]["tmp_name"], $vfile_upload);
	}
	
	function delete_file($path) {
	    if (is_dir($path) === true) {
	        $files = array_diff(scandir($path), array(".",".."));
	        foreach ($files as $file) {
	            $this->delete_file(realpath($path)."/".$file);
	        }
	        return rmdir($path);
	    } elseif (is_file($path) === true) {
	        return unlink($path);
	    }
	    return false;
	}
}

$uploader = new _Uploader;
?>