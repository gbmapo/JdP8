<?php

namespace Drupal\association\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Form controller for Person edit forms.
 *
 * @ingroup association
 */
class PersonForm extends ContentEntityForm
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    /* @var $entity \Drupal\association\Entity\Person */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);

    $person_id = $this->entity->id->value;
    $user_id = $form_state->getValue('user_id')['0']['target_id'];
    $storage = \Drupal::entityTypeManager()->getStorage('person');
    $person = $storage->load($user_id);
    if ($user_id == $person_id) {
    } else {
      if ($person != null) {
        $form_state->setErrorByName('name', $this->t('This user is already associated to another person.<BR>Please choose another user.'));
      }
    }

  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    $entity = $this->entity;

    $user_id = $entity->user_id->target_id;
    $userofperson = User::load($user_id);

    $entity->set('id', $user_id);
    $entity->set('email', $userofperson->getEmail());
    if ($entity->isactive->value) {
      $userofperson->set("status", 1);
    } else {
      $userofperson->set("status", 0);
      $entity->set("iscontact", 0);
    }
//     if ($entity->member_id->target_id == null) {
//       $userofperson->removeRole('member');
//     } else {
//       $userofperson->addRole('member');
//     }

    if ($entity->iscontact->value) {
      // List all persons for the current member
      $member_id = $entity->member_id->target_id;
      $iId = $entity->id->value;
      $database = \Drupal::database();
      $query = $database->select('person', 'ap');
      $query->fields('ap', ['id', 'member_id'])
        ->condition('id', $iId, '<>')
        ->condition('member_id', $member_id, '=');
      $results = $query->execute();
      // Undefine "Contact for Member" for these persons
      $storage = \Drupal::entityTypeManager()->getStorage('person');
      foreach ($results as $key => $result) {
        $person = $storage->load($result->id);
        $person->iscontact = 0;
        $person->save();
        $usertemp = User::load($result->id);
        $usertemp->removeRole('contact_for_member');
        $usertemp->save();
      }
      // Define the current Person as "Contact for Member"
      $storage = \Drupal::entityTypeManager()->getStorage('member');
      $member = $storage->load($entity->member_id->target_id);
      $member->contact_id = $user_id;
      $member->save();
      $userofperson->addRole('contact_for_member');
    } else {
      $userofperson->removeRole('contact_for_member');
    }

    $userofperson->save();

    $status = parent::save($form, $form_state);
    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addMessage($this->t('Person « %label » has been added.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Person « %label » has been updated.', [
          '%label' => $entity->label(),
        ]));
    }

    _export_association_CSV('association_persons', 'rest_export_1');
    _export_association_CSV('association_members_and_persons', 'rest_export_1');

    $form_state->setRedirect('view.association_persons.page_1');

  }

}
