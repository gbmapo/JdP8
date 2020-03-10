<?php

namespace Drupal\association\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Component\Render\FormattableMarkup;

class MembershipStep3 extends MembershipFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'membership_step3';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $form['anonymous'] = [
      '#type'  => 'hidden',
      '#value' => [$this->currentUser()->isAnonymous() ? 'Y' : 'N'],
    ];

    $markup          = implode("", [
        $this->store->get('lastname1'),
        ' ',
        $this->store->get('firstname1'),
        ' (',
        $this->store->get('cellphone1'),
        ' - ',
        $this->store->get('email1'),
        ')',
      ]);
    $form['person1'] = [
      '#type'   => 'item',
      '#markup' => $markup,
    ];

    $markup         = $this->store->get('designation');
    $form['member'] = [
      '#type'   => 'item',
      '#markup' => $markup,
    ];

    $markup          = implode("", [
        $this->store->get('lastname2'),
        ' ',
        $this->store->get('firstname2'),
        ' (',
        $this->store->get('cellphone2'),
        ' - ',
        $this->store->get('email2'),
        ')',
      ]);
    $markup          = $this->store->get('lastname2') ? $markup : '';
    $form['person2'] = [
      '#type'   => 'item',
      '#markup' => $markup,
    ];

    $markup          = implode("", [
        $this->store->get('addresssupplement') ? $this->store->get('addresssupplement') . '<BR>' : '',
        $this->store->get('street') . '<BR>',
        $this->store->get('postalcode') . ' ' . $this->store->get('city') . '<BR>',
        $this->store->get('telephone') ? $this->store->get('telephone') : '',
      ]);
    $form['address'] = [
      '#type'   => 'item',
      '#markup' => $markup,
    ];

    $form['sel'] = [
      '#type'          => 'checkboxes',
      '#options'       => [
        'sel1' => $this->store->get('lastname1') . ' ' . $this->store->get('firstname1'),
        'sel2' => $this->store->get('lastname2') ? $this->store->get('lastname2') . ' ' . $this->store->get('firstname2') : '',
      ],
      '#default_value' => [
        $this->store->get('seliste1') ? 'sel1' : 0,
        $this->store->get('seliste2') ? 'sel2' : 0,
      ],
      '#disabled'      => TRUE,
    ];

    $form['amap_legumes']                = ['#type' => 'checkbox',];
    $form['amap_fruits']                 = ['#type' => 'checkbox',];
    $form['amap_pain']                   = ['#type' => 'checkbox',];
    $form['amap_viandedebœuf']           = ['#type' => 'checkbox',];
    $form['amap_œufs']                   = ['#type' => 'checkbox',];
    $form['amap_volaille']               = ['#type' => 'checkbox',];
    $form['amap_champignons']            = ['#type' => 'checkbox',];
    $form['amap_lentilles']              = ['#type' => 'checkbox',];
    $form['amap_viandedeporc']           = ['#type' => 'checkbox',];
    $form['amap_produitslaitiersvache']  = ['#type' => 'checkbox',];
    $form['amap_pommesdeterre']          = ['#type' => 'checkbox',];
    $form['amap_produitslaitiersbrebis'] = ['#type' => 'checkbox',];
    $form['amap_farine']                 = ['#type' => 'checkbox',];
    $form['amap_miel']                   = ['#type' => 'checkbox',];
    $form['amap_jusdepomme']             = ['#type' => 'checkbox',];

    $form['contract'] = ['#type' => 'checkbox',];

    $form['payment'] = [
      '#type'     => 'radios',
      '#title'    => $this->t('Subscription Payment Mode'),
      '#options'  => [
        0 => $this->t('by check'),
        1 => $this->t('by bank transfer'),
        2 => $this->t('by card'),
      ],
      '#required' => TRUE,
    ];

    $markup             = $this->t('After submitting this form, you will be redirected to the online payment process.');
    $form['paymentcrd'] = [
      '#type'   => 'item',
//    '#markup' => $markup,
      '#states' => [
        'visible' => [
          ':input[name="payment"]' => ['value' => 2],
        ],
      ],
    ];

    $form['actions']['previous'] = [
      '#type'       => 'link',
      '#title'      => $this->t('Previous'),
      '#attributes' => [
        'class' => ['form-submit'],
      ],
      '#weight'     => 0,
      '#url'        => Url::fromRoute('association.membership2'),
    ];

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

    $values = $form_state->getValues();
    $contracts = '';
    foreach ($values as $key => $value) {
      if (substr($key, 0, 5)=='amap_' && $value == 1) {
        $contracts .= substr($key, 5, 999) . ', ';
      }
    }
    $contracts = substr($contracts, 0, strlen($contracts)-2);
    if ($contracts) {
      $contracts = 'AMAP : ' . $contracts . '.';
    }
    $this->store->set('contracts', $contracts);

    $payment = (string) $form['payment']['#title'] . ' : ' . (string) $form['payment']['#options'][$form_state->getValue('payment')] . '.';
    $this->store->set('payment', $payment);

    //  Save the data
    parent::saveData();

    switch ($form_state->getValue('payment')) {
      case '2':
        $form_state->setRedirect('association.membership4');
        break;
      default:
        $form_state->setRedirectUrl(Url::fromRoute('<front>'));
    }


  }

}
