<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Admin\Extensions\Chronoforms\Actions\Html;
/*** FILE_DIRECT_ACCESS_HEADER ***/
defined("GCORE_SITE") or die;
Class Html extends \GCore\Admin\Extensions\Chronoforms\Action{
	static $title = 'HTML (Render Form)';
	var $defaults = array(
		'submit_event' => 'submit',
		'add_form_tags' => 1,
		'page' => 1,
	);

	function execute(&$form, $action_id){
		$config = !empty($form->actions_config[$action_id]) ? $form->actions_config[$action_id] : array();
		$config = new \GCore\Libs\Parameter($config);

		$doc = \GCore\Libs\Document::getInstance();
		$form_id = 'chronoform-'.$form->form['Form']['title'];
		//$doc->_('forms');

		//check fields validation
		/*
		if(!empty($form->form['Form']['extras']['fields'])){
			$validations = array();
			foreach($form->form['Form']['extras']['fields'] as $k => $field){
				if(!empty($field['validation'])){
					foreach($field['validation'] as $rule => $rule_data){
						$validations[$rule][] = $field['name'].(strlen(trim($rule_data)) > 0 ? ':'.$rule_data : ':');
					}
				}
				if(!empty($field['inputs'])){
					foreach($field['inputs'] as $fn => $field_input){
						if(!empty($field_input['validation'])){
							foreach($field_input['validation'] as $rule => $rule_data){
								$validations[$rule][] = $field_input['name'].(strlen(trim($rule_data)) > 0 ? ':'.$rule_data : ':');
							}
						}
					}
				}
			}
			foreach($validations as $rule => &$fields){
				$fields = implode("\n", $fields);
			}
			$form->execute('client_validation', array('rules' => $validations));
		}
		*/
		$theme = '';
		if($form->params->get('theme', 'bootstrap3') == 'bootstrap3'){
			$theme = 'bootstrap3';
		}else if($form->params->get('theme', 'bootstrap3') == 'semantic1'){
			$theme = 'semantic1';
		}else if($form->params->get('theme', 'bootstrap3') == 'gcoreui'){
			$theme = 'gcoreui';
		}else if($form->params->get('theme', 'bootstrap3') == 'none'){
			$theme = 'none';
		}
		$doc->theme = $theme;
		\GCore\Helpers\Theme::getInstance();
		if($form->params->get('tight_layout', 0)){
			$doc->addCssCode('
				.gbs3 .gcore-form-row{margin-bottom:5px;}
				.gcore-form-row .gcore-line-td{margin:0;}
			');
		}
		if($form->params->get('rtl_support', 0)){
			$doc->addCssCode('
				#'.$form_id.'.chronoform{direction:rtl;}
			');
			if($form->params->get('theme', 'bootstrap3') == 'bootstrap3'){
				$doc->addCssCode('
					#'.$form_id.' .gcore-label-left{
						float:right !important;
						min-width:160px;
						max-width:160px;
						padding-left:7px;
						text-align:right !important;
					}
					#'.$form_id.' .gcore-label-top{
						display:block;
						text-align:right !important;
						float:none !important;
						width:auto !important;
					}
					#'.$form_id.' .gcore-form-row > .gcore-label-checkbox{
						float:right !important;
						min-width:160px;
						padding-right:7px;
						padding-top: 1px !important;
						text-align:right !important;
					}
					#'.$form_id.' .gcore-subinput-container{
						float:right;
					}
					#'.$form_id.' .gcore-multiple-column .gcore-checkbox-item, .gcore-multiple-column .gcore-radio-item{
						float: right;
					}
					#'.$form_id.' .gcore-multiple-column .gcore-checkbox-item:not(:first-child), .gcore-multiple-column .gcore-radio-item:not(:first-child){
						padding-right: 5px;
					}
				');
			}
			if($form->params->get('theme', 'bootstrap3') == 'gcoreui'){
				$doc->addCssCode('
					#'.$form_id.' .gcore-label-left {
						min-width: 150px !important;
						max-width: 150px !important;
						display: inline-block;
						white-space: normal;
						float: right !important;
						padding: 1px;
						padding-right: 10px !important;
						font-weight: bold;
					}
					#'.$form_id.' .gcore-input-container {
						float: right;
						overflow: auto;
						display: inline-block;
						white-space: normal;
					}
					#'.$form_id.' .gcore-line-tr .gcore-input{
						float:right;
					}
					#'.$form_id.' .gcore-subinput-container {
						margin-bottom: 3px;
						overflow: auto;
						float: right;
					}
					#'.$form_id.' .gcore-subinput-container:not(:first-child) {
						padding-right: 4px;
					}
					#'.$form_id.' .gcore-subinput-container-wide {
						display: inline-block;
						margin: 0px 3px 3px 0px;
						float: right;
						overflow: auto;
					}
					#'.$form_id.' .gcore-radio-item,
					#'.$form_id.' .gcore-checkbox-item {
						float: right;
						margin: 0px 6px 6px 0px;
						white-space: nowrap;
					}
					#'.$form_id.' .gcore-single-column .gcore-radio-item,
					#'.$form_id.' .gcore-single-column .gcore-checkbox-item {
						clear: right;
					}
				');
			}
		}
		if($form->params->get('labels_right_aligned', 0)){
			$doc->addCssCode('
				#'.$form_id.' .gcore-label-left{
					text-align:'.($form->params->get('rtl_support', 0) ? 'left' : 'right').' !important;
				}
			');
		}
		if($form->params->get('labels_auto_width', 0)){
			$doc->addCssCode('
				#'.$form_id.' .gcore-label-left{
					min-width: 0px !important;
					max-width: none !important;
					width: auto !important;
				}
			');
		}
		if($form->params->get('responsive_layout', 0)){
			$responsive_css = '
				#'.$form_id.' .gcore-input{width:100% !important;}
				#'.$form_id.' .form-control{width:100% !important;}
			';
			foreach($form->form['Form']['extras']['fields'] as $k => $field){
				if(in_array($field['type'], array('submit', 'button'))){
					$responsive_css .= '#'.$form_id.' #fin-'.$field['id'].'{width:auto !important;}';
				}
			}
			$doc->addCssCode($responsive_css);
		}
		
		if($form->params->get('js_validation_language', '') == ''){
			$lang = strtolower(\GCore\Libs\Base::getConfig('site_language'));
			$js_lang_tag = explode('-', $lang);
			$form->params->set('js_validation_language', $js_lang_tag[0]);
		}

		//check fields events
		if(!empty($form->form['Form']['extras']['fields'])){
			$events_codes = array();
			$pageload_events_codes = array();
			//$events_codes[] = 'jQuery(document).ready(function($){';
			$events_codes[] = 'function chronoforms_fields_events(){';
			foreach($form->form['Form']['extras']['fields'] as $k => $field){
				if(!empty($field['id']) AND !empty($field['events'])){
					if($field['type'] == 'dropdown'){
						$change_event = 'change';
					}else{
						$change_event = 'click';
					}
					$_f = '$("[name=\''.$field['name'].'\']").on("'.$change_event.'", function(){';
					$_l = '});';
					$_m = array();
					foreach($field['events'] as $k => $event_data){
						if(/*strlen($event_data['state']) AND */strlen($event_data['action']) AND strlen($event_data['target'])){
							$_m[] = $this->create_event($field, $event_data, $form);
						}
					}
					if(!empty($_m)){
						$events_codes[] = $_f."\n".implode("\n", $_m)."\n".$_l;
						$pageload_events_codes[] = implode("\n", $_m);
					}
				}
				if(!empty($field['inputs'])){
					foreach($field['inputs'] as $fn => $field_input){
						if(!empty($field_input['id']) AND !empty($field_input['events'])){
							if($field_input['type'] == 'dropdown'){
								$change_event = 'change';
							}else{
								$change_event = 'click';
							}
							$_f = '$("[name=\''.$field_input['name'].'\']").on("'.$change_event.'", function(){';
							$_l = '});';
							$_m = array();
							foreach($field_input['events'] as $k => $event_data){
								if(/*strlen($event_data['state']) AND */strlen($event_data['action']) AND strlen($event_data['target'])){
									$_m[] = $this->create_event($field_input, $event_data, $form);
								}
							}
							if(!empty($_m)){
								$events_codes[] = $_f."\n".implode("\n", $_m)."\n".$_l;
								$pageload_events_codes[] = implode("\n", $_m);
							}
						}
					}
				}
			}
			$events_codes[] = '}';
			$events_codes[] = 'chronoforms_fields_events();';
			$events_codes[] = 'function chronoforms_pageload_fields_events(){';
			$events_codes[] = implode("\n", $pageload_events_codes);
			$events_codes[] = '}';
			$events_codes[] = 'chronoforms_pageload_fields_events();';
			
			//$form->execute('js', array('content' => implode("\n", $events_codes)));
		}

		ob_start();
		eval('?>'.$form->form['Form']['content']);
		$output = ob_get_clean();
		$form_content = $output;
		//select the page to display
		$form_pages = explode('<!--_CHRONOFORMS_PAGE_BREAK_-->', $output);
		$active_page_index = (int)$config->get('page', 1) - 1;
		$output = $form_pages[$active_page_index];
		//get current url
		$current_url = \GCore\Libs\Url::current();
		if((bool)$config->get('relative_url', 1) === false){
			$current_url = r_('index.php?ext=chronoforms');
		}
		//generate <form tag
		$form_tag = '<form';
		$form_action = (strlen($config->get('action_url', '')) > 0) ? $config->get('action_url', '') : \GCore\Libs\Url::buildQuery($current_url, array('chronoform' => $form->form['Form']['title'], 'event' => $config->get('submit_event', 'submit')));

		$form_tag .= ' action="'.r_($form_action).'"';
		//get method
		$form_method = $config->get('form_method', 'post');
		if($config->get('form_method', 'post') == 'file'){
			$form_tag .= ' enctype="multipart/form-data"';
			$form_method = 'post';
		}
		$form_tag .= ' method="'.$form_method.'"';
		$form_tag .= ' name="'.$form->form['Form']['title'].'"';
		//$form_id = 'chronoform-'.$form->form['Form']['title'];
		$form_tag .= ' id="'.$form_id.'"';
		$form_tag .= ' class="'.$config->get('form_class', 'chronoform').(($theme == 'bootstrap3') ? ' form-horizontal' : '').'"';
		if($config->get('form_tag_attach', '')){
			$form_tag .= $config->get('form_tag_attach', '');
		}

		$form_tag .= '>';

		if(empty($theme)){
			$doc->_('forms');
		}
		/*
		if($theme == 'bootstrap3'){
			$doc->_('jquery');
			$doc->_('bootstrap');
			//echo '<div class="gcore chronoform-container">';
		}
		*/
		if(!empty($events_codes)){
			$js_scripts = array(implode("\n", $events_codes));
		}else{
			$js_scripts = array();
		}
		if(strpos($output, 'data-wysiwyg="1"') !== false){
			$doc->_('jquery');
			$doc->_('editor');
			$js_scripts[] = '$(\'*[data-wysiwyg="1"]\').each(function(){ tinymce.init({"selector":"#"+$(this).attr("id")}); });';
		}
		if(strpos($form_content, 'validate[') !== false){
			$doc->_('jquery');
			$doc->_('gtooltip');
			$doc->_('gvalidation', array('lang' => $form->params->get('js_validation_language', 'en')));
			$js_scripts[] = '$("#chronoform-'.$form->form['Form']['title'].'").gvalidate();';
			if($config->get('required_labels_identify', 1)){
				if($form->params->get('theme', 'bootstrap3') == 'bootstrap3'){
					$required_icon = '<i class=\'fa fa-asterisk\' style=\'color:#ff0000; font-size:9px; vertical-align:top;\'></i>';
				}else{
					$required_icon = '<span style=\'color:#ff0000; font-size:12px; vertical-align:top;\'>*</span>';
				}
				$js_scripts[] = '
					function chronoforms_validation_signs(formObj){
						formObj.find(":input[class*=validate]").each(function(){
							if($(this).attr("class").indexOf("required") >= 0 || $(this).attr("class").indexOf("group") >= 0){
								var required_parent = [];
								if($(this).closest(".gcore-subinput-container").length > 0){
									var required_parent = $(this).closest(".gcore-subinput-container");
								}else if($(this).closest(".gcore-form-row").length > 0){
									var required_parent = $(this).closest(".gcore-form-row");
								}
								if(required_parent.length > 0){
									var required_label = required_parent.find("label");
									if(required_label.length > 0 && !required_label.first().hasClass("required_label")){
										required_label.first().addClass("required_label");
										required_label.first().html(required_label.first().html() + " '.$required_icon.'");
									}
								}
							}
						});
					}
					chronoforms_validation_signs($("#chronoform-'.$form->form['Form']['title'].'"));
				';
			}
		}
		if(strpos($form_content, 'data-tooltip') !== false){
			$doc->_('jquery');
			$doc->_('gtooltip');
			$js_scripts[] = '
				function chronoforms_data_tooltip(formObj){
					formObj.find(":input").each(function(){
						if($(this).data("tooltip") && $(this).closest(".gcore-input, .gcore-input-wide").length > 0 && $(this).closest(".gcore-input, .gcore-input-wide").next(".input-tooltip").length < 1){
							var $tip = $(\'<i class="fa fa-exclamation-circle input-tooltip" style="float:left; padding:7px 0px 0px 7px;"></i>\').attr("title", $(this).data("tooltip"));
							$(this).closest(".gcore-input, .gcore-input-wide").after($tip);
							$(this).closest(".gcore-input, .gcore-input-wide").css("float", "left");
						}
					});
					formObj.find(".input-tooltip").gtooltip("hover");
				}
				chronoforms_data_tooltip($("#chronoform-'.$form->form['Form']['title'].'"));
			';
		}
		if(strpos($form_content, 'data-load-state') !== false){
			$doc->_('jquery');
			$js_scripts[] = '
				function chronoforms_data_loadstate(formObj){
					formObj.find(\':input[data-load-state="disabled"]\').prop("disabled", true);
					formObj.find(\'*[data-load-state="hidden"]\').css("display", "none");
					formObj.find(\':input[data-load-state="hidden_parent"]\').each(function(){
						if($(this).closest(".gcore-subinput-container").length > 0){
							$(this).closest(".gcore-subinput-container").css("display", "none");
						}else if($(this).closest(".gcore-form-row").length > 0){
							$(this).closest(".gcore-form-row").css("display", "none");
						}
					});
				}
				chronoforms_data_loadstate($("#chronoform-'.$form->form['Form']['title'].'"));
			';
		}
		if(strpos($output, 'data-inputmask=') !== false){
			$doc->_('jquery');
			$doc->_('jquery.inputmask');
			$js_scripts[] = '$(":input").inputmask();';
		}
		if(strpos($output, 'data-gdatetimepicker') !== false){
			$doc->_('jquery');
			$doc->_('gdatetimepicker');
			$js_scripts[] = '$(\'*[data-gdatetimepicker="1"]\').gdatetimepicker();';
		}
		if(strpos($output, 'multiplier-add-button') !== false){
			$doc->_('jquery');
			$js_scripts[] = '
				$(".multiplier-add-button").on("click", function(){
					var multiplier_clone = $(this).parent().find(".multiplier-contents").first().clone();
					if(typeof($(this).data("replacer")) != "undefined"){
						var multiplier_clone = multiplier_clone.wrap("<p>").parent().html().replace(new RegExp($(this).data("replacer"), "g"), $(this).data("count"));
						$(this).data("count", parseInt($(this).data("count")) + 1);
					}
					$(this).parent().find(".multiplier-contents").last().after(multiplier_clone);
				});
				$(document).on("click", ".multiplier-remove-button", function(){
					$(this).parent().remove();
				});
			';
		}
		if((bool)$config->get('ajax_submit', 0) === true){
			$doc->_('jquery');
			$doc->_('gtooltip');
			$doc->_('gvalidation', array('lang' => $form->params->get('js_validation_language', 'en')));
			
			$ajax_url = \GCore\Libs\Url::buildQuery($form_action, array('tvout' => 'ajax'));
			$js_scripts[] = '
					function chrono_ajax_submit(){
						$(document).on("click", "#'.$form_id.' :input[type=submit]", function(event){
							$("#'.$form_id.'").append("<input type=\'hidden\' name=\'"+$(this).attr("name")+"\' value=\'"+$(this).val()+"\' />");
						});
						
						var files;
						$("input[type=file]").on("change", function(event){
							files = event.target.files;
						});
						
						$(document).on("submit", "#'.$form_id.'", function(event){
							var overlay = $("<div/>").css({
								"position": "fixed",
								"top": "0",
								"left": "0",
								"width": "100%",
								"height": "100%",
								"background-color": "#000",
								"filter": "alpha(opacity=50)",
								"-moz-opacity": "0.5",
								"-khtml-opacity": "0.5",
								"opacity": "0.5",
								"z-index": "10000",
								"background-image":"url(\"'.\GCore\Helpers\Assets::image('loading-small.gif').'\")",
								"background-position":"center center",
								"background-repeat":"no-repeat",
							});
							if(!$("#'.$form_id.'").hasClass("form-overlayed")){
								$("#'.$form_id.'").append(overlay);
								$("#'.$form_id.'").addClass("form-overlayed");
							}
							var form_action = $("#'.$form_id.'").prop("action");
							var sep = (form_action.indexOf("?") > -1) ? "&" : "?";
							var ajax_url = form_action + sep + "tvout=ajax";
							
							//data processing
							$.ajax({
								"type" : "POST",
								"url" : ajax_url,
								"data" : $("#'.$form_id.'").serialize(),
								"success" : function(res){
									$("#'.$form_id.'").replaceWith(res);
									$("#'.$form_id.'").gvalidate();
									chronoforms_fields_events();
									chronoforms_validation_signs($("#'.$form_id.'"));
									chronoforms_data_tooltip($("#'.$form_id.'"));
									chronoforms_data_loadstate($("#'.$form_id.'"));
									//chrono_ajax_submit();//this line duplicates submissions, should be removed
								},
							});
							return false;
						});
					}
					chrono_ajax_submit();
				';
		}
		if(!empty($js_scripts)){
			$doc->addJsCode('jQuery(document).ready(function($){
				'.implode("\n", $js_scripts).'
			});');	
		}
		
		if((bool)$config->get('add_form_tags', 1) === true){
			echo $form_tag;
		}
		//if ajax then display system messages inside the form
		if((bool)$config->get('ajax_submit', 0) === true){
			$doc = \GCore\Libs\Document::getInstance();
			$doc->addCssFile('system_messages');
			$session = \GCore\Libs\Base::getSession();
			$types = $session->getFlash();
			echo \GCore\Helpers\Message::render($types);
		}
		//add fields values
		$output = \GCore\Helpers\DataLoader::load($output, $form->data);
		//show output
		echo $output;
		if((bool)$config->get('add_form_tags', 1) === true){
			echo '</form>';
		}
	}

	function create_event($field, $event_data, $form){
		$return = '';
		$form_id = 'chronoform-'.$form->form['Form']['title'];
		if(empty($event_data['operator'])){
			$event_data['operator'] = '=';
		}
		if($event_data['state'] == 'check'){
			$return .= 'if($("[name=\''.$field['name'].'\']").prop("checked"))';
		}else if($event_data['state'] == 'uncheck'){
			$return .= 'if(!$("[name=\''.$field['name'].'\']").prop("checked"))';
		}else{
			if($field['type'] == 'checkbox_group'){
				$operator = ($event_data['operator'] == '=') ? '=' : $event_data['operator'];
				$return .= 'if($("[name=\''.$field['name'].'\'][value'.$operator.'\''.$event_data['state'].'\']").prop("checked"))';
			}else{
				$operator = ($event_data['operator'] == '=') ? '==' : $event_data['operator'];
				$return .= 'if($("[name=\''.$field['name'].'\']").val() '.$operator.' "'.$event_data['state'].'")';
			}
		}
		$return .= '{'."\n";
		$target_field = '$("#'.$event_data['target'].'")';
		$target = '$("#fin-'.$event_data['target'].', #'.$event_data['target'].'")';
		if($event_data['action'] == 'enable'){
			$return .= $target_field.'.prop("disabled", false);';
		}
		if($event_data['action'] == 'disable'){
			$return .= $target_field.'.prop("disabled", true);';
		}
		if($event_data['action'] == 'show'){
			$return .= $target.'.css("display", "");';
		}
		if($event_data['action'] == 'show_parent'){
			$return .= 'if('.$target.'.closest(".gcore-subinput-container").length > 0){
				'.$target.'.closest(".gcore-subinput-container").css("display", "");
			}else if('.$target.'.closest(".gcore-form-row").length > 0){
				'.$target.'.closest(".gcore-form-row").css("display", "");
			}';
		}
		if($event_data['action'] == 'hide'){
			$return .= $target.'.css("display", "none");';
		}
		if($event_data['action'] == 'hide_parent'){
			$return .= 'if('.$target.'.closest(".gcore-subinput-container").length > 0){
				'.$target.'.closest(".gcore-subinput-container").css("display", "none");
			}else if('.$target.'.closest(".gcore-form-row").length > 0){
				'.$target.'.closest(".gcore-form-row").css("display", "none");
			}';
		}
		if($event_data['action'] == 'set_options'){
			$return .= $target_field.'.find("option").remove();';
			$options = array();
			if(!empty($event_data['options'])){
				$lines = explode("\n", $event_data['options']);
				foreach($lines as $line){
					$opts = explode("=", $line);
					$options[$opts[0]] = $opts[1];
					$return .= $target_field.'.append(\'<option value="'.$opts[0].'">'.trim($opts[1]).'</option>\');'."\n";
				}
			}
		}
		if($event_data['action'] == 'set_dynamic_options'){
			$return .= $target_field.'.find("option").remove();';
			$options = array();
			if(!empty($event_data['options'])){
				$ajax_event = $event_data['options'];
				$return .= '
				$.ajax({
					"type" : "GET",
					"url" : "'.r_('index.php?ext=chronoforms&chronoform='.$form->form['Form']['title'].'&event='.$ajax_event.'&tvout=ajax').'",
					//"data" : {"'.$field['name'].'":$("#'.$field['id'].'").val()},
					"data" : $("#'.$form_id.'").serialize(),
					"success" : function(res){
						$.each($.parseJSON(res), function(id, val){
							'.$target_field.'.append(\'<option value="\'+id+\'">\'+val+\'</option>\');
						});
					},
				});';
				
			}
		}
		if($event_data['action'] == 'function'){
			$return .= $event_data['target'].';';
		}
		$return .= "\n".'}';
		return $return;
	}

	public static function config(){
		echo \GCore\Helpers\Html::formStart('action_config html_action_config', 'html_action_config_{N}');
		echo \GCore\Helpers\Html::formSecStart();

		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][page]', array('type' => 'text', 'label' => l_('CF_PAGE'), 'value' => 1, 'sublabel' => l_('CF_PAGE_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][submit_event]', array('type' => 'text', 'label' => l_('CF_SUBMIT_EVENT'), 'value' => 'submit', 'sublabel' => l_('CF_SUBMIT_EVENT_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][form_method]', array('type' => 'dropdown', 'label' => l_('CF_FORM_METHOD'), 'options' => array('file' => 'File', 'post' => 'Post', 'get' => 'Get'), 'sublabel' => l_('CF_FORM_METHOD_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][action_url]', array('type' => 'text', 'label' => l_('CF_ACTION_URL'), 'class' => 'XL', 'sublabel' => l_('CF_ACTION_URL_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][form_class]', array('type' => 'text', 'label' => l_('CF_FORM_CLASS'), 'value' => 'chronoform', 'sublabel' => l_('CF_FORM_CLASS_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][form_tag_attach]', array('type' => 'text', 'label' => l_('CF_FORM_TAG_ATTACHMENT'), 'class' => 'XL', 'rows' => 1, 'sublabel' => l_('CF_FORM_TAG_ATTACHMENT_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][required_labels_identify]', array('type' => 'dropdown', 'label' => l_('CF_REQUIRED_LABELS_IDENTIFY'), 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'values' => 1, 'sublabel' => l_('CF_REQUIRED_LABELS_IDENTIFY_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][relative_url]', array('type' => 'dropdown', 'label' => l_('CF_RELATIVE_URL'), 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'values' => 1, 'sublabel' => l_('CF_RELATIVE_URL_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][ajax_submit]', array('type' => 'dropdown', 'label' => l_('CF_AJAX_SUBMIT'), 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'values' => 0, 'sublabel' => l_('CF_AJAX_SUBMIT_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][add_form_tags]', array('type' => 'dropdown', 'label' => l_('CF_ADD_FORM_TAGS'), 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'values' => 1, 'sublabel' => l_('CF_ADD_FORM_TAGS_DESC')));

		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
	
	public static function config_check($data = array()){
		$diags = array();
		$diags[l_('CF_DIAG_PAGE')] = !empty($data['page']) ? $data['page'] : 1;
		$diags[l_('CF_DIAG_SUBMIT_EVENT')] = !empty($data['submit_event']) ? $data['submit_event'] : 'submit';
		$diags[l_('CF_DIAG_ACTION_URL')] = empty($data['action_url']);
		$diags[l_('CF_DIAG_AJAX')] = !empty($data['ajax_submit']) ? $data['ajax_submit'] : 0;
		return $diags;
	}
}