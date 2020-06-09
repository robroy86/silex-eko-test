<?php

$metadata->mapField(array(
    'id' => true,
    'fieldName' => 'id',
    'type' => 'integer'
 ));
 
 $metadata->mapField(array(
    'fieldName' => 'username',
    'type' => 'string',
    'options' => array(
        'fixed' => true,
        'comment' => "User's login name"
    )
 ));
 
 $metadata->mapField(array(
    'fieldName' => 'login_count',
    'type' => 'integer',
    'nullable' => false,
    'options' => array(
        'unsigned' => true,
        'default' => 0
    )
 ));

 $metadata->mapField(array(
    'fieldName' => 'id',
    'type' => 'integer',
    'nullable' => false,
    'options' => array(
        'unsigned' => true
    )
));
  $metadata->mapField(array(
    'fieldName' => 'nazwa',
    'type' => '
  $metadata->mapField(array(
    'fieldName' => 'adres',
    'type' => '
  $metadata->mapField(array(
    'fieldName' => 'opis',
    'type' => '
  $metadata->mapField(array(
    'fieldName' => 'zdj1',
    'type' => '
  $metadata->mapField(array(
    'fieldName' => 'zdj2',
    'type' => '
  $metadata->mapField(array(
    'fieldName' => 'zdj3',
    'type' => '
  $metadata->mapField(array(
    'fieldName' => 'reviewed',
    'type' => '
  $metadata->mapField(array(
    'fieldName' => 'ip',
    'type' => '
  $metadata->mapField(array(
    'fieldName' => 'browser',
    'type' => '
  $metadata->mapField(array(
    'fieldName' => 'data',
    'type' => '