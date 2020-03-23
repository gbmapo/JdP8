<?php

namespace Drupal\association\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;


/**
 * Class MembershipSettings.
 */
class MembershipSettings extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'membership_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config       = \Drupal::config('association.renewalperiod');
    $rpStep       = $config->get('step');
    $rpYear       = $config->get('year');
    $rpStatus     = $config->get('status');
    $rpFirstEmail = $config->get('firstemail');
    $rpReminder   = $config->get('reminder');

    if ($rpStep == 0) {
      $iY1                   = (int) strftime("%Y");
      $iY2                   = $iY1 + 1;
      $form['actions']['1B'] = [
        '#type'          => 'select',
        '#title'         => "<BR>1. " . $this->t("Choose year and open renewal period."),
        '#options'       => [
          ""   => "--",
          $iY1 => $iY1,
          $iY2 => $iY2,
        ],
        '#default_value' => $rpYear,
      ];
    }
    else {
      $form['actions']['1A'] = [
        '#markup' => "<BR>1. " . $this->t('The renewal period has been opened for year « %year ».', ['%year' => $rpYear]),
      ];


      if ($rpStep == 1) {
        $form['actions']['2B'] = [
          '#type'          => 'radios',
          '#title'         => "2. " . $this->t('Do you want to send the first email?'),
          '#options'       => [
            0 => $this->t('No'),
            1 => $this->t('Yes'),
          ],
          '#default_value' => 0,
        ];
      }
      else {
        $form['actions']['2A'] = [
          '#markup' => '<BR>' . "2. " . $this->t('The first email has been sent.'),
        ];
        if ($rpStep == 2) {
        }
        else {
          $form['actions']['3A'] = [
            '#markup' => '<BR>' . "3. " . $this->t('The reminder email has been sent (total number of reminders: %reminders).', ['%reminders' => $rpReminder]),
          ];
        }
        $form['actions']['3B'] = [
          '#type'          => 'radios',
          '#title'         => "3. " . $this->t('Do you want to send the reminder email?'),
          '#options'       => [
            0 => $this->t('No'),
            1 => $this->t('Yes'),
          ],
          '#default_value' => 0,
        ];
      }
      $form['actions']['4B'] = [
        '#type'          => 'radios',
        '#title'         => $this->t('Do you want to close the renewal period?'),
        '#options'       => [
          0 => $this->t('No'),
          1 => $this->t('Yes'),
        ],
        '#default_value' => 0,
      ];
    }

    $form['submit'] = [
      '#type'   => 'submit',
      '#value'  => $this->t('Submit'),
      '#prefix' => '<BR>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $config = \Drupal::config('association.renewalperiod');
    $rpStep = $config->get('step');
    switch ($rpStep) {
      case 0:
        if ($form_state->getValue('1B') == "") {
          $form_state->setErrorByName('1A', $this->t('Please choose one option.'));
        }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config       = \Drupal::service('config.factory')
      ->getEditable('association.renewalperiod');
    $rpStep       = $config->get('step');
    $rpYear       = $config->get('year');
    $rpStatus     = $config->get('status');
    $rpFirstEmail = $config->get('firstemail');
    $rpReminder   = $config->get('reminder');

    $sTo = \Drupal::config('system.site')->get('mail');

    $sMessage = '';
    $sType    = 'status';
    switch ($rpStep) {

      case 0:
        $config->set('step', 1);
        $config->set('year', $form_state->getValue('1B'));
        $config->set('status', 'Opened');
        $config->set('firstemail', FALSE);
        $config->set('reminder', 0);
        // Autoriser les adhérents à accéder au formulaire
        $role = Role::create(['id' => 'member', 'label' => 'Adhérent']);
        $role->grantPermission('renew membership');
        $role->save();
        $database = \Drupal::database();
        $query    = $database->select('person', 'pe');
        $query->fields('pe', ['id', 'member_id ']);
        $query->condition('member_id', 0, '>');
        $ids = $query->execute()->fetchCol(0);
        $users = User::loadMultiple($ids);
        foreach ($users as $user) {
          $user->addRole('member');
          $user->save();
        }
        // Mettre le statut de tous les adhérents actifs à 'Adhésion en attente'
        $storage  = \Drupal::entityTypeManager()->getStorage('member');
        $database = \Drupal::database();
        $query    = $database->select('member', 'am');
        $query->fields('am', ['id', 'status'])->condition('am.status', 4, '=');
        $results = $query->execute();
        $iNumber = 0;
        foreach ($results as $key => $result) {
          $entity         = $storage->load($result->id);
          $entity->status = 2;
          $entity->save();
          $iNumber++;
        }
        \Drupal::logger('association')
          ->info('Renew membership: Period has been opened.');
        \Drupal::logger('association')
          ->info('Renew membership: Number of members: @number.', ['@number' => $iNumber]);
        $sMessage = $this->t('Renew membership: Period has been opened.');
        break;

      case 1:
        if ($form_state->getValue('2B') == "1") {
          $config->set('step', 2);
          $config->set('firstemail', TRUE);
          // Envoyer le premier courriel
          $sTo     = $sTo;
          $sBcc    = _setListOfRecipients(2);
          $aParams = [$sBcc, $rpYear];
          \Drupal::service('plugin.manager.mail')
            ->mail('association', 'membershipfirstemail', $sTo, 'fr', $aParams);
          \Drupal::logger('association')
            ->info('Renew membership: First email has been sent.');
          $sMessage = $this->t('Renew membership: First email has been sent.');
        }
        break;

      case 2:
      case 3:
        if ($form_state->getValue('3B') == "1") {
          $config->set('step', 3);
          $rpReminder = $config->get('reminder') + 1;
          $config->set('reminder', $rpReminder);
          // Envoyer un courriel de relance
          $sTo     = $sTo;
          $sBcc    = _setListOfRecipients(2);
          $aParams = [$sBcc, $rpYear, $rpReminder];
          \Drupal::service('plugin.manager.mail')
            ->mail('association', 'membershipreminderemail', $sTo, 'fr', $aParams);
          \Drupal::logger('association')
            ->info('Renew membership: Reminder email @number has been sent.', ['@number' => $rpReminder]);
          $sMessage = $this->t('Renew membership: Reminder email @number has been sent.', ['@number' => $rpReminder]);
        }
        break;

      default:
        $sMessage = $this->t('Renew membership: Unexpected case.');
        $sType    = 'warning';
    }

    if ($form_state->getValue('4B') == "1") {

      $config->set('step', 0);
      $config->set('year', '');
      $config->set('status', 'Closed');
      $config->set('firstemail', FALSE);
      $config->set('reminder', 0);

      // Mettre le statut de tous les adhérents en attente à 'Ancien adhérent'
      $storage  = \Drupal::entityTypeManager()->getStorage('member');
      $database = \Drupal::database();
      $query    = $database->select('member', 'am');
      $query->fields('am', ['id', 'status'])->condition('am.status', 2, '=');
      $results = $query->execute();
      $iNumber = 0;
      $enddate = ($rpYear - 1) . '-12-31';
      foreach ($results as $key => $result) {
        $entity          = $storage->load($result->id);
        $entity->status  = 0;
        $entity->enddate = $enddate;
        $comment = $entity->comment->getString();
        $comment = ($comment ? $comment . "\r" : '') . $this->t('Status changed when Renew Membership period was closed.');
        $entity->comment = $comment;
        $entity->save();
        $iNumber++;
      }

      // Enlever l'accès au formulaire aux adhérents
      $ids   = \Drupal::entityQuery('user')
        ->condition('roles', 'member')
        ->execute();
      $users = User::loadMultiple($ids);
      foreach ($users as $user) {
        $user->removeRole('member');
        $user->save();
      }
      $role = Role::load('member');
      $role->delete();

      \Drupal::logger('association')
        ->info('Renew membership: Period has been closed.');
      $sMessage = $this->t('Renew membership: Period has been closed.');
      \Drupal::logger('association')
        ->info('Renew membership: Number of members: @number.', ['@number' => $iNumber]);
    }

    $config->save();

    if ($sMessage != '') {
      \Drupal::messenger()->addMessage($sMessage, $sType);

    }
  }

}
