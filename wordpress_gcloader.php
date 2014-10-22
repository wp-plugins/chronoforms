<?php
/**
* COMPONENT FILE HEADER
**/
//basic checks
$success = array();
$fails = array();
if(version_compare(PHP_VERSION, '5.3.0') >= 0){
	$success[] = "PHP 5.3.0 or later found.";
}else{
	$fails[] = "Your PHP version is outdated: ".PHP_VERSION;
}
if(phpversion('pdo') !== false AND in_array('mysql', PDO::getAvailableDrivers())){
	$success[] = "PDO Extension is available and enabled and it has MySQL support.";
}else{
	//$fails[] = "PDO Extension is NOT available, disabled or may not have MySQL support.";
}
if(!empty($fails)){
	die("Your PHP version should be 5.3 or later, you must have the PDO extension and PDO MYSQL extension enabled in your PHP config.");
}
//end basic checks
if(empty($fails)){
	function r_($url){
		$alters = array(
			'chronomigrator' => 'com_chronomigrator', 
			'chronoforms' => 'Chronoforms5', 
			'chronoconnectivity' => 'com_chronoconnectivity5', 
			'chronoforums' => 'com_chronoforums', 
		);
		foreach($alters as $k => $v){
			$url = str_replace('ext='.$k, 'page='.$v, $url);
		}
		if(GCORE_SITE == 'front'){
			return $url;//JRoute::_($url);
		}else{
			return $url;
		}
	}
	
	class WordpressGCLoader{
		function __construct($area, $joption, $extension, $setup = null, $cont_vars = array()){
			require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'cegcore'.DIRECTORY_SEPARATOR.'gcloader.php');
			
			if(phpversion('pdo') !== false AND in_array('mysql', PDO::getAvailableDrivers())){
				//good, we use PDO
				\GCore\Libs\Base::setConfig('db_adapter', 'wordpress');
			}else{
				\GCore\Libs\Base::setConfig('db_adapter', 'wordpress');
			}
			
			\GCore\C::set('EXTENSIONS_PATHS', array(
				dirname(__FILE__).DS.'cegcore'.DS.'admin'.DS.'extensions'.DS => dirname(__FILE__).DS.'admin', 
				dirname(__FILE__).DS.'cegcore'.DS.'extensions'.DS => dirname(__FILE__).DS.'front', 
			));
			\GCore\C::set('EXTENSIONS_URLS', array(
				plugins_url().'/'.$joption.'/cegcore/admin/extensions/' => plugins_url().'/'.$joption.'/admin/', 
				plugins_url().'/'.$joption.'/cegcore/extensions/' => plugins_url().'/'.$joption.'/front/', 
			));
			\GCore\C::set('EXTENSIONS_NAMES', array( 
				'chronoforms' => '', 
				'chronoconnectivity' => '', 
				'chronoforums' => '', 
			));
			
			\GCore\Bootstrap::initialize('wordpress', array('component' => $joption, 'ext' => $extension));
			
			$tvout = strlen(\GCore\Libs\Request::data('tvout', null)) > 0 ? \GCore\Libs\Request::data('tvout') : '';
			$controller = \GCore\Libs\Request::data('cont', '');
			$action = \GCore\Libs\Request::data('act', '');
			
			if(is_callable($setup)){
				$return_vars = $setup();
				if(!empty($return_vars)){
					$cont_vars = array_merge($cont_vars, $return_vars);
				}
			}
			if(isset($cont_vars['controller'])){
				$controller = $cont_vars['controller'];
			}
			if(isset($cont_vars['action'])){
				$action = $cont_vars['action'];
			}
			$cont_vars['_app_thread'] = 'gcore';
			ob_start();
			echo \GCore\Libs\AppWp::call($area, $extension, $controller, $action, $cont_vars);
			$output = ob_get_clean();
			
			$output = \GCore\C::fix_urls($output);

			if($tvout == 'ajax'){
				echo $output;
				die();
				
			}else{
				ob_start();
				$toolbar = \GCore\Helpers\Module::render(array('type' => 'toolbar', 'site' => 'admin', 'params' => ''));
				$messages = \GCore\Libs\AppWp::getSystemMessages();;
				echo \GCore\Libs\AppWp::getHeader();
				if($toolbar){
					echo $toolbar;
					echo '<div style="clear:both;"></div>';
				}
				echo $messages;
				//echo \GCore\Libs\AppJ::getHeader();
				$system_output = ob_get_clean();
				$system_output = \GCore\C::fix_urls($system_output);
				echo $system_output;
				echo $output;
			}
		}
	}
}