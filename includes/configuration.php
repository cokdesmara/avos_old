<?php
/*
 * This is php configuration.
 *
 * @author : Cokorda Gde Agung Smara Adnyana Putra
 * @email : cokorda.smara@gmail.com
 *
 */

session_name("avos");
session_start();
if (!ob_start("ob_gzhandler")) ob_start();
error_reporting(1);
define("IS_PATH", true);
?>