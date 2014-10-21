<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Helpers\Themes;
/*** FILE_DIRECT_ACCESS_HEADER ***/
defined("GCORE_SITE") or die;
class None extends \GCore\Helpers\Theme {
	var $view;
	
	function __construct(){
		$doc = \GCore\Libs\Document::getInstance();
		$doc->_('jquery');
	}
	
}