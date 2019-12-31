<?php

namespace Drupal\amap\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class ContractsController.
 */
class ContractsController extends ControllerBase
{

  public function showContracts()
  {

    $oCurrentUser = $this->currentUser();

    switch (true) {
      case ($oCurrentUser->isAnonymous()):
        return $this->redirect('view.amap_contracts.page_3');
        break;
      case ($oCurrentUser->hasPermission('add contract entities')):
        return $this->redirect('view.amap_contracts.page_1');
        break;
      default:
        return $this->redirect('view.amap_contracts.page_2');
    }
  }

  public function export_subscriptions($contract)
  {

    _export_amap_CSV('amap_contracts_subscriptions', 'rest_export_1', $contract);

    $sFileName = 'Contract-' . $contract . '-export.csv';
    $sFileNameWithPath = 'sites/default/files/_private/contracts/' . $sFileName;
    $response = new BinaryFileResponse($sFileNameWithPath);
    $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $sFileName);

    return $response;

  }

}

