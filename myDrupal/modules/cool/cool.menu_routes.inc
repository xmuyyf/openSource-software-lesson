<?php

use \Drupal\cool\Loader;

/**
 * Implements hook_menu().
 */
function cool_menu() {
  $items = array();
  $classes_pages = Loader::mapImplementationsAvailable('PageControllers', '\Drupal\cool\Controllers\PageController');
  $classes_form_pages = Loader::mapSubclassesAvailable('FormControllers', '\Drupal\cool\BaseFormPage');
  $classes = array_merge($classes_pages, $classes_form_pages);
  foreach ($classes as $class_name) {
    if (method_exists($class_name, 'getDefinition')) {
      if (!method_exists($class_name, 'pageCallback')) {
        throw new Exception(t('Class @classname do not have the pageCallback method', array('@classname' => $class_name)));
      }
      $items[$class_name::getPath()] = array_merge(
        array(
          'page callback' => $class_name . '::pageCallback',
          'access callback' => 'cool_default_page_access_callback',
          'access arguments' => array($class_name),
        ), $class_name::getDefinition()
      );
    }
  }
  return $items;
}

/**
 * Default function to call the page access callback from page controller classes
 * @param string $class_name
 */
function cool_default_page_access_callback($class_name) {
  return $class_name::accessCallback();
}