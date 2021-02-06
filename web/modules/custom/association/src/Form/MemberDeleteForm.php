<?php

namespace Drupal\association\Form;

use Drupal;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Url;

/**
 * Provides a form for deleting Member entities.
 *
 * @ingroup association
 */
class MemberDeleteForm extends ContentEntityDeleteForm
{

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $entity = $this->getEntity();
    $entity->delete();

    $form_state->setRedirect('view.association_members.page_1');
    Drupal::messenger()->addMessage($this->getDeletionMessage());

  }

  public function getQuestion()
  {
    return $this->t('Are you sure you want to delete member « %label »?', [
      '%label' => $this->getEntity()->label(),
    ]);
  }

  public function getCancelUrl()
  {
    return Url::fromRoute('view.association_members.page_1');
  }

  protected function getDeletionMessage()
  {
    $entity = $this->getEntity();
    Drupal::messenger()->addMessage($this->t('Member « %label » has been deleted.', [
      '%label' => $entity->label(),
    ]));

  }

}
