<?php

namespace Drupal\sel\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Service edit forms.
 *
 * @ingroup sel
 */
class ServiceForm extends ContentEntityForm
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    /* @var $entity \Drupal\sel\Entity\Service */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['service']['widget']['0']['value']['#size'] = 128;

    $form['picture']['widget']['#open'] = FALSE;

    $form['fileDetails'] = [
      '#type'   => 'details',
      '#title'  => $this->t('File'),
      '#weight' => 8,
    ];
    $form['file']['#group'] = 'fileDetails';

    $form['link']['widget']['0']['#type'] = 'details';
    $form['link']['widget']['0']['title']['#access'] = FALSE;
    $form['link']['widget']['0']['uri']['#type'] = "url";
    $form['link']['widget']['0']['uri']['#link_type'] = 16;
    $form['link']['widget']['0']['uri']['#description'] = t('This must be an external URL such as %url.', ['%url' => 'http://example.com']);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
    if ($form_state->hasAnyErrors()) {
    }
    else {

      $values = $form_state->getValues();
      $sDueDate = $values['duedate'][0]['value']->format("Y-m-d");
      $sToday = strftime("%Y-%m-%d");
      $sIn2Weeks = strftime("%Y-%m-%d", strtotime("+ 2 weeks"));
      $sIn3Months = strftime("%Y-%m-%d", strtotime("+ 3 months"));

      if ($values['status']['value'] == 0) {
        //Pas de contrôle si le service n'est pas publié
      }
      else {
        if ($sDueDate <= $sToday) {
          $form_state->setErrorByName('duedate', $this->t('Due date must be in the future.'));
        }
        else {
          if ($values['isurgent']['value'] == 1) {
            if ($sDueDate > $sIn2Weeks) {
              $form_state->setErrorByName('duedate', $this->t('It is no longer quite urgent!<BR>Please change the due date or uncheck \'Urgent\'.'));
            }
          }
          else {
            if ($sDueDate > $sIn3Months) {
              $form_state->setErrorByName('duedate', $this->t('Validity period cannot exceed three months!'));
            }
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    $values = $form_state->getValues();
    if (($values['isurgent']['value'] == 1) && ($values['status']['value'] == 1)) {
      $iSeliste = $entity->owner_id->target_id;
      $oSeliste = \Drupal::entityTypeManager()->getStorage('person')->load($iSeliste);
      $sSeliste = $oSeliste->firstname->value . " " . $oSeliste->lastname->value;
      $sAction = ($values['action'][0]['value'] == 'O') ? "offre" : "demande";
      $sDueDate = $values['duedate'][0]['value']->format("d/m/Y");
      _sendEmailForUrgentService($sSeliste, $sAction, $sDueDate);
    }

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addMessage($this->t('Service « %label » has been added.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Service « %label » has been updated.', [
          '%label' => $entity->label(),
        ]));
    }
    $id = \Drupal::currentUser()->id();
    $form_state->setRedirect('view.sel_services.page_2', ['arg_0' => $id]);
  }

}
