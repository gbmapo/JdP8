<?php

namespace Drupal\association\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Render\FormattableMarkup;

/**
 * Class MembershipStep4.
 */
class MembershipStep4 extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'membership_step4';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $markup = new FormattableMarkup('<B>À utiliser dans les étapes « Adhérents » et « Coordonnées » :</B>
    <BR>&nbsp;&nbsp;Prénom : <span style="color: #0000ff;">' . $_SESSION['association']['firstname'] . '</span>
    <BR>&nbsp;&nbsp;Nom : <span style="color: #0000ff;">' . $_SESSION['association']['lastname'] . '</span>
    <BR>&nbsp;&nbsp;Désignation : <span style="color: #0000ff;">' . $_SESSION['association']['designation'] . '</span>
    <BR>&nbsp;&nbsp;Adresse email : <span style="color: #0000ff;">' . $_SESSION['association']['email'] . '</span>
    <BR><BR>', []);

    $form['header'] = [
      '#type'   => 'item',
      '#markup' => $markup,
    ];

    $config = Drupal::service('config.factory')
      ->getEditable('association.renewalperiod');
    $rpYear = $config->get('year');
    $thisMonth = $_SESSION['association']['anonymous'] ? date('Y-m') : $rpYear . '-00';
    $form['hello'] = [
      '#type'     => 'inline_template',
      '#template' => '<iframe id="haWidget" allowtransparency="true" scrolling="auto" src="https://www.helloasso.com/associations/le-jardin-de-poissy/adhesions/adhesion-' . $thisMonth . '/widget" style="width:100%;height:750px;border:none;" onload="window.scroll(0, this.offsetTop)"></iframe>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
  }

}
