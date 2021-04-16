<?php

namespace Drupal\association\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\user\Entity\User;

/**
 * Class MemberAndPerson.
 */
class MemberAndPerson extends FormBase
{

  public function getFormId()
  {
    return 'member_and_person';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {

    \Drupal::moduleHandler()->loadInclude('association', 'inc', 'association.allowed.values');

    $weight = 0;

    $weight++;
    $temp = isset($form_state->getStorage()['lastname1']) ? $form_state->getStorage()['lastname1'] : '';
    $form['lastname1'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Last Name'),
      '#size'          => 32,
      '#required'      => TRUE,
      '#default_value' => $temp,
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];
    $weight++;
    $temp = isset($form_state->getStorage()['firstname1']) ? $form_state->getStorage()['firstname1'] : '';
    $form['firstname1'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('First Name'),
      '#size'          => 32,
      '#required'      => TRUE,
      '#default_value' => $temp,
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];
    $weight++;
    $temp = isset($form_state->getStorage()['email1']) ? $form_state->getStorage()['email1'] : '';
    $form['email1'] = [
      '#type'          => 'email',
      '#title'         => $this->t('Email'),
      '#size'          => 64,
      '#required'      => TRUE,
      '#default_value' => $temp,
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];
    $weight++;
    $temp = isset($form_state->getStorage()['cellphone1']) ? $form_state->getStorage()['cellphone1'] : '';
    $form['cellphone1'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Cellphone'),
      '#size'          => 16,
      '#default_value' => $temp,
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];

    $weight++;
//    $temp = $form_state->getStorage()['lastname1'] . ' ' . $form_state->getStorage()['firstname1'];
    $form['designation'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Designation'),
      '#size'          => 64,
      '#required'      => TRUE,
      '#default_value' => $temp,
      '#weight'        => $weight,
    ];
    $weight++;
    $temp = isset($form_state->getStorage()['addresssupplement']) ? $form_state->getStorage()['addresssupplement'] : '';
    $form['addresssupplement'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Address supplement'),
      '#size'          => 64,
      '#default_value' => $temp,
      //    '#placeholder'   => 'Batiment B',
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];
    $weight++;
    $temp = isset($form_state->getStorage()['street']) ? $form_state->getStorage()['street'] : '';
    $form['street'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Street'),
      '#size'          => 64,
      '#required'      => TRUE,
      //    '#placeholder'   => '28 bis boulevard Victor Hugo',
      '#default_value' => $temp,
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];
    $weight++;
    $temp = isset($form_state->getStorage()['postalcode']) ? $form_state->getStorage()['postalcode'] : '';
    $form['postalcode'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Postal Code'),
      '#size'          => 10,
      '#required'      => TRUE,
      //    '#placeholder'   => '78300',
      '#default_value' => $temp,
      '#weight'        => $weight,
    ];
    $weight++;
    $temp = isset($form_state->getStorage()['city']) ? $form_state->getStorage()['city'] : '';
    $form['city'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('City'),
      '#size'          => 64,
      '#required'      => TRUE,
      //    '#placeholder'   => 'Poissy',
      '#default_value' => $temp,
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];
    $weight++;
    $temp = isset($form_state->getStorage()['country']) ? $form_state->getStorage()['country'] : 'FR';
    $form['country'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Country'),
      '#options'       => association_member_country(),
      '#default_value' => $temp,
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];
    $weight++;
    $temp = isset($form_state->getStorage()['telephone']) ? $form_state->getStorage()['telephone'] : '';
    $form['telephone'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Landline Phone'),
      '#size'          => 16,
      '#default_value' => $temp,
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];

    $weight++;
    $form['submit'] = [
      '#type'   => 'submit',
      '#value'  => $this->t('Submit'),
      '#weight' => $weight,
    ];

    $form['#attached']['library'][] = 'association/membership';

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {

    $email1 = $form_state->getValue('email1');
    $sTemp = $this->_existsEmail($email1);
    if ($sTemp) {
      $form_state->setErrorByName('email1', $sTemp);
    }
    parent::validateForm($form, $form_state);
  }

  public function _existsEmail($email)
  {
    $database = \Drupal::database();
    $query = $database->select('users_field_data', 'us');
    $query->fields('us', ['uid', 'name', 'mail'])
      ->condition('us.mail', $email, '=');
    $results = $query->execute()->fetchAll();
    if (count($results) == 0) {
      $output = FALSE;
    }
    else {
      $output = $this->t('This email is already registered for « %user ».', [
        '%user' => $results[0]->name,
      ]);
    }
    return $output;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->cleanValues()->getValues();
    $currentUser = $this->currentUser()->id();
    $now = \Drupal::time()->getRequestTime();
    $database = \Drupal::database();

    // User --------------------------------------------------------------
    $user = User::create();
    $user->setPassword('passwordtobechanged');
    $user->enforceIsNew();
    $user->setEmail($values['email1']);
    $user->setUsername($this->_generateName($values['firstname1'], $values['lastname1']));
    $user->block();
    $user->addRole('contact_for_member');
    $user->save();
    $uid = $user->id();
    $mail = _user_mail_notify('register_pending_approval', $user);
    // Member ------------------------------------------------------------
    $insertFieldsM = [
      'designation'       => $values['designation'],
      'addresssupplement' => $values['addresssupplement'],
      'street'            => $values['street'],
      'postalcode'        => $values['postalcode'],
      'city'              => $values['city'],
      'country'           => $values['country'],
      'telephone'         => $values['telephone'],
      'status'            => 2,
      'startdate'         => date('Y-m-d'),
      'enddate'           => '2037-12-30',
      'contact_id'        => $uid,
      'comment'           => NULL,
      'owner_id'          => $currentUser,
      'created'           => $now,
      'changed'           => $now,
    ];
    $idM = $database->insert('member')
      ->fields($insertFieldsM)
      ->execute();
/*
    $query = $database->select('member', 'am');
    $query->fields('am', ['id', 'created']);
    $query->condition('created', $now, '=');
    $idM = $query->execute()->fetchCol()[0];
 */
    // Person ------------------------------------------------------------
    $insertFieldsP = [
      'id'        => $uid,
      'lastname'  => $values['lastname1'],
      'firstname' => $values['firstname1'],
      'cellphone' => $values['cellphone1'],
      'email'     => $values['email1'],
      'iscontact' => 1,
      'isactive'  => 0,
      'member_id' => $idM,
      'user_id'   => $uid,
      'comment'   => NULL,
      'owner_id'  => $currentUser,
      'created'   => $now,
      'changed'   => $now,
    ];
    $database->insert('person')
      ->fields($insertFieldsP)
      ->execute();

    $user = User::load($uid);
    $user->setEmail($values['email1']);
    $user->save();

    \Drupal::messenger()->addMessage($this->t('Member « %label » has been added.', [
      '%label' => $values['designation'],
    ]));
    $form_state->setRedirect('view.association_members.page_1');

  }

  public function _generateName($firstname, $lastname)
  {
    $database = \Drupal::database();
    $query = $database->select('users_field_data', 'us');
    $query->fields('us', ['name']);
    $query->condition('name', $firstname . '%', 'like');
    $results = $query->execute()->fetchCol();
    for ($i = 0; $i <= strlen($lastname); $i++) {
      $temp = $firstname . strtoupper(substr($lastname, 0, $i));
      $key = array_search($temp, $results);
      if ($key === FALSE) {
        break;
      }
    }
    return $temp;
  }

}
