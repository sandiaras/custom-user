<?php

namespace Drupal\serempre_usr\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class UserRegisterController.
 */
class UserRegisterController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function user_register() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: user register'),
    ];
  }

}
