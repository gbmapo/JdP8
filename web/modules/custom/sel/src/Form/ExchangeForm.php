<?php

namespace Drupal\sel\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Exchange edit forms.
 *
 * @ingroup sel
 */
class ExchangeForm extends ContentEntityForm
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    /* @var $entity \Drupal\sel\Entity\Exchange */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    $form['from_seliste_id']['#attributes'] = array(
      'onchange' => "hasChanged(this)"
    );

    $form['to_seliste_id']['#attributes'] = array(
      'onchange' => "hasChanged(this)"
    );

    $oCurrentUser = \Drupal::currentUser();
    $iCurrentUserId = $oCurrentUser->id();
    $form['current_user'] = array(
      '#type' => 'hidden',
      '#default_value' => $iCurrentUserId,
      '#attributes' => array('id' => 'current-user-id'),
    );

    $form['#attached']['library'][] = 'sel/echange';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    $values = $form_state->getValues();
    $sSeliste = $entity->from_seliste_id->target_id;
    $sSeliste .= '|' . $entity->to_seliste_id->target_id;
    $sDate = $values['date'][0]['value']->format("d/m/Y");

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addMessage($this->t('Exchange « %label » has been added.', [
          '%label' => $entity->label(),
        ]));
        $sAction = 'ajouté';
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Exchange « %label » has been updated.', [
          '%label' => $entity->label(),
        ]));
        $sAction = 'modifié';
    }
    _sendEmailForExchange(array($sDate, $sAction, $sSeliste));

    $form_state->setRedirect('view.sel_echanges.page_1');
  }

}
