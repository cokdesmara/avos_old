<?php
/*
 * This is php mysqli (mysql improved) database configuration.
 *
 * @author : Cokorda Gde Agung Smara Adnyana Putra
 * @email : cokorda.smara@gmail.com
 *
 */

define("SERVER", "localhost");
define("USERNAME", "root");
define("PASSWORD", "123456");
define("DATABASE", "db_avos");

$mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
if ($mysqli->connect_error) {
	exit("<script>alert('KONEKSI KE SERVER GAGAL !'); window.location.href='logout.php'</script>");
}

?>