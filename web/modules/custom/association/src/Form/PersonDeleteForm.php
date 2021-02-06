<?php

namespace Drupal\association\Form;

use Drupal;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Url;

/**
 * Provides a form for deleting Person entities.
 *
 * @ingroup association
 */
class PersonDeleteForm extends ContentEntityDeleteForm
{

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $entity = $this->getEntity();
    $entity->delete();

    $form_state->setRedirect('view.association_persons.page_1');
    Drupal::messenger()->addMessage($this->getDeletionMessage());

  }

  public function getQuestion()
  {
    return $this->t('Are you sure you want to delete person « %label »?', [
      '%label' => $this->getEntity()->label(),
    ]);
  }

  public function getCancelUrl()
  {
    return Url::fromRoute('view.association_persons.page_1');
  }

  protected function getDeletionMessage()
  {
    $entity = $this->getEntity();
    Drupal::messenger()->addMessage($this->t('Person « %label » has been deleted.', [
      '%label' => $entity->label(),
    ]));

  }

}
