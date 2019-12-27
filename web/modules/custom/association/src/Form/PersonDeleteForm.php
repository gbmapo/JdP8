<?php

namespace Drupal\association\Form;

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

    if ($entity->iscontact->value) {
      // Reset User for this Person
      $user_id = $entity->user_id->target_id;
      $userofperson = User::load($user_id);
      $userofperson->removeRole('contact_for_member');
      $userofperson->set("status", 0);
      $userofperson->save();
      // Reset corresponding Member
      $storage = \Drupal::entityTypeManager()->getStorage('member');
      $member = $storage->load($entity->member_id->target_id);
      $member->set("contact_id", NULL);
      $member->save();
    }
    $entity->delete();

    _export_association_CSV('association_persons', 'rest_export_1');
    _export_association_CSV('association_members_and_persons', 'rest_export_1');

    $form_state->setRedirect('view.association_persons.page_1');
    \Drupal::messenger()->addMessage($this->getDeletionMessage());

  }

  public function getQuestion()
  {
    return $this->t('Are you sure you want to delete person « %label »?', array(
      '%label' => $this->getEntity()->label()
    ));
  }

  public function getCancelUrl()
  {
    return Url::fromRoute('view.association_persons.page_1');
  }

  protected function getDeletionMessage()
  {
    $entity = $this->getEntity();
    \Drupal::messenger()->addMessage($this->t('Person « %label » has been deleted.', array(
      '%label' => $entity->label()
    )));

  }

}
