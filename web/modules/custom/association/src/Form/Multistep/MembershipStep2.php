<?php

namespace Drupal\association\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class MembershipStep2 extends MembershipFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'membership_step2';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $weight = 0;
    $weight++;
    $form['designation'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Designation'),
      '#size'          => 64,
      '#required'      => TRUE,
      '#default_value' => $this->store->get('designation') ? $this->store->get('designation') : '',
      '#weight'        => $weight,
    ];
    $form['address']     = [
      '#type'   => 'fieldset',
      '#title'  => t('Address'),
      '#weight' => $weight,
    ];
    $weight++;
    $form['address']['addresssupplement'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Address supplement'),
      '#size'          => 64,
      '#default_value' => $this->store->get('addresssupplement') ? $this->store->get('addresssupplement') : '',
      '#placeholder'   => 'Batiment B',
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];
    $weight++;
    $form['address']['street'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Street'),
      '#size'          => 64,
      '#required'      => TRUE,
      '#placeholder'   => '28 bis boulevard Victor Hugo',
      '#default_value' => $this->store->get('street') ? $this->store->get('street') : '',
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];
    $weight++;
    $form['address']['postalcode'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Postal Code'),
      '#size'          => 10,
      '#required'      => TRUE,
      '#placeholder'   => '78300',
      '#default_value' => $this->store->get('postalcode') ? $this->store->get('postalcode') : '',
      '#weight'        => $weight,
    ];
    $weight++;
    $form['address']['city'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('City'),
      '#size'          => 64,
      '#required'      => TRUE,
      '#placeholder'   => 'Poissy',
      '#default_value' => $this->store->get('city') ? $this->store->get('city') : '',
      '#weight'        => $weight,
      '#attributes'    => ['onchange' => 'hasChanged(this)',],
    ];
    $weight++;
    $form['telephone'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Phone'),
      '#size'          => 16,
      '#default_value' => $this->store->get('telephone') ? $this->store->get('telephone') : '',
      '#weight'        => $weight,
    ];

    $form['actions']['previous'] = [
      '#type'       => 'link',
      '#title'      => $this->t('Previous'),
      '#attributes' => [
        'class' => ['form-submit'],
      ],
      '#weight'     => 0,
      '#url'        => Url::fromRoute('association.membership1'),
    ];

    $form['actions']['submit']['#value'] = $this->t('Next');

    $form['#attached']['library'][] = 'association/membership';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $keys = [
      'designation',
      'addresssupplement',
      'street',
      'postalcode',
      'city',
      'telephone',
    ];
    foreach ($keys as $key) {
      $this->store->set($key, $form_state->getValue($key));
    }

    $form_state->setRedirect('association.membership3');

  }

}
