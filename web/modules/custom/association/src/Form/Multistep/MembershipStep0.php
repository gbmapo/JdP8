<?php

namespace Drupal\association\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Url;

class MembershipStep0 extends MembershipFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'membership_step0';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    if ($this->currentUser()->isAnonymous()) {

      $nextStepNotice = $this->t('enter');
      $markup         = $this->t('After submitting this form, you will be redirected to the membership process where you can @str your personal information then choose your subscription payment mode.', [
          '@str' => $nextStepNotice,
        ]) . '<BR>';
      $form['header'] = [
        '#type'     => 'inline_template',
        '#template' => $markup,
      ];


    }
    else {

      $nextStepNotice = $this->t('correct, if needed,');

      $config   = \Drupal::config('association.renewalperiod');
      $rpYear   = $config->get('year');
      $rpStatus = $config->get('status');

      if ($rpStatus == 'Closed') {
        $form['header'] = [
          '#markup' => '<BR>' . $this->t("There is currently no renewal period opened.") . '<BR>',
        ];
      }
      elseif (!$this->currentUser()->hasPermission("renew membership")) {
        $form['header'] = [
          '#markup' => $this->t("You're not allowed to renew membership. Only the person(s) associated to the member is(are) allowed to do it.") . '<BR>',
        ];
      }
      else {

        switch ($this->store->get('status')) {
          case 2:
            $iWish = -1;
            break;
          case 1:
            $iWish = 0;
            break;
          case 3:
            $iWish = 1;
            break;
          default:
            $iWish = -1;
            break;
        }
        $sMember = new FormattableMarkup('<span style="color: #0000ff;">' . $this->store->get('designation') . '</span>', []);
        $sPerson = new FormattableMarkup('<span style="color: #0000ff;">' . $this->store->get('firstname') . ' ' . $this->store->get('lastname') . '</span>', []);
        if ($this->store->get('status') == 4) {

          $sTemp          = '<BR>' . $this->t('The member «&nbsp;%member&nbsp;» has already renewed his membership to the association <I>Le Jardin de Poissy</I> for year « %year ».', [
              '%member' => $sMember,
              '%year'   => $rpYear,
            ]);
          $form['header'] = [
            '#type'     => 'inline_template',
            '#template' => $sTemp,
          ];

        }
        else {

          $sTemp          = $this->t("Here’s your wish as recorded. You can change it as many times as you like: only the last change will be taken into account.<BR><BR>");
          $sTemp          = ($iWish == -1) ? "" : $sTemp;
          $sTemp2         = $this->t('I, the undersigned «&nbsp;%person&nbsp;», representing the member «&nbsp;%member&nbsp;», wishes to renew my membership to the association <I>Le Jardin de Poissy</I> for year « %year ».', [
            '%person' => $sPerson,
            '%member' => $sMember,
            '%year'   => $rpYear,
          ]);
          $sTemp          = $sTemp . $sTemp2;
          $form['header'] = [
            '#type'     => 'inline_template',
            '#template' => $sTemp,
          ];

          $form['suscribe'] = [
            '#type'          => 'radios',
            '#title'         => '',
            '#options'       => [
              0 => $this->t('No'),
              1 => $this->t('Yes'),
            ],
            '#default_value' => $iWish,
            '#validated'     => TRUE,
          ];

          $markup              = $this->t('After submitting this form, you will be redirected to the membership process where you can @str your personal information then choose your subscription payment mode.', [
              '@str' => $nextStepNotice,
            ]) . '<BR>';
          $form['suscribeyes'] = [
            '#type'   => 'item',
            '#markup' => $markup,
            '#states' => [
              'visible' => [
                ':input[name="suscribe"]' => ['value' => 1],
              ],
            ],
          ];
        }
      }
    }

    return $form;

  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    if ($form_state->getValue('suscribe') == -1) {
      $form_state->setErrorByName('suscribe', $this->t('Please choose one option.'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    if ($this->currentUser()->isAnonymous()) {

      $form_state->setRedirect('association.membership1');

    }
    else {

      switch ($form_state->getValue('suscribe')) {
        case NULL:
          $form_state->setRedirectUrl(Url::fromRoute('<front>'));
          break;
        case 0:
          $this->store->set('status', 1);
          parent::saveData();
          $form_state->setRedirectUrl(Url::fromRoute('<front>'));
          break;
        case 1:
          $this->store->set('status', 3);
          $form_state->setRedirect('association.membership1');
          break;
      }
    }

  }

}
