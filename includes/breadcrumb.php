<?php
/*
 * This class can help you to add breadcrumb in your php web page
 * its already integrated with boostrap 2 framework style.
 *
 * @author : Cokorda Gde Agung Smara Adnyana Putra
 * @email : cokorda.smara@gmail.com
 *
 */

class _Breadcrumb {
	private $breadcrumbs = array();
	private $_divider 	 = "";
	private $_tag_open 	 = "<ul class='breadcrumb'>";
	private $_tag_close  = "</ul>";
	
	private function init($params = array()) {
		if (count($params) > 0) {
			foreach ($params as $key => $val) {
				if (isset($this->$key)) {
					$this->$key = $val;
				}
			}
		}
	}
	
	function append_crumb($title, $href) {
		if (!$title or !$href) return;
		$this->breadcrumbs[] = array("title" => $title, "href" => $href);
	}
	
	function prepend_crumb($title, $href) {
		if (!$title or !$href) return;
		array_unshift($this->breadcrumbs, array("title" => $title, "href" => $href));
	}
	
	function breadcrumb() {
		if ($this->breadcrumbs) {
			
			$output = $this->_tag_open;
			
			foreach ($this->breadcrumbs as $key => $crumb) {
				if ($key) $output .= $this->_divider;
				
				if (end(array_keys($this->breadcrumbs)) != $key) {
					$output .= "<li><a href='".$crumb["href"]."'>".$crumb["title"]."</a><span class='divider'>/</span></li>";
				} else {
					$output .= "<li class='active'>".$crumb["title"]."</li>";
				}
			}
			return $output.$this->_tag_close.PHP_EOL;
		}
		return "";
	}
}

$breadcrumb = new _Breadcrumb;
?>