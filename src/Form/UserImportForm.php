<?php

namespace Drupal\serempre_usr\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

class UserImportForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_import';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $form['import_csv'] = array(
      '#type'               => 'managed_file',
      '#title'              => t('Upload file here'),
      '#required'           => true,
      '#upload_location'    => 'public://import_cvs',
      '#default_value'      => '',
      "#upload_validators"  => array("file_validate_extensions" => array("csv")),
    );

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = array(
      '#type'        => 'submit',
      '#value'       => $this->t('Upload CSV'),
      '#button_type' => 'primary',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $csv_file = $form_state->getValue('import_csv', 0);

    if (isset($csv_file[0]) && !empty($csv_file[0])) {
      $file = File::load($csv_file[0]);
      $file->setPermanent();
      $file->save();

    }

    $data = $this->csvtoarray($file->getFileUri(), ',');

    foreach($data as $row) {

      $name = str_replace(';','', array_shift($row));

      if($name != ''){
        $operations[] = ['\Drupal\serempre_usr\AddImportContent::importUsers', [$name]];
      }

    }

    $batch = array(
      'title'        => t('Importing Names...'),
      'operations'   => $operations,
      'init_message' => t('Import is starting.'),
      'finished'     => '\Drupal\serempre_usr\AddImportContent::importUsersFinishedCallback',
    );

    batch_set($batch);
  }

  public function csvtoarray($filename='', $delimiter){

    if(!file_exists($filename) || !is_readable($filename)) return FALSE;
    $header = NULL;
    $data = array();

    if (($handle = fopen($filename, 'r')) !== FALSE ) {
      while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
      {
        if(!$header){
          $header = $row;
        }else{
          $data[] = array_combine($header, $row);
        }
      }
      fclose($handle);
    }

    return $data;
  }

}
