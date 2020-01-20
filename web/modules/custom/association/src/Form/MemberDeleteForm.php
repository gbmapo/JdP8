<?php

namespace Drupal\association\Form;

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

    // List all Persons for the current Member
    $id = $entity->id->value;
    $database = \Drupal::database();
    $query = $database->select('person', 'ap');
    $query->fields('ap', ['id', 'member_id'])
      ->condition('member_id', $id, '=');
    $results = $query->execute();
    // Undefine "Contact for Member" for these Persons
    $storage = \Drupal::entityTypeManager()->getStorage('person');
    foreach ($results as $key => $result) {
      $person = $storage->load($result->id);
      $person->set("member_id", "");
      if ($person->iscontact->value) {
        $person->set("iscontact", 0);
        $usertemp = User::load($result->id);
        $usertemp->removeRole('contact_for_member');
        $usertemp->save();
      }
      $person->save();
    }
    $entity->delete();

    $form_state->setRedirect('view.association_members.page_1');
    \Drupal::messenger()->addMessage($this->getDeletionMessage());

  }

  public function getQuestion()
  {
    return $this->t('Are you sure you want to delete member « %label »?', array(
      '%label' => $this->getEntity()->label()
    ));
  }

  public function getCancelUrl()
  {
    return Url::fromRoute('view.association_members.page_1');
  }

  protected function getDeletionMessage()
  {
    $entity = $this->getEntity();
    \Drupal::messenger()->addMessage($this->t('Member « %label » has been deleted.', array(
      '%label' => $entity->label()
    )));

  }

}
