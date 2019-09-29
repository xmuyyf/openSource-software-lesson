<?php

namespace Drupal\cool\Models;

abstract class BaseModel extends \EntityDrupalWrapper {

  static public $base_model_entity_type;
  static public $base_model_default_bundle;

  static private function checkBaseModelVars() {
    if (empty(static::$base_model_entity_type) || empty(static::$base_model_default_bundle)) {
      throw new \Exception('You need to define $base_model_entity_type and $base_model_default_bundle for ' . get_called_class());
    }
  }

  static public function create() {
    static::checkBaseModelVars();

    $entity = entity_create(static::$base_model_entity_type, ['type' => static::$base_model_default_bundle]);
    $EntityWrapper = new static($entity);
    return $EntityWrapper;
  }

  static public function load($id, $reset = FALSE) {
    static::checkBaseModelVars();

    if ($reset) {
      $entity = entity_load(static::$base_model_entity_type, [$id], [], $reset = TRUE);
      if ($entity) {
        $entity = current($entity);
      }
    }
    else {
      $entity = entity_load_single(static::$base_model_entity_type, $id);
    }
    if ($entity) {
      return new static($entity);
    }
    else {
      throw new \Exception(get_called_class() . " ID($id), does not match an entity");
    }
  }

  public function __construct($data = NULL, $info = []) {
    static::checkBaseModelVars();

    parent::__construct(static::$base_model_entity_type, $data, $info);
  }

  static public function accessCallback($op, $entity, $account, $entity_type_name) {
    module_load_include('inc', 'eck', 'eck.bundle');
    return eck__entity_access($op, $entity, $account, $entity_type_name);
  }

  static public function onInsert($entity) {
  }

  static public function onUpdate($entity) {
  }

  static public function onDelete($entity) {
  }
}