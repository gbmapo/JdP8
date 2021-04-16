<?php

namespace Drupal\sel\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting Exchange entities.
 *
 * @ingroup sel
 */
class ExchangeDeleteForm extends ContentEntityDeleteForm
{

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $entity = $this->getEntity();

    $sSeliste = $entity->from_seliste_id->target_id;
    $sSeliste .= '|' . $entity->to_seliste_id->target_id;
    $sDate = $entity->date->value;
    $aDate = explode("-", $sDate);
    $sDate = implode("/", array_reverse($aDate));
    $sAction = 'supprimé';
    _sendEmailForExchange([
      $sDate,
      $sAction,
      $sSeliste
    ]);

    $entity->delete();
    $id = \Drupal::currentUser()->id();
    $form_state->setRedirect('view.sel_echanges.page_1');
    \Drupal::messenger()->addMessage($this->getDeletionMessage());

  }

  public function getQuestion()
  {
    return $this->t('Are you sure you want to delete exchange « %label »?', array(
      '%label' => $this->getEntity()->label()
    ));
  }

  public function getCancelUrl()
  {
    $id = \Drupal::currentUser()->id();
    return Url::fromRoute('view.sel_echanges.page_2', array('arg_0' => $id, 'arg_1' => $id));
  }

  protected function getDeletionMessage()
  {

    $entity = $this->getEntity();
    \Drupal::messenger()->addMessage($this->t('Exchange « %label » has been deleted.', array(
      '%label' => $entity->label()
    )));

  }

}
