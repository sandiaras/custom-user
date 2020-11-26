<?php

namespace Drupal\serempre_usr;

use Drupal\node\Entity\Node;

class AddImportContent {

  /**
   * Method to manage the importantion of the user item. 
   * See src/Form/UserImportForm.php
   */
  public static function importUsers($item, &$context){

    if(save_name($item)){
      $message = 'Creating ' . $item;
      $context['results'][] = $item;
    }else{
      $message = 'Already created ' . $item;
    }

    $context['message'] = $message;
    
  }


  /**
   * Function called at the end of the batch process
   */
  static function importUsersFinishedCallback($success, $results, $operations) {

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


/**
 * Function to insert a new name
 */
function save_name($item) {

  $result = \Drupal::database()->select('myusers', 'm')
  ->fields('m', array('id', 'name'))
  ->condition('m.name', $item)
  ->countQuery()
  ->execute()
  ->fetchField();        

  if($result===0){
    $database = \Drupal::service('database');
    $database->insert('myusers')
      ->fields([
        'name' => $item
      ])
      ->execute();
    return true;
  }else{
    return false;
  }

}
