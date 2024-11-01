<?php

 /*
  Plugin Name: Sort Any Table
  Plugin URI: http://sort-tables.bunte-giraffe.de
  Description: Add dynamic sorting to your tables with just a button click. No need to modify html, add css classes or restructure tables. Super-easy, super-fast.
  Version: 0.2
  Author: Bunte Giraffe
  Author URI: http://bunte-giraffe.de
  License: GPLv2
  Domain Path: /languages
  Text Domain: sort_any_table
  */
 
/* Make sure we don't expose any info if called directly */
if ( !function_exists( 'add_action' ) ) {
	die("This application is not meant to be called directly!");
}

require_once('ErrorLogging.php');

/* Tabs-related includes */
require_once('PluginContext.php');
require_once('TabManager.php');

require_once('Tab_help.php');

/* Plugin settings */
define( "BG_SRT_TMCE_STYLESHEET_NAME", "bg_sort_table_styleBE-tmce.css");
define( "BG_SRT_TMCE_STYLESHEET_FILE_URL", "assets/css/bg-sort-table.css");
define( "BG_SRT_TMCE_REGISTERED_PLUGIN_NAME", "bg_sort_table_tc_button");
define( "BG_SRT_TMCE_REGISTERED_PLUGIN_FILE_URL", "assets/js/bg-sort-table-mce-plugin.js");
define( "BG_SRT_TMCE_PLUGIN_USER_OPTION_NAME", "rich_editing");

/* Register Wordpress Hooks */
register_uninstall_hook( __FILE__, 'bg_sort_table_plugin_uninstall' );
register_activation_hook( __FILE__, 'bg_sort_table_activate' );
register_deactivation_hook( __FILE__, 'bg_sort_table_deactivate' );

function bg_sort_table_plugin_uninstall() {

}

function bg_sort_table_activate() {

}

function bg_sort_table_deactivate() {

}

/* Adding a custom "Admin Action" to hook to requests sent from plugin's backend */
/* TODO: comment/uncomment relevant action handlers */
$bg_sort_table_registered_admin_actions = array(
	"bg_sort_table_add_plugin_mgmgt_menu" => "admin_menu",
	"bg_sort_table_register_shortcodes" => "init",
	"bg_sort_table_enqueue_scripts" => "wp_enqueue_scripts",
	"bg_sort_table_enqueue_styles" => "wp_enqueue_scripts",	
	/* TinyMCE-related actions */
	"bg_sort_table_add_mce_style" => "admin_enqueue_scripts",
	"bg_sort_table_add_tc_button" => "admin_head"
);

/* Register admin action handlers.
 * One admin action can have multiple handlers.
 * Up on an action the handlers will be called in the same order they were registered.
 * This could be prioritised with add_action() parameter $priority
 */
foreach( $bg_sort_table_registered_admin_actions as $action_handler => $admin_action ) {
	add_action( $admin_action, $action_handler);
}

function bg_sort_table_register_shortcodes() {
	/* List valid shortcodes here and their appropriate tags here */
	$bg_sort_table_registered_shortcodes = array(
		"bg_sort_this_table" => "bg_sort_table_shortcode",
		"BG_SORT_THIS_TABLE" => "bg_sort_table_shortcode"
	);
	/* Register shortcodes */
	foreach( $bg_sort_table_registered_shortcodes as $shortcodeTag => $shortcodeHandler) {
		add_shortcode( $shortcodeTag, $shortcodeHandler);
	}
}

$bgSortableTables = [];

