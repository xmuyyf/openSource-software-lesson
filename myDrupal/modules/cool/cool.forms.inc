<?php

use \Drupal\cool\Loader;

/**
 * Implements hook_forms()
 */
function cool_forms($form_id, $args) {
  $classes = Loader::mapImplementationsAvailable('FormControllers', '\Drupal\cool\Controllers\FormController');
  unset($classes['Drupal\\cool\\BaseForm']);
  unset($classes['Drupal\\cool\\BaseSettingsForm']);
  $forms = array();
  foreach ($classes as $class_name) {
    $forms[$class_name::getId()] = array(
      'callback' => 'cool_default_form_callback',
      'callback arguments' => array($class_name),
    );
  }
  return $forms;
}

/**
 * Default callback to build forms through FormController
 */
function cool_default_form_callback($form, &$form_state, $class_name) {
  $args = array_slice(func_get_args(), 3);
  return call_user_func_array($class_name . '::build', array_merge(array(
    $form,
    $form_state
  ), $args));
}

/**
 * Default submit() callback for FormController
 */
function cool_default_form_validate($form, &$form_state) {
  $class_name = $form_state['values']['cool_class_name'];
  $class_name::validate($form, $form_state);
}

/**
 * Default submit() callback for FormController
 */
function cool_default_form_submit($form, &$form_state) {
  $class_name = $form_state['values']['cool_class_name'];
  $class_name::submit($form, $form_state);
}