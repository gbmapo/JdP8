<?php

namespace Drupal\association\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class MembershipStep1 extends MembershipFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'membership_step1';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $weight          = 0;
    $form['person1'] = [
      '#type'  => 'fieldset',
      '#title' => t('Person') . ' 1 ' . t('(Contact)'),
    ];
    $weight++;
    $form['person1']['lastname1'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Last Name'),
      '#size'          => 32,
      '#required'      => TRUE,
      '#default_value' => $this->store->get('lastname1') ? $this->store->get('lastname1') : '',
      '#weight'        => $weight,
    ];
    $weight++;
    $form['person1']['firstname1'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('First Name'),
      '#size'          => 32,
      '#required'      => TRUE,
      '#default_value' => $this->store->get('firstname1') ? $this->store->get('firstname1') : '',
      '#weight'        => $weight,
    ];
    $weight++;
    $form['person1']['email1'] = [
      '#type'          => 'email',
      '#title'         => $this->t('Email'),
      '#size'          => 64,
      '#required'      => TRUE,
      '#default_value' => $this->store->get('email1') ? $this->store->get('email1') : '',
      '#weight'        => $weight,
    ];
    $weight++;
    $form['person1']['cellphone1'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Cellphone'),
      '#size'          => 16,
      '#required'      => TRUE,
      '#default_value' => $this->store->get('cellphone1') ? $this->store->get('cellphone1') : '',
      '#weight'        => $weight,
    ];
    $weight++;
    $form['person1']['seliste1'] = [
      '#type'          => 'checkbox',
      '#title'         => $this->t('I wish to be SÉListe'),
      '#default_value' => $this->store->get('seliste1') ? $this->store->get('seliste1') : 0,
      '#weight'        => $weight,
    ];

    $form['person2'] = [
      '#type'  => 'fieldset',
      '#title' => t('Person') . ' 2',
    ];
    $weight++;
    $form['person2']['lastname2'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Last Name'),
      '#size'          => 32,
      '#default_value' => $this->store->get('lastname2') ? $this->store->get('lastname2') : '',
      '#weight'        => $weight,
    ];
    $weight++;
    $form['person2']['firstname2'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('First Name'),
      '#size'          => 32,
      '#default_value' => $this->store->get('firstname2') ? $this->store->get('firstname2') : '',
      '#weight'        => $weight,
    ];
    $weight++;
    $form['person2']['email2'] = [
      '#type'          => 'email',
      '#title'         => $this->t('Email'),
      '#size'          => 64,
      '#default_value' => $this->store->get('email2') ? $this->store->get('email2') : '',
      '#weight'        => $weight,
    ];
    $weight++;
    $form['person2']['cellphone2'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Cellphone'),
      '#size'          => 16,
      '#default_value' => $this->store->get('cellphone2') ? $this->store->get('cellphone2') : '',
      '#weight'        => $weight,
    ];
    $weight++;
    $form['person2']['seliste2'] = [
      '#type'          => 'checkbox',
      '#title'         => $this->t('I wish to be SÉListe'),
      '#default_value' => $this->store->get('seliste2') ? $this->store->get('seliste2') : 0,
      '#weight'        => $weight,
    ];

    $form['actions']['previous'] = [
      '#type'       => 'link',
      '#title'      => $this->t('Previous'),
      '#attributes' => [
        'class' => ['form-submit'],
      ],
      '#weight'     => 0,
      '#url'        => Url::fromRoute('association.membership0'),
    ];

    $form['actions']['submit']['#value'] = $this->t('Next');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $values = $form_state->cleanValues()->getValues();

    $person2 = array_intersect_key($values, array_flip([
      'lastname2',
      'firstname2',
      'email2',
      'cellphone2',
    ]));
    if (implode("", $person2)) {
      //    $message = 'Si l\'un des champs Nom, Prénom, Courriel, Portable est renseigné, les autres doivent l\'être aussi.';
      $message = $this->t('If one of the fields Lastname, Firstname, Email, Cellphone is filled, the others must be filled too.');
      if (!$form_state->getValue('lastname2') || !$form_state->getValue('firstname2') || !$form_state->getValue('email2') || !$form_state->getValue('cellphone2')) {
        $form_state->setErrorByName('lastname2', $message);
        $form_state->setErrorByName('firstname2');
        $form_state->setErrorByName('email2');
        $form_state->setErrorByName('cellphone2');
      }
    }

    if ($this->currentUser()->isAnonymous()) {

      $email = $form_state->getValue('email1');
      $sTemp = $this->_existsEmail($email);
      if ($sTemp) {
        $form_state->setErrorByName('email1', $sTemp);
      }

      $email = $form_state->getValue('email2');
      if ($email) {
        $sTemp = $this->_existsEmail($email);
        if ($sTemp) {
          $form_state->setErrorByName('email2', $sTemp);
        }
      }

    }

  }

  /**
   *
   */
  public function _existsEmail($email) {

    $database = \Drupal::database();
    $query    = $database->select('users_field_data', 'us');
    $query->fields('us', ['uid', 'name', 'mail'])
      ->condition('us.mail', $email, '=');
    $results = $query->execute()->fetchAll();
    if (count($results) == 0) {
      $output = FALSE;
    }
    else {
      $url    = Url::fromUri('base:/user/login');
      $link   = \Drupal\Core\Link::fromTextAndUrl($this->t('here'), $url)
        ->toString();
      $output = $this->t('This email is already registered for « %user ».<BR>If you are already a member, please log in %link.', [
        '%user' => $results[0]->name,
        '%link' => $link,
      ]);
    }
    return $output;

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $keys = [
      'lastname1',
      'firstname1',
      'email1',
      'cellphone1',
      'seliste1',
      'lastname2',
      'firstname2',
      'email2',
      'cellphone2',
      'seliste2',
    ];
    foreach ($keys as $key) {
      $this->store->set($key, $form_state->getValue($key));
    }

    $this->store->set('lastname', $form_state->getValue('lastname1'));
    $this->store->set('firstname', $form_state->getValue('firstname1'));
    $this->store->set('email', $form_state->getValue('email1'));

    if ($this->store->get('designation')) {
      $designation = $this->store->get('designation');
    }
    else {
      $designation = $this->store->get('lastname1') . ' ' . $this->store->get('firstname1');
      if ($this->store->get('lastname2')) {
        if ($this->store->get('lastname1') == $this->store->get('lastname2')) {
          $designation .= ' et ' . $this->store->get('firstname2');
        }
        else {
          $designation .= ' et ' . $this->store->get('lastname2') . ' ' . $this->store->get('firstname2');
        }
      }
    }
    $this->store->set('designation', $designation);

    $form_state->setRedirect('association.membership2');
  }

}
