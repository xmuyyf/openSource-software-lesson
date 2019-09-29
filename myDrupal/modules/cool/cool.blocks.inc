<?php

use \Drupal\cool\Loader;

/**
 * Implements hook_block_info()
 */
function cool_block_info() {
  $blocks = array();
  $classes = Loader::mapImplementationsAvailable('BlockControllers', '\Drupal\cool\Controllers\BlockController');
  unset($classes['Drupal\\cool\\BaseBlock']);
  unset($classes['Drupal\\cool\\BaseDynamicBlock']);
  foreach ($classes as $class_name) {
    if (method_exists($class_name, 'getAdminTitle')) {
      if (!method_exists($class_name, 'getContent')) {
        throw new Exception(t('Class @classname do not have the getContent method', array('@classname' => $class_name)));
      }
      $isDynamicBlock = Loader::classIsSubclassOf($class_name, '\Drupal\cool\BaseDynamicBlock');
      if (!$isDynamicBlock) {
        $blocks[$class_name::getId()] = array_merge(
          array(
            'info' => $class_name::getAdminTitle(),
            'cache' => DRUPAL_NO_CACHE,
          ),
          $class_name::getDefinition()
        );
      }
    }
  }
  return $blocks;
}

/**
 * Implements hook_block_info_alter()
 */
function cool_block_info_alter(&$blocks, $theme, $code_blocks) {
  if (isset($blocks['block'])) {
    $cool_dynblocks = cool_get_dynamic_blocks();
    foreach ($blocks['block'] as &$block) {
      if (isset($cool_dynblocks[$block['delta']])) {
        $cool_dynamicblock_type = $cool_dynblocks[$block['delta']]['class'];
        if (class_exists($cool_dynamicblock_type)) {
          $block['info'] = $cool_dynamicblock_type::getAdminTitle($block['delta']);
        }
      }
    }
  }
}

/**
 * Implements hook_block_configure()
 */
function cool_block_configure($delta = '') {
  $classes = Loader::mapImplementationsAvailable('BlockControllers', '\Drupal\cool\Controllers\BlockController');
  unset($classes['Drupal\\cool\\BaseBlock']);
  unset($classes['Drupal\\cool\\BaseDynamicBlock']);
  foreach ($classes as $class_name) {
    $isDynamicBlock = Loader::classIsSubclassOf($class_name, '\Drupal\cool\BaseDynamicBlock');
    if (!$isDynamicBlock && ($delta == $class_name::getId())) {
      return $class_name::getConfiguration();
    }
  }
}

/**
 * Implements hook_block_save()
 */
function cool_block_save($delta = '', $edit = array()) {
  $classes = Loader::mapImplementationsAvailable('BlockControllers', '\Drupal\cool\Controllers\BlockController');
  unset($classes['Drupal\\cool\\BaseBlock']);
  unset($classes['Drupal\\cool\\BaseDynamicBlock']);
  foreach ($classes as $class_name) {
    $isDynamicBlock = Loader::classIsSubclassOf($class_name, '\Drupal\cool\BaseDynamicBlock');
    if (!$isDynamicBlock && ($delta == $class_name::getId())) {
      return $class_name::saveConfiguration($edit, $delta);
    }
  }
}

/**
 * Implements hook_block_view()
 */
function cool_block_view($delta = '') {
  $classes = Loader::mapImplementationsAvailable('BlockControllers', '\Drupal\cool\Controllers\BlockController');
  unset($classes['Drupal\\cool\\BaseBlock']);
  unset($classes['Drupal\\cool\\BaseDynamicBlock']);
  $block = array();
  foreach ($classes as $class_name) {
    $isDynamicBlock = Loader::classIsSubclassOf($class_name, '\Drupal\cool\BaseDynamicBlock');
    if (!$isDynamicBlock && $class_name::getId() == $delta) {
      $block['subject'] = $class_name::getSubject();
      $block['content'] = $class_name::getContent();
    }
  }
  return $block;
}

/**
 * Implements hook_block_view_alter()
 */
function cool_block_view_alter(&$data, $block) {
  $cool_dynblocks = cool_get_dynamic_blocks();
  if (isset($cool_dynblocks[$block->delta])) {
    $block_data = db_query('SELECT body, format FROM {block_custom} WHERE bid = :bid', array(':bid' => $block->delta))->fetchObject();
    $data['subject'] = $cool_dynblocks[$block->delta]['class']::getSubject($block->delta);
    $data['content'] = '<div class="dynamic-block-header">' . check_markup($block_data->body, $block_data->format, '', TRUE) . '</div>';
    $data['content'] .= $cool_dynblocks[$block->delta]['class']::getContent($block->delta);
  }
}