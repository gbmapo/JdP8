<?php

namespace Drupal\association\Form\Multistep;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\user\Entity\User;

abstract class MembershipFormBase extends FormBase {

  /**
   * @var \Drupal\user\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * @var \Drupal\user\PrivateTempStore
   */
  protected $store;

  /**
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  private $sessionManager;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  private $currentUser;

  /**
   * Constructs a \Drupal\association\Form\Multistep\MembershipFormBase.
   *
   * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
   * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
   * @param \Drupal\Core\Session\AccountInterface $current_user
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, SessionManagerInterface $session_manager, AccountInterface $current_user) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->sessionManager   = $session_manager;
    $this->currentUser      = $current_user;

    $this->store = $this->tempStoreFactory->get('multistep_data');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('user.private_tempstore'), $container->get('session_manager'), $container->get('current_user'));
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Start a manual session for anonymous users.
    if ($this->currentUser->isAnonymous() && !isset($_SESSION['multistep_form_holds_session'])) {
      $_SESSION['multistep_form_holds_session'] = TRUE;
      $this->sessionManager->start();
    }


    if ($this->store->get('firstTime') == '') {
      $this->store->set('firstTime', 'NO');

      if ($this->currentUser->isAnonymous()) {
      }
      else {

        $storage   = Drupal::entityTypeManager()->getStorage('person');
        $person    = $storage->load($this->currentUser->id());
        $member_id = $person->get("member_id")->target_id;

        $database = Drupal::database();
        $query    = $database->select('member', 'am');
        $query->leftJoin('person', 'ap', 'ap.member_id = am.id');
        $query->leftJoin('person__field_sel_isseliste', 'ps', 'ap.id = ps.entity_id');
        $query->leftJoin('users_field_data', 'us', 'us.uid = ps.entity_id');
        $query->fields('am', [
          'id',
          'designation',
          'addresssupplement',
          'street',
          'postalcode',
          'city',
          'contact_id',
          'telephone',
          'status',
        ]);
        $query->fields('ap', [
          'id',
          'lastname',
          'firstname',
          'email',
          'cellphone',
          'iscontact',
        ]);
        $query->fields('ps', ['field_sel_isseliste_value',]);
        $query->fields('us', ['uid', 'name']);
        $query->condition('am.id', $member_id, '=');
        $query->orderBy('iscontact', 'DESC');
        $results = $query->execute()->fetchAll();

        $this->store->set('am_id', $results[0]->id);
        $this->store->set('designation', $results[0]->designation);
        $this->store->set('addresssupplement', $results[0]->addresssupplement);
        $this->store->set('street', $results[0]->street);
        $this->store->set('postalcode', $results[0]->postalcode);
        $this->store->set('city', $results[0]->city);
        $this->store->set('telephone', $results[0]->telephone);
        $this->store->set('status', $results[0]->status);

        $this->store->set('ap_id1', $results[0]->ap_id);
        $this->store->set('lastname1', $results[0]->lastname);
        $this->store->set('firstname1', $results[0]->firstname);
        $this->store->set('email1', $results[0]->email);
        $this->store->set('cellphone1', $results[0]->cellphone);
        $this->store->set('seliste1', $results[0]->field_sel_isseliste_value);
        $this->store->set('name1', $results[0]->name);
        if (count($results) > 1) {
          $this->store->set('ap_id2', $results[1]->ap_id);
          $this->store->set('lastname2', $results[1]->lastname);
          $this->store->set('firstname2', $results[1]->firstname);
          $this->store->set('email2', $results[1]->email);
          $this->store->set('cellphone2', $results[1]->cellphone);
          $this->store->set('seliste2', $results[1]->field_sel_isseliste_value);
          $this->store->set('name2', $results[1]->name);
        }
        if ($this->currentUser->getUsername() == $results[0]->name) {
          $this->store->set('lastname', $results[0]->lastname);
          $this->store->set('firstname', $results[0]->firstname);
          $this->store->set('email', $results[0]->email);
        }
        else {
          $this->store->set('lastname', $results[1]->lastname);
          $this->store->set('firstname', $results[1]->firstname);
          $this->store->set('email', $results[1]->email);
        }
      }
    }

    $form                      = [];
    $form['actions']['#type']  = 'actions';
    $form['actions']['submit'] = [
      '#type'        => 'submit',
      '#value'       => $this->t('Submit'),
      '#button_type' => 'primary',
      '#weight'      => 10,
    ];

    return $form;
  }

  /**
   * Saves the data from the multistep form.
   */
  protected function saveData() {

    $numberOfPersons = $this->store->get('lastname2') ? 2 : 1;
    $now             = \Drupal::time()->getRequestTime();
    $database        = \Drupal::database();

    if ($this->currentUser->isAnonymous()) {

      // Create user(s)
      $uid = [];
      for ($i = 1; $i <= $numberOfPersons; $i++) {
        $user = \Drupal\user\Entity\User::create();
        $user->setPassword('passwordtobechanged');
        $user->enforceIsNew();
        $user->setEmail($this->store->get('email' . $i));
        $user->setUsername($this->generateName($this->store->get('firstname' . $i), $this->store->get('lastname' . $i)));
        $user->block();
        if ($i == 1) {
          $user->addRole('contact_for_member');
        }
        if ($this->store->get('seliste' . $i) == 1) {
          $user->addRole('seliste');
        }
        $user->save();
        $uid[$i] = $user->id();
        $mail    = _user_mail_notify('register_pending_approval', $user);
      }

      // Prepare Member
      $idM           = NULL;
      $comment    = ($this->store->get('contracts') ? $this->store->get('contracts') . ' ' : '') . $this->store->get('payment');
      $insertFieldsM = [
        'comment'    => $comment,
        'contact_id' => $uid['1'],
        'created'    => $now,
        'enddate'    => '2037-12-30',
        'owner_id'   => $uid['1'],
        'startdate'  => date('Y-m-d'),
      ];
      $this->store->set('status', 5);

    }
    else {
      $insertFieldsM = [];
      $idM           = $this->store->get('am_id');
    }

    // Member fields
    $updateFieldsM = [];
    $fieldsM       = [
      'addresssupplement' => $this->store->get('addresssupplement'),
      'changed'           => $now,
      'city'              => $this->store->get('city'),
      'country'           => 'FR',
      'designation'       => $this->store->get('designation'),
      'postalcode'        => $this->store->get('postalcode'),
      'street'            => $this->store->get('street'),
      'telephone'         => $this->store->get('telephone'),
      'status'            => $this->store->get('status'),
    ];

    // Insert or Update member
    $insertFieldsM = array_merge($insertFieldsM, $fieldsM);
    $updateFieldsM = array_merge($updateFieldsM, $fieldsM);
    $database->merge('member')
      ->insertFields($insertFieldsM)
      ->updateFields($updateFieldsM)
      ->key('id', $idM)
      ->execute();

    $query = $database->select('member', 'am');
    $query->fields('am', ['id', 'created']);
    $query->condition('created', $now, '=');
    $idMnew = $query->execute()->fetchCol()[0];

    // Prepare Person(s)
    if ($this->currentUser->isAnonymous()) {
      $idP           = [];
      $insertFieldsP = [];
      for ($i = 1; $i <= $numberOfPersons; $i++) {
        $idP[$i]           = $uid[$i];
        $insertFieldsP[$i] = [
          'comment'   => NULL,
          'created'   => $now,
          'isactive'  => 0,
          'iscontact' => $i == 1 ? 1 : 0,
          'member_id' => $idMnew,
          'owner_id'  => $uid['1'],
          'user_id'   => $uid[$i],
        ];
      }
    }
    else {
      $insertFieldsP[1] = [];
      $insertFieldsP[2] = [];
      $idP[1]           = $this->store->get('ap_id1');
      $idP[2]           = $this->store->get('ap_id2');
    }

    // Person(s) fields
    $updateFieldsP = [];
    $fieldsP       = [];
    for ($i = 1; $i <= $numberOfPersons; $i++) {
      $updateFieldsP[$i] = [];
      $fieldsP[$i]       = [
        'cellphone' => $this->store->get('cellphone' . $i),
        'changed'   => $now,
        'email'     => $this->store->get('email' . $i),
        'firstname' => $this->store->get('firstname' . $i),
        'lastname'  => $this->store->get('lastname' . $i),
      ];
    }

    // Insert or Update person(s), Update user(s)
    for ($i = 1; $i <= $numberOfPersons; $i++) {

      $insertFieldsP[$i] = array_merge($insertFieldsP[$i], $fieldsP[$i]);
      $updateFieldsP[$i] = array_merge($updateFieldsP[$i], $fieldsP[$i]);
      $database->merge('person')
        ->insertFields($insertFieldsP[$i])
        ->updateFields($updateFieldsP[$i])
        ->key('id', $idP[$i])
        ->execute();

      $database->merge('person__field_sel_isseliste')
        ->key('entity_id', $idP[$i])
        ->fields([
          'bundle'                    => 'person',
          'entity_id'                 => $idP[$i],
          'revision_id'               => $idP[$i],
          'langcode'                  => 'und',
          'delta'                     => 0,
          'field_sel_isseliste_value' => $this->store->get('seliste' . $i),
        ])
        ->execute();

      if ($this->store->get('seliste' . $i) == 1) {
        $database->merge('person__field_sel_balance')
          ->key('entity_id', $idP[$i])
          ->fields([
            'bundle'                  => 'person',
            'entity_id'               => $idP[$i],
            'revision_id'             => $idP[$i],
            'langcode'                => 'und',
            'delta'                   => 0,
            'field_sel_balance_value' => 180,
          ])
          ->execute();
      }

      $user = User::load($idP[$i]);
      $user->setEmail($this->store->get('email' . $i));
      if ($this->store->get('seliste' . $i) == 1) {
        $user->addRole('seliste');
      }
      else {
        $user->removeRole('seliste');
      }
      $user->save();

    }

    $_SESSION['association']['anonymous']   = $this->currentUser->isAnonymous();
    $_SESSION['association']['designation'] = $this->store->get('designation');
    $_SESSION['association']['lastname']    = $this->store->get('lastname');
    $_SESSION['association']['firstname']   = $this->store->get('firstname');
    $_SESSION['association']['email']       = $this->store->get('email');

    if ($this->currentUser->isAnonymous()) {
      $str      = $this->t('membership request');
      $sMessage = $this->t('Your @str has been registered.<BR>It will be effective after reception of your payment.', ['@str' => $str]);
      Drupal::messenger()->addMessage($sMessage);
      $sMessage = $this->t('A confimation email has been sent.');
      Drupal::messenger()->addMessage($sMessage);
    }
    else {
      switch ($this->store->get('status')) {
        case 1:
          $sMessage = $this->t('Your wish has been recorded.');
          break;
        case 3:
          $str      = $this->t('membership renewal request');
          $sMessage = $this->t('Your @str has been registered.<BR>It will be effective after reception of your payment.', ['@str' => $str]);
          break;
        default:
          $sMessage = $this->t('Renew membership: Unexpected case.');
      }
      Drupal::messenger()->addMessage($sMessage);
    }

    $this->deleteStore();

  }

  public function generateName($firstname, $lastname) {

    $database = \Drupal::database();
    $query    = $database->select('users_field_data', 'us');
    $query->fields('us', ['name']);
    $query->condition('name', $firstname . '%', 'like');
    $results = $query->execute()->fetchCol();
    for ($i = 0; $i <= strlen($lastname); $i++) {
      $temp = $firstname . strtoupper(substr($lastname, 0, $i));
      $key  = array_search($temp, $results);
      if ($key === FALSE) {
        break;
      }
    }
    return $temp;

  }

  /**
   * Helper method that removes all the keys from the store collection used
   * for the multistep form.
   */
  protected function deleteStore() {
    $keys = [
      'firstTime',
      'ap_id1',
      'lastname1',
      'firstname1',
      'email1',
      'cellphone1',
      'seliste1',
      'name1',
      'ap_id2',
      'lastname2',
      'firstname2',
      'email2',
      'cellphone2',
      'seliste2',
      'name2',
      'lastname',
      'firstname',
      'email',
      'am_id',
      'designation',
      'addresssupplement',
      'street',
      'postalcode',
      'city',
      'telephone',
      'status',
      'contracts',
      'payment',
    ];
    foreach ($keys as $key) {
      $this->store->delete($key);
    }
  }

}
