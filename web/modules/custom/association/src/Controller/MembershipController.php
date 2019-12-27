<?php

namespace Drupal\association\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class MembershipController.
 */
class MembershipController extends ControllerBase
{

  /**
   * Showmembership.
   */
  public function showMembership()
  {

    if ($this->currentUser()->isAnonymous()) {
      return $this->redirect('entity.node.canonical', ['node' => 3]);
    }
    return $this->formBuilder()->getForm('Drupal\association\Form\Membership');
  }

  public function download($member)
  {

    $storage = \Drupal::entityTypeManager()->getStorage('member');
    $entity = $storage->load($member);
    $sFileName = 'sites/default/files/_private/bulletins/' . $entity->designation->value . '.pdf';
    $response = new BinaryFileResponse($sFileName);
    $response->setContentDisposition(
      ResponseHeaderBag::DISPOSITION_ATTACHMENT,
      'bulletin.pdf'
    );

    return $response;

  }

}
