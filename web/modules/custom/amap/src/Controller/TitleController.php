<?php

namespace Drupal\amap\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class TitleController.
 */
class TitleController extends ControllerBase
{

  public function titleMany()
  {

    $title = $this->t('Sign up for Planning <a class="form-submit" href="/amap/signupForPlanningOne">Little screen</a>');
    return $title;

  }

  public function titleOne()
  {

    $title = $this->t('Sign up for Planning <a class="form-submit" href="/amap/signupForPlanningMany">Big screen</a>');
    return $title;

  }

}
