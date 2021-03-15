<?php

namespace Drupal\association\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class MembersAndPersonsController.
 */
class MembersAndPersonsController extends ControllerBase
{

  public function export_personsformaps()
  {

    _export_association_CSV('association_persons', 'rest_export_3');
    $sFileName = 'export_personsformaps.csv';
    $sFileNameWithPath = 'sites/default/files/_private/' . $sFileName;
    $response = new BinaryFileResponse($sFileNameWithPath);
    $response->setContentDisposition(
      ResponseHeaderBag::DISPOSITION_ATTACHMENT,
      $sFileName
    );

    return $response;

  }

}
