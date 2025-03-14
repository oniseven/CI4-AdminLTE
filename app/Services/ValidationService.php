<?php
namespace App\Services;

class ValidationService {
  /**
   * Get validation rules for a specific entity and action.
   *
   * @param string $entity The entity (e.g., 'user', 'product').
   * @param string $action The action (e.g., 'insert', 'update').
   * @param array $params Additional parameters (e.g., user ID for update rules).
   * @return array
   */
  public static function getRules($entity, $params = [])
  {
    $validationClass = "App\\Validations\\" . ucfirst($entity) . 'Validation';

    if (!class_exists($validationClass)) {
      throw new \InvalidArgumentException("Validation class for entity '{$entity}' does not exist.");
    }

    $method = 'rules';
    if (!method_exists($validationClass, $method)) {
      throw new \InvalidArgumentException("Validation method '{$method}' does not exist in class '{$validationClass}'.");
    }

    return call_user_func([$validationClass, $method], ...$params);
  }

  /**
   * Get error messages for a specific entity.
   *
   * @param string $entity The entity (e.g., 'user', 'product').
   * @return array
   */
  public static function getMessages($entity)
  {
    $validationClass = "App\\Validations\\" . ucfirst($entity) . 'Validation';

    if (!class_exists($validationClass)) {
      throw new \InvalidArgumentException("Validation class for entity '{$entity}' does not exist.");
    }

    return call_user_func([$validationClass, 'errorMessages']);
  }
}