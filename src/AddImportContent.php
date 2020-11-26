<?php

namespace Drupal\serempre_usr;

use Drupal\node\Entity\Node;

class AddImportContent {
  public static function importUsers($item, &$context){
    $context['sandbox']['current_item'] = $item;
    $message = 'Creating ' . $item;
    $results = array();
    save_name($item);
    $context['message'] = $message;
    $context['results'][] = $item;
  }

  function importUsersFinishedCallback($success, $results, $operations) {

    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One item processed.', '@count items processed.'
      )->render();
    }
    else {
      $message = t('Finished with an error.');
    }
    \Drupal::messenger()->addStatus($message);
  }
}


function save_name($item) {

  $database = \Drupal::service('database');
  $database->insert('myusers')
    ->fields([
      'name' => $item
    ])
    ->execute();

}
