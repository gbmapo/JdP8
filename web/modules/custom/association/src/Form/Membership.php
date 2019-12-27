<?php

namespace Drupal\association\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\Role;
use Drupal\user\RoleInterface;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Url;

/**
 * Class Membership.
 */
class Membership extends FormBase
{


  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'membership';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $oCurrentUser = \Drupal::currentUser();
    $iCurrentUserId = $oCurrentUser->id();

    $config = \Drupal::config('association.renewalperiod');
    $rpYear = $config->get('year');
    $rpStatus = $config->get('status');

    if ($rpStatus == 'Closed') {
      $form['header'] = [
        '#markup' => $this->t("There is currently no renewal period opened.") . '<BR>'
      ];

    } elseif ($oCurrentUser->hasPermission("renew membership")) {

      $database = \Drupal::database();
      $query = $database->select('person', 'ap');
      $query->leftJoin('member', 'am', 'am.id = ap.member_id');
      $query->fields('ap', ['id', 'lastname', 'firstname', 'user_id'])
        ->fields('am', ['id', 'designation', 'status'])
        ->condition('ap.user_id', $iCurrentUserId, '=');
      $results = $query->execute();
      $result = $results->fetchAssoc();
      if ($result['am_id'] != null) {
        switch ($result['status']) {
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
        $sMember = new FormattableMarkup('<span style="color: #0000ff;">' . $result['designation'] . '</span>', []);
        $sPerson = new FormattableMarkup('<span style="color: #0000ff;">' . $result['firstname'] . ' ' . $result['lastname'] . '</span>', []);
        if ($result['status'] == 4) {

          $sTemp = '<BR>' . $this->t('The member « %member » has already renewed his membership to the association <I>Le Jardin de Poissy</I> for year « %year ».',
              ['%member' => $sMember, '%year' => $rpYear]);
          $form['header'] = [
            '#type' => 'inline_template',
            '#template' => $sTemp,
          ];

        } else {

          $sTemp = $this->t("Here’s your wish as recorded. You can change it as many times as you like: only the last change will be taken into account.<BR><BR>");
          $sTemp = ($iWish == -1) ? "" : $sTemp;
          $sTemp2 = $this->t('I, the undersigned « %person », representing the member « %member », wishes to renew my membership to the association <I>Le Jardin de Poissy</I> for year « %year ».',
            ['%person' => $sPerson, '%member' => $sMember, '%year' => $rpYear]);
          $sTemp = $sTemp . $sTemp2;
          $form['header'] = [
            '#type' => 'inline_template',
            '#template' => $sTemp,
          ];
          $form['suscribe'] = [
            '#type' => 'radios',
            '#title' => '',
            '#options' => [
              0 => $this->t('No'),
              1 => $this->t('Yes'),
            ],
            '#default_value' => $iWish,
            '#attributes' => [
              'onchange' => 'hasChanged(this)'
            ],
            '#validated' => TRUE,
          ];
          $form['fs1'] = [
            '#markup' => '<div id="fs1">' . $this->t("Once downloaded, I'll print my membership form, I'll make the necessary changes and send it to:<BR><BR><I>Le Jardin de Poissy<BR>28 bis boulevard Victor Hugo<BR>78300 Poissy</I>.<BR><BR>") . '</div>'
          ];
        }
      }
      $form['adherent'] = array(
        '#type' => 'hidden',
        '#value' => array(
          $result['am_id'],
          $result['designation']
        )
      );
      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ];

    } else {

      $form['header'] = [
        '#markup' => $this->t("You're not allowed to renew membership. Only the « contact for member » is allowed to do it.") . '<BR>'
      ];

    }

    return $form;

  }


  /**
   * {@inheritdoc}
   */
  public
  function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
    if ($form_state->getValue('suscribe') == -1) {
      $form_state->setErrorByName('suscribe', $this->t('Please choose one option.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    switch ($form_state->getValue('suscribe')) {
      case 0:
        $iStatus = 1;
        $sMessage = $this->t('Your wish has been recorded.');
        break;
      case 1:
        $iStatus = 3;
        $Url = Url::fromUri('base:/association/membership/download/' . $form_state->getValue('adherent')[0]);
        $sLink = \Drupal\Core\Link::fromTextAndUrl($this->t('here'), $Url)->toString();
        $sMessage = $this->t('Your wish has been recorded.<BR>To download your membership form, please click %link.', ['%link' => $sLink]);
        break;
    }
    $storage = \Drupal::entityTypeManager()->getStorage('member');
    $id = $form_state->getValue('adherent')[0];
    $entity = $storage->load($id);
    $entity->status = $iStatus;
    $entity->save();
    \Drupal::messenger()->addMessage($sMessage);
  }
}
