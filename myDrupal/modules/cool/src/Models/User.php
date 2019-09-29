<?php

namespace Drupal\cool\Models;

class User extends BaseModel {

  static public $base_model_entity_type = 'user';
  static public $base_model_default_bundle = 'user';

  /**
   * @return User
   */
  static public function currentUser() {
    global $user;
    return static::load($user->uid);
  }

  static public function createNewUser($name, $email, $pass = NULL, $roles_names = [], $status = 0, $created = NULL) {
    $new_user = [
      'created' => $created ? $created : time(),
      'name' => $name,
      'pass' => $pass ? $pass : time() . rand(0, 10000),
      'mail' => $email,
      'status' => $status,
      'init' => $email,
      'roles' => [],
    ];
    foreach ($roles_names as $role_name) {
      $role = user_role_load_by_name($role_name);
      $new_user['roles'][$role->rid] = $role->name;
    }

    $account = user_save('', $new_user);
    return static::load($account->uid);
  }

  static public function loginFinalize($uid) {
    global $user;
    $account = user_load($uid);
    $user = $account;
    $user->login = REQUEST_TIME;
    db_update('users')
      ->fields(array('login' => $user->login))
      ->condition('uid', $user->uid)
      ->execute();
    drupal_session_regenerate();
  }

  public function isCurrentUser() {
    $User = static::currentUser();
    return $this->getIdentifier() == $User->getIdentifier();
  }

  public function getRoles() {
    if (isset($this->data->roles)) {
      $roles = $this->data->roles;
      unset($roles[2]);
    }
    else {
      $roles = [];
    }
    return $roles;
  }

  public function hasRole($role) {
    return in_array($role, $this->getRoles());
  }

  public function addRoles($roles_names = []) {
    foreach ($roles_names as $role_name) {
      $role = user_role_load_by_name($role_name);
      $this->data->roles[$role->rid] = $role->name;
    }
  }

  public function isAdministrator() {
    if (
      (int) $this->getIdentifier() === 1
    ) {
      return TRUE;
    }
    return FALSE;
  }

  public function getCreatedTime() {
    return $this->created->value();
  }

  public function getUsername() {
    return $this->name->value();
  }

  public function getEmail() {
    return $this->mail->value();
  }
}