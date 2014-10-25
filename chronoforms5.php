<?php
/*
 * Plugin Name: Chronoforms5
 * Plugin URI: http://www.chronoengine.com
 * Description: Add any type of forms to your WordPress site quickly & effeciently.
 * Author: chronoengine.com
 * Author URI: http://www.chronoengine.com
 * Version: 5.0.3
 * License: GPL3
 * License URI: http://www.gnu.org/licenses/gpl.html
 */

class Chronoforms5{
	function Chronoforms5(){
		add_action('init', array($this, 'cf5_global')); //global initializing
		add_action('admin_init', array($this, 'cf5_admin')); //admin initializing
		add_shortcode('Chronoforms5', array($this, 'cf5_front'));
		add_action('admin_menu', array($this, 'cf5_admin_menu'));
		
		if(!is_admin()){
			if(!empty($_GET['page']) AND $_GET['page'] == 'Chronoforms5' AND !empty($_GET['chronoform'])){
				add_filter('the_content', array($this, 'cf5_preview_post_content'));
				add_filter('posts_results', array($this, 'cf5_preview_trim_posts'));
			}
			if(!empty($_GET['tvout']) AND $_GET['tvout'] == 'ajax'){
				add_filter('parse_request', array($this, 'cf5_front_ajax'));
			}
		}
	}
	
	function cf5_admin_menu(){
		add_menu_page('Chronoforms5', 'Chronoforms5', 'administrator', 'Chronoforms5', array($this, 'cf5_output'));
	}
	
	function cf5_global(){
		defined("GCORE_SITE") or define("GCORE_SITE", "admin");
		require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'wordpress_gcloader.php');
		if(!class_exists('WordpressGCLoader')){
			die("Please download the CEGCore framework from www.chronoengine.com then install it using the 'Extensions Manager'");
			return;
		}
		require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'cegcore'.DIRECTORY_SEPARATOR.'gcloader.php');
		$session = \GCore\Libs\Base::getSession();
		/*global $chronoforms5_output;
		ob_start();
		$output = new WordpressGCLoader('admin', '', '');
		$chronoforms5_output = ob_get_clean();*/
	}
	
	function cf5_preview_post_content($content){
		echo $this->cf5_front();
		echo '<br><br><br>';
		echo $content;
	}
	
	function cf5_preview_trim_posts($posts){
		return array(array_pop($posts));
	}
	
	function cf5_front_ajax(){
		echo $this->cf5_front();
		die();
	}
	
	function cf5_admin(){
		/*defined("GCORE_SITE") or define("GCORE_SITE", "admin");
		require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'wordpress_gcloader.php');
		if(!class_exists('WordpressGCLoader')){
			die("Please download the CEGCore framework from www.chronoengine.com then install it using the 'Extensions Manager'");
			return;
		}*/
		global $wpdb;
		$database = new \GCore\Libs\DatabaseAdapters\Wordpress();
		$tables = $database->getTablesList();
		if(\GCore\Libs\Request::data('cont') != 'installer'){
			if(!empty($tables) AND !in_array($wpdb->prefix.'chronoengine_chronoforms', $tables)){
				\GCore\Libs\Env::redirect(r_('index.php?ext=chronoforms&cont=installer'));
			}
		}
		
		global $chronoforms5_output;
		ob_start();
		$output = new WordpressGCLoader('admin', self::get_plugin_name(), 'chronoforms');
		$chronoforms5_output = ob_get_clean();
	}
	
	function cf5_front($attrs = array()){
		/*defined("GCORE_SITE") or define("GCORE_SITE", "admin");
		require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'wordpress_gcloader.php');
		if(!class_exists('WordpressGCLoader')){
			die("Please download the CEGCore framework from www.chronoengine.com then install it using the 'Extensions Manager'");
			return;
		}
		*/
		$chronoforms5_setup = function() use ($attrs){
			$chronoform = GCore\Libs\Request::data('chronoform', '');
			$event = GCore\Libs\Request::data('event', '');
			$params = new \GCore\Libs\Parameter($attrs);
			$formname = $params->get('chronoform', '');
			if(!empty($formname)){
				if(!empty($event)){
					if($formname == $chronoform){
						return array('chronoform' => $params->get('chronoform'), 'event' => $event);
					}
				}
				return array('chronoform' => $params->get('chronoform'), 'event' => $params->get('event') ? $params->get('event') : 'load');
			}
		};

		global $chronoforms5_output;
		ob_start();
		$output = new WordpressGCLoader('front', self::get_plugin_name(), 'chronoforms', $chronoforms5_setup);
		return $chronoforms5_output = ob_get_clean();
		//$this->cf5_output();
	}
	
	function cf5_output(){
		global $chronoforms5_output;
		echo $chronoforms5_output;
	}
	
	function get_plugin_name(){
		$plugin_path = plugin_dir_url(__FILE__);
		$plugin_path_parts = array_filter(explode('/', $plugin_path));
		$plugin_name = array_pop($plugin_path_parts);
		return $plugin_name;
	}
}
$Chronoforms5 = new Chronoforms5();
//add_shortcode('Chronoforms5', array($Chronoforms5, 'run'));
?>
