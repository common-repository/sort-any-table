<?php

require_once("TabBase.php");

class bg_sort_table_help extends bg_sort_table_TabBase {
	public function __construct( $pluginContext, $tabName) {
		parent::__construct( $tabName, "Help", $pluginContext );
	}
	
	public function display() {
		$this->_html = "<h2>How to use in posts/pages?</h2>";
		$this->_html .= "<p>Click anywhere in the first row of your table and then click the <img src=". plugins_url( "assets/img/tinymce-button.png", __FILE__ ) ." style='vertical-align:bottom;'> button in your editor to customize the shortcode parameters and insert shortcode.</p>";
		$this->_html .= "<p><img src=". plugins_url( "assets/img/add-sorting.png", __FILE__ ) ."> </p>";
		$this->_html .= "<p>Make sure the shortcode appears in the first row of your table. If not, please cut and paste by hand.</p>";
		$this->_html .= "<h2>[bg_sort_this_table] Shortcode Example</h2>";
		$this->_html .= "<p>Free version: [bg_sort_this_table pagination=1]</p>";
		$this->_html .= "<p><a href=\"http://bunte-giraffe.de/sort-any-table\" target=\"_new\">PRO version (3.99 EUR)</a>: [bg_sort_this_table pagination=1 perpage=20 showsearch=0 showinfo=1 responsive=1 lang=\"de\"]</p>";
		$this->_html .= "<h2>[bg_sort_this_table] Shortcode Parameters</h2>";
		$this->_html .= "<p>Free version:</p>";
		$this->_html .= "<ul>";
		$this->_html .= "<li> Use pagination for long tables (by default 10 rows per page are shown): <br><b>pagination</b>=\"1 | 0\" </li></ul>";
		$this->_html .= "<p><a href=\"http://bunte-giraffe.de/sort-any-table\" target=\"_new\">PRO version (3.99 EUR)</a>:</p>";
		$this->_html .= "<ul>";
		$this->_html .= "<li> Number of rows to show per page: <br><b>perpage</b>=\"any number\"</li>";
		$this->_html .= "<li> Show search above the table: <br><b>showsearch</b>=\"1 | 0\" </li>";
		$this->_html .= "<li> Show pagination information (number of rows per page, number of entries displayed): <br><b>showinfo</b>=\"1 | 0\"</li>";
		$this->_html .= "<li> Make table responsive for mobile: <br><b>responsive</b>=\"1 | 0\"</li>";
		$this->_html .= "<li> Language for table sorting interface (please contact for more languages, added free of charge): <br><b>lang</b>=\"en | de | ru | it | fr\"</li>";
		$this->_html .= "</ul>";
		return $this->_html;
		
	}
	
	private $_html = '';
};