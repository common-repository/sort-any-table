<?php

class bg_sort_table_PluginContext {
	public function __construct(){
	}
	
	/* Admin post URL */
	public function setAdminPostUrl( $adminPostUrl) {
		$this->_adminPostUrl = $adminPostUrl;
	}
	public function getAdminPostUrl() {
		return $this->_adminPostUrl;
	}

	/* Redirect value */
	public function setRedirectValue( $redirectValue) {
		$this->_redirectValue = $redirectValue;
	}	
	public function getRedirectValue() {
		return $this->_redirectValue;
	}

	/* Plugin Version */
	public function setPluginVersion( $pluginVersion) {
		$this->_pluginVersion = $pluginVersion;
	}	
	public function getPluginVersion() {
		return $this->_pluginVersion;
	}
	
	/* Plugin Name */
	public function setPluginName( $pluginName) {
		$this->_pluginName = $pluginName;
	}	
	public function getPluginName() {
		return $this->_pluginName;
	}
	
	/* Plugin Slug */
	public function setPluginSlug( $pluginSlug) {
		$this->_pluginSlug = $pluginSlug;
	}	
	public function getPluginSlug() {
		return $this->_pluginSlug;
	}

	private $_redirectValue = null;
	private $_adminPostUrl = null;
	private $_pluginVersion = null;
	private $_pluginName = null;
	private $_pluginSlug = null;
};

?>