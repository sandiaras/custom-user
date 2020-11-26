<?php

namespace Drupal\serempre_usr\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class UserListController.
 */
class UserListController extends ControllerBase {

  /**
   * Listusers.
   *
   * @return string
   *   Return Hello string.
   */
  public function users_list() {

    $result = \Drupal::database()->select('myusers', 'm')
      ->fields('m', array('id', 'name'))
      ->execute()->fetchAllAssoc('id');

    $rows_piece = $this->_return_pager_for_array($result, 10);

    foreach ($rows_piece as $row => $content) {
      $rows[] = array('data' => array($content->id, $content->name));
    }

    $header = array('id', 'name');

    $output['table'] = array(
      '#theme'  => 'table',    // Here you can write #type also instead of #theme.
      '#header' => $header,
      '#rows'   => $rows
    );

    $output['pageruser'] = array(
      '#type'     => 'pager',
      '#quantity' => 5
    );

    return $output;
    
  }


  // Build the part of the rows that will be shown
  function _return_pager_for_array($items, $num_page) {

    $total = count($items);
    $current_page = pager_default_initialize($total, $num_page);
    $chunks = array_chunk($items, $num_page);
    $current_page_items = $chunks[$current_page];

    return $current_page_items;
  }

}
