<?php

/**
 * @file
 * Theme setting callbacks for the cea00 theme.
 */

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * @param $form
 *   The form.
 * @param $form_state
 *   The form state.
 */
function cea00_form_system_theme_settings_alter(&$form, &$form_state) {

/* Select color schema combo */
  $form['cea00_schema'] = array(
    '#type' => 'select',
	'#default_value' => theme_get_setting('cea00_schema'),
    '#title' => t('Schema'),
    '#options' => array(
      'gray' => t('Gray'),
      'red' => t('Red'),
	  'blue' => t('Blue'),
	  'orange' => t('Orange'),
	  'pink' => t('Pink'),
	  'maroon' => t('Maroon'),
	  'forest' => t('Forest'),
	  'graphite' => t('Graphite'),
    ),
    '#description' => t('Select the color schema.'),
    // Place this above the color scheme options.
    '#weight' => -2,
  );
  
/* Select menu combo */
  $menus_in_combo = array();
  $site_menus = menu_get_names();
  foreach ($site_menus as $id_menu){
  	$array_menu = menu_load($id_menu);
  	if ( $array_menu ){
  		$menus_in_combo[$array_menu['menu_name']] = $array_menu['title'];
  	}
  }
  
  $form['cea00_menu_select'] = array(
    '#type' => 'select',
	'#default_value' => theme_get_setting('cea00_menu_select'),
    '#title' => t('Menu'),
    '#options' => $menus_in_combo,
    '#description' => t('Select the combo menu in the top bar.'),
    '#weight' => -5,
  );
  
  /* Imput top margin */
    $form['cea00_top_margin'] = array(
    '#type' => 'textfield',
	'#default_value' => theme_get_setting('cea00_top_margin'),
	'#size' => 6, 
	'#maxlength' => 3, 
    '#title' => t('Top margin'),
    '#description' => t('Select the top margin value in pixels.'),
    '#weight' => -1,
  );
}