function bg_sort_table_filter($content = null) {
	
	if ( strstr($content, '[bg_sort_this_table') || strstr($content, strtoupper('[bg_sort_this_table')) ) {
						
		$dom = new DOMDocument();
		$dom->validateOnParse = true;
		$dom->resolveExternals = true;
		@$dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
				
		if($dom) {
			if ($dom->getElementsByTagName('table')->length > 0) {
				for ($i = 0; $i<$dom->getElementsByTagName('table')->length; $i++) {
					
					$table = $dom->getElementsByTagName('table')->item($i);
					$manipulateTable = false;
					$showError = false;
					
					$tableHeader = ($table->getElementsByTagName('thead')->length > 0) ? $table->getElementsByTagName('thead')->item(0) : false;
					$tableBody = ($table->getElementsByTagName('tbody')->length > 0) ? $table->getElementsByTagName('tbody')->item(0) : false;
					$bg_uniq_id = uniqid();
					
					if (!$tableBody) {
						$tableBodyTag = $dom->createElement('tbody');
						$table->appendChild($tableBodyTag);
						$tableBody = $table->getElementsByTagName('tbody')->item(0);
					}
					if (!$tableHeader) {
						$tableHeadTag = $dom->createElement('thead');
						$table->insertBefore($tableHeadTag, $tableBody);
						$tableHeader = $table->getElementsByTagName('thead')->item(0);
						$tableHeader->appendChild($table->getElementsByTagName('tr')->item(0));
					}
					
					$headers = ($tableHeader->getElementsByTagName('tr')->item(0)->getElementsByTagName('td')->length > 0) ? $tableHeader->getElementsByTagName('tr')->item(0)->getElementsByTagName('td') : $tableHeader->getElementsByTagName('tr')->item(0)->getElementsByTagName('th');
										
					foreach ($headers as $header) {
						if ( strstr($header->nodeValue, '[bg_sort_this_table') || strstr($header->nodeValue, strtoupper('[bg_sort_this_table')) ) {
							if ( ($header->hasAttribute('colspan') && $header->getAttribute('colspan') > 1) || 
								 ($header->hasAttribute('rowspan') && $header->getAttribute('rowspan') > 1) ) {
									$manipulateTable = false;
									$showError = true;
									break;
							}
							else {
								$header->nodeValue = str_replace('[bg_sort_this_table', '[bg_sort_this_table id="bgSortTable'.$bg_uniq_id.'"', $header->nodeValue);
								$header->nodeValue = str_replace('[BG_SORT_THIS_TABLE', '[bg_sort_this_table id="bgSortTable'.$bg_uniq_id.'"', $header->nodeValue);
								$manipulateTable = true;
							}
						}
						

					}
					
					if ($manipulateTable) {

						$tableCells = $tableBody->getElementsByTagName('td');

						$tableId = $dom->createAttribute('id');
						$tableId->value = 'bgSortTable'.$bg_uniq_id;
						$tableClass = $dom->createAttribute('class');
						$tableClass->value = "table table-striped table-bordered table-hover compact";

						foreach ($tableCells as $tableCell) {
							if ( ($tableCell->hasAttribute('colspan') && $tableCell->getAttribute('colspan') > 1) || 
								 ($tableCell->hasAttribute('rowspan') && $tableCell->getAttribute('rowspan') > 1) ) {
									$tableId->value='';
									$tableClass->value='';
								}
						}
						if ( $tableId->value!= '' && $tableClass->value!='') {
							$dom->getElementsByTagName('table')->item($i)->appendChild($tableId);
							$dom->getElementsByTagName('table')->item($i)->appendChild($tableClass);
						}
						else {
							$showError = true;
						}


					}
					if ($showError) {
							$noSortingPossible = $dom->createElement('div');
							$noSortingPossible->textContent = 'Your table contains colpan or/and rowspan attributes. We currently do not support sorting such tables. This message will be removed when you delete bg_sort_this_table shortcode from your table.';
							$noSortingPossibleWarning = $dom->createAttribute('class');
							$noSortingPossibleWarning->value = 'bg-warning';
							$noSortingPossible->appendChild($noSortingPossibleWarning);
							
							$dom->getElementsByTagName('table')->item($i)->appendChild($noSortingPossible);
							$showError = false;
						}

				}
			}
		}
		
		$content = $dom->saveHTML($dom->documentElement);
		
		$content = do_shortcode($content);	
	}
	 
    // always return
    return $content;
}

function bg_sort_table_shortcode($attr, $content = null) {
	
	$a = shortcode_atts( 
			array( 
					'id' => '',
					'pagination' => '1'
				), 	
			$attr
		);
			
	array_push($GLOBALS['bgSortableTables'], json_encode($a));
	
	wp_localize_script( 'bg-sort-table', 'bgSortableTables', $GLOBALS['bgSortableTables']);
	wp_localize_script( 'bg-sort-table', 'bgPluginUrl', plugins_url( "assets/js/", __FILE__ ));
	
	$content = do_shortcode($content);
 
    // always return
    return $content;
}



function bg_sort_table_add_plugin_mgmgt_menu() {
	add_management_page(										/* Add submenu to Tools menu */
		"Sort Any Table Options:",							/* $page_title */
		"Sort Any Table ", 		/* $menu_title */
		"edit_posts",											/* $capability (aka access rights). 
																 * Other valid values: administrator, editor, author, contributor and subscriber */
		"bg_sort_table",										/* $menu_slug - unique part of the plugin URL that leads directly to the menu */
		"bg_sort_table_menu_handler"							/* $function - menu action handler function (aka main() function)*/
	);
}

function bg_sort_table_add_scripts() {

}

function bg_sort_table_enqueue_scripts() {
	wp_enqueue_script( "bg-sort-table", 
		plugins_url( "assets/js/bg-sort-table.js", __FILE__ ), array('jquery'), false, true
	);
	wp_enqueue_script( "bg-sort-table-dataTables", 
		plugins_url( "assets/js/jquery.dataTables.min.js", __FILE__ ), array('jquery'), false, true
	);
	wp_enqueue_script( "bg-sort-table-dataTables-bootstrap", 
		plugins_url( "assets/js/dataTables.bootstrap.min.js", __FILE__ ), array('jquery'), false, true
	);
	wp_enqueue_script( "bg-sort-table-dataTables-bootstrap-moment", 
		plugins_url( "assets/js/moment.min.js", __FILE__ ), array('jquery'), false, true
	);
	wp_enqueue_script( "bg-sort-table-dataTables-bootstrap-datetime", 
		plugins_url( "assets/js/datetime-moment.js", __FILE__ ), array('jquery'), false, true
	);
		
}

