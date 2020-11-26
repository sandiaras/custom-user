<?php

namespace Drupal\serempre_usr\Controller;

use Drupal\Core\Controller\ControllerBase;

use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Class UserIOController.
 */
class UserIOController extends ControllerBase {

  /**
   * UserIO.
   *
   * @return array
   *  
   */
  public function user_import() {

    $form = \Drupal::formBuilder()->getForm('Drupal\serempre_usr\Form\UserImportForm');

    return $form;
  }

  public function user_export() {
    $response = new Response();
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');
    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->headers->set('Content-Disposition', 'attachment; filename=spreadsheet.xlsx');
    
    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    
    // Set workbook properties
    $spreadsheet->getProperties()->setCreator('Sandi Aramayo')
                ->setLastModifiedBy('Sandi Aramayo')
                ->setTitle('Serempre Export Users')
                ->setSubject('PhpSpreadsheet')
                ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
                ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
                ->setCategory('Test file');

    //Set active sheet index to the first sheet, 
    //and add some data
    $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Id')
                ->setCellValue('B2', 'Name');

    // Set worksheet title
    $spreadsheet->getActiveSheet()->setTitle('Users');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);
 
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    ob_start();
    $writer->save('php://output');
    $content = ob_get_clean();
    $response->setContent($content);
    return $response;
  }


  /**
   * Listusers.
   *
   * @return strings
   */
  public function users_list() {

    $result = \Drupal::database()->select('myusers', 'm')
      ->fields('m', array('id', 'name'))
      ->execute()->fetchAllAssoc('id');

    if(count($result)>0){
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
  
    }else{
      $output['empty'] = array(
        '#markup'     => 'No user registered yet',
      );
    }

    return $output;
    
  }


  /**
   * Build the part of the rows that will be shown
   */
  function _return_pager_for_array($items, $num_page) {

    $total = count($items);
    $current_page = pager_default_initialize($total, $num_page);
    $chunks = array_chunk($items, $num_page);
    $current_page_items = $chunks[$current_page];

    return $current_page_items;
  }

}
