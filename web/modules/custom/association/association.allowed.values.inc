<?php

function association_member_country()
{
  $countries = Drupal::service('country_manager')->getList();

  $key = 'FR';
  $data = $countries[$key];
  unset($countries[$key]);
  $countries = array_merge(array($key=>$data),$countries);

  return $countries;
}

function association_member_status()
{
  $values = [
    0 => 'Ancien adhérent',
    1 => 'Adhésion non renouvelée',
    2 => 'Adhésion en attente',
    3 => 'Adhésion renouvelée',
    4 => 'Adhérent actif',
    5 => 'Adhérent internet',
  ];
  return $values;
}