function bg_sort_table_enqueue_styles() {

	wp_enqueue_style( "bg-sort-table", 
		plugins_url( "assets/css/bg-sort-table.css", __FILE__ )
	);
	
	wp_enqueue_style( "bg-sort-table-bootstrap", 
		plugins_url( "assets/css/bootstrap.css", __FILE__ )
	);

	wp_enqueue_style( "bg-sort-table-bootstrap-datatables", 
		plugins_url( "assets/css/dataTables.bootstrap.min.css", __FILE__ )
	);
			
}

/* Tegisterd Wordpress filters */
$bg_sort_table_registered_filters = array(
	"do_shortcode" => "widget_text",
	/* TinyMCE-related filters */
	"bg_sort_table_add_tinymce_plugin" => "mce_external_plugins",
	"bg_sort_table_register_tc_button" => "mce_buttons",
	"bg_sort_table_filter" => "the_content" 
);

foreach( $bg_sort_table_registered_filters as $filterHandler => $filterTag) {
	add_filter( $filterTag, $filterHandler);
}

function bg_sort_table_settings_link($links, $file) {
	if ( $file == plugin_basename( __FILE__  ) ) {
		$links['settings'] = sprintf( '<a href="%s"> %s </a>', admin_url( 'tools.php?page=bg_sort_table' ), __( 'Help', 'plugin_domain' ) );
	}
	
	return $links;
}

add_filter('plugin_action_links', 'bg_sort_table_settings_link', 10, 2);


function bg_sort_table_menu_handler() {

	/* Prepare Plugin Context */
	$pluginContext = new bg_sort_table_PluginContext( );
	$pluginContext->setAdminPostUrl( admin_url( 'admin-post.php' ) );
	$pluginContext->setRedirectValue( $_SERVER['REQUEST_URI'] );
	$pluginContext->setPluginName( "Sort Any Table");
	$pluginContext->setPluginSlug( "bg_sort_table");
	
	$activeTabName = "tab1";
	if( isset( $_GET["tab"] ) ) {
		$activeTabName = $_GET["tab"];
	}
		
	$registeredTabs = array(
		"tab1" => "bg_sort_table_help"
	);
	
	/* Init. Tab Manager and populate it with tabs */
	$tabManager = new bg_sort_table_TabManager( new bg_sort_table_TabView( $pluginContext) );

	foreach( $registeredTabs as $tabName => $tabClass) {
		if( $activeTabName == $tabName) {
			$tabManager->addTab( new $tabClass( $pluginContext, $tabName), true );
		}
		else {
			$tabManager->addTab( new $tabClass( $pluginContext, $tabName) );
		}
	}

	if( !$tabManager->displayActiveTab() ) {
		//echo "DBG: ERROR: Failed to display an active tab";
	}
}

/* Extending TinyMCE */
function bg_sort_table_add_tinymce_plugin( $registeredPlugins) {
	if ( get_user_option( BG_SRT_TMCE_PLUGIN_USER_OPTION_NAME) != 'true') {
		return $registeredPlugins;
	}

	$registeredPlugins[ BG_SRT_TMCE_REGISTERED_PLUGIN_NAME] =
		plugins_url( BG_SRT_TMCE_REGISTERED_PLUGIN_FILE_URL, __FILE__ );

	return $registeredPlugins;
}

function bg_sort_table_register_tc_button( $registeredButtons) {
	if ( get_user_option( BG_SRT_TMCE_PLUGIN_USER_OPTION_NAME) != 'true') {
		return $registeredButtons;
	}

	array_push( $registeredButtons, "bg_sort_table_tc_button");

	return $registeredButtons;
}

function bg_sort_table_add_tc_button() {
	global $typenow;
	
	if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
		return;
    }

	if( !in_array( $typenow, array( 'post', 'page' ) ) ) {
		return;
	}
}

function bg_sort_table_add_mce_style() {
	
	wp_enqueue_style( BG_SRT_TMCE_STYLESHEET_NAME,
		plugins_url( BG_SRT_TMCE_STYLESHEET_FILE_URL, __FILE__ ) );

/* TODO: Populate the tmce poupup here, like (GMSP_MAPS appears in js as a valid js variable) : 
	$maps_raw = get_option( "gmsp-Maps", false );
	wp_localize_script( 'jquery', 'GMSP_MAPS', $maps_raw);
*/
}
/* end TinyMCE button*/

?>