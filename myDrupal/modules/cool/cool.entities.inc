<?php

use \Drupal\cool\Loader;

/**
 * Implements hook_entity_info_alter().
 */
function cool_entity_info_alter(&$entity_info) {
  $classes = Loader::mapSubclassesAvailable('Models', '\Drupal\cool\Models\BaseModel');
  foreach ($classes as $class_name) {
    if (
      property_exists($class_name, 'base_model_entity_type')
      && method_exists($class_name, 'accessCallback')
    ) {
      $entity_info[$class_name::$base_model_entity_type]['access callback'] = 'cool_model_access_callback';
      break;
    }
  }
}

function cool_model_access_callback($op, $entity, $account, $entity_type_name) {
  $classes = Loader::mapSubclassesAvailable('Models', '\Drupal\cool\Models\BaseModel');
  foreach ($classes as $class_name) {
    if (
      property_exists($class_name, 'base_model_entity_type')
      && $class_name::$base_model_entity_type == $entity_type_name
      && method_exists($class_name, 'accessCallback')
    ) {
      return $class_name::accessCallback($op, $entity, $account, $entity_type_name);
      break;
    }
  }
  return FALSE;
}

/**
 * Implements hook_entity_insert().
 */
function cool_entity_insert($entity, $type) {
  $classes = Loader::mapSubclassesAvailable('Models', '\Drupal\cool\Models\BaseModel');
  foreach ($classes as $class_name) {
    if (
      property_exists($class_name, 'base_model_entity_type')
      && $class_name::$base_model_entity_type == $type
      && method_exists($class_name, 'onInsert')
    ) {
      $class_name::onInsert($entity);
      break;
    }
  }
}

/**
 * Implements hook_entity_update().
 */
function cool_entity_update($entity, $type) {
  $classes = Loader::mapSubclassesAvailable('Models', '\Drupal\cool\Models\BaseModel');
  foreach ($classes as $class_name) {
    if (
      property_exists($class_name, 'base_model_entity_type')
      && $class_name::$base_model_entity_type == $type
      && method_exists($class_name, 'onUpdate')
    ) {
      $class_name::onUpdate($entity);
      break;
    }
  }
}

/**
 * Implements hook_entity_delete().
 */
function cool_entity_delete($entity, $type) {
  $classes = Loader::mapSubclassesAvailable('Models', '\Drupal\cool\Models\BaseModel');
  foreach ($classes as $class_name) {
    if (
      property_exists($class_name, 'base_model_entity_type')
      && $class_name::$base_model_entity_type == $type
      && method_exists($class_name, 'onDelete')
    ) {
      $class_name::onDelete($entity);
      break;
    }
  }
}