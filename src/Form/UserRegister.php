<?php

namespace Drupal\serempre_usr\Form;

use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;


/**
 * Class UserRegister.
 */
class UserRegister extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_register';
  }

  /**
   * Building all elements in the form
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['name'] = [
      '#type'     => 'textfield',
      '#required' => true,
      '#title'    => 'Name'
    ];

    // Submit with ajax event (a modal window will be displayed)
    $form['submit'] = [
      '#type'  => 'submit',
      '#value' => $this->t('Register user'),
      '#ajax'  => array(
        'callback' => '::ajaxSaveUser',
        'event' => 'validateUserForm',
      ),
    ];

    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $form['#attached']['library'][] = 'serempre_usr/jquery-validate';
    $form['#attached']['library'][] = 'serempre_usr/frontend';

    return $form;
  }


  /**
   * To validate values of the form
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    parent::validateForm($form, $form_state);
  }


  /**
   * Submit funtion (also close the modal window)
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $form_state->setRebuild(TRUE);
  }

  public function ajaxSaveUser(array &$form, FormStateInterface $form_state) {

    $response = new AjaxResponse();

    //retrive the name value
    $name = $form_state->getValue('name');

    if (strlen($name) < 5) {

      $dialogText['#markup'] = "Enter at least 5 characters.";

    }else{

      $result = \Drupal::database()->select('myusers', 'm')
        ->fields('m', array('id', 'name'))
        ->condition('m.name', $name)
        ->countQuery()
        ->execute()
        ->fetchField();        

      if($result>0){

        $dialogText['#markup'] = "This name is already registered.";

      }else{

        $database = \Drupal::service('database');

        $result = $database->insert('myusers')
          ->fields([
            'name' => $name
          ])
          ->execute();
        $dialogText['#markup'] = "Your new user ID: $result";
        
      }

    }

    $response->addCommand(new OpenModalDialogCommand(t('Name registration'), $dialogText));

    return $response;

  }

}
