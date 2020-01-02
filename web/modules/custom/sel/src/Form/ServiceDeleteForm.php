<?php

namespace Drupal\sel\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting Service entities.
 *
 * @ingroup sel
 */
class ServiceDeleteForm extends ContentEntityDeleteForm
{

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $entity = $this->getEntity();
    $entity->delete();

    $id = \Drupal::currentUser()->id();
    $form_state->setRedirect('view.sel_services.page_2', array('arg_0' => $id));
    \Drupal::messenger()->addMessage($this->getDeletionMessage());
  }

  public function getQuestion()
  {
    return $this->t('Are you sure you want to delete service « %label »?', array(
      '%label' => $this->getEntity()->label()
    ));
  }

  public function getCancelUrl()
  {
    $id = \Drupal::currentUser()->id();
    return Url::fromRoute('view.sel_services.page_2', array('arg_0' => $id));
  }

  protected function getDeletionMessage()
  {
    $entity = $this->getEntity();
    \Drupal::messenger()->addMessage($this->t('Service « %label » has been deleted.', array(
      '%label' => $entity->label()
    )));
  }
}
