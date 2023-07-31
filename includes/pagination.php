<?php
/*
 * This class can help you to add pagination in your php web page
 * its already integrated with boostrap 2 framework style.
 *
 * @author : Cokorda Gde Agung Smara Adnyana Putra
 * @email : cokorda.smara@gmail.com
 *
 */
 
class _Pagination {
	private $page_url	= "";
	private $page_hash	= "";
	private $page_limit = "";
	private	$page_total = "";
	private $page_range = "";
	private $tag_open 	= "<div class='pagination pagination-centered'><ul>";
	private $tag_first  = "<<";
	private $tag_prev   = "<";
	private $tag_next   = ">";
	private $tag_last   = ">>";
	private $tag_close  = "</ul></div>";
	
	function init($params = array()) {
		if (count($params) > 0) {
			foreach ($params as $key => $val) {
				if (isset($this->$key)) {
					$this->$key = $val;
				}
			}
		}
	}
	
	function offset($limit) {
		if (empty($_GET["p"])) {
			$_GET["p"] = 1;
			$output = 0;
		} else {
			$output = ($_GET["p"]-1) * $limit;
		}
		return $output;
	}

	function paging() {
		$url 	    = $this->page_url;
		$hash 	    = $this->page_hash;
		$page 	    = ceil($this->page_total/$this->page_limit);
		$range      = $this->page_range;
		$current    = (int)$_GET["p"];
		$first_page = $this->tag_first;
		$prev_page  = $this->tag_prev;
		$next_page  = $this->tag_next;
		$last_page  = $this->tag_last;
		$output     = "";
		
	    $output = $this->tag_open;
		
		if ($current > 1) {
			$prev    = $current-1;
			$output .= "<li><a href='javascript:void(0)' onclick=\"window.location.href='".$url."&p=1".$hash."'\">".$first_page."</a></li>
		                <li><a href='javascript:void(0)' onclick=\"window.location.href='".$url."&p=".$prev.$hash."'\">".$prev_page."</a></li>";
		} else { 
			$output .= "<li class='disabled'><span>".$first_page."</span></li>
						<li class='disabled'><span>".$prev_page."</span></li>";
		}

		$num_page = ($current > ($range + 1) ? "<li class='disabled'><span>...</span></li>" : ""); 
		
		for ($i = ($current - $range); $i < (($current + $range) + 1); $i++) {
			if (($i > 0) and ($i <= $page)) {
		    	if ($i == $current) {
					$num_page .= "<li class='active'><span>".$current."</span></li>";
		      	} else {
		      		$num_page .= "<li><a href='javascript:void(0)' onclick=\"window.location.href='".$url."&p=".$i.$hash."'\">".$i."</a></li>";
		      	}
		    }
		}
		
		$num_page .= ($current < ($page - $range) ? "<li class='disabled'><span>...</span></li>" : "");
										      
		$output .= $num_page;
		
		if ($current < $page) {
			$next    = $current+1;
			$output .= "<li><a href='javascript:void(0)' onclick=\"window.location.href='".$url."&p=".$next.$hash."'\">".$next_page."</a></li>
		                <li><a href='javascript:void(0)' onclick=\"window.location.href='".$url."&p=".$page.$hash."'\">".$last_page."</a></li>";
		} else {
			$output .= "<li class='disabled'><span>".$next_page."</span></li>
		                <li class='disabled'><span>".$last_page."</span></li>";
		}
		
		$output = preg_replace("#([^:])//+#", "\\1/", $output.$this->tag_close.PHP_EOL);
		
		return $output;
	}
}

$pagination = new _Pagination;
?>
