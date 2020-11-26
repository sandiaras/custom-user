<?php

namespace Drupal\serempre_usr\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class UserImportController.
 */
class UserImportController extends ControllerBase {

  /**
   * Userimport.
   *
   * @return string
   *   Return Hello string.
   */
  public function user_import() {

    $form = \Drupal::formBuilder()->getForm('Drupal\serempre_usr\Form\UserImportForm');

    return $form;
  }

}
