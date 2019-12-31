<?php

namespace Drupal\amap\Form;

use \Drupal\Core\Entity\ContentEntityDeleteForm;
use \Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Url;

/**
 * Provides a form for deleting Contract entities.
 *
 * @ingroup amap
 */
class ContractDeleteForm extends ContentEntityDeleteForm
{

  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    $entity = $this->getEntity();
    $entity->delete();

    $form_state->setRedirect('view.amap_contracts.page_1');
    \Drupal::messenger()->addMessage($this->getDeletionMessage());

  }

  public function getQuestion()
  {
    return $this->t('Are you sure you want to delete contract « %label »?', [
      '%label' => $this->getEntity()->label()
    ]);
  }

  public function getCancelUrl()
  {
    return Url::fromRoute('view.amap_contracts.page_1');
  }

  protected function getDeletionMessage()
  {
    $entity = $this->getEntity();
    \Drupal::messenger()->addMessage($this->t('Contract « %label » has been deleted.', array(
      '%label' => $entity->label()
    )));
  }

}
