<?php

/* eko_ przystanki  
   comes in handy: https://www.doctrine-project.org/projects/doctrine-dbal/en/2.10/reference/types.html
*/

 $metadata->mapField(array(
    'id' => true,
    'fieldName' => 'id',
    'type' => 'integer',
    'nullable' => false,
    'options' => array(
        'unsigned' => true
    )
));

$metadata->mapField(array(
    'fieldName' => 'nazwa',
    'type' => 'string',
    'nullable' => false,
    'options' => array(
      'default' => ''
    )
));

$metadata->mapField(array(
    'fieldName' => 'adres',
    'type' => 'string',
    'nullable' => false,
    'options' => array(
      'default' => ''
    )
));

$metadata->mapField(array(
    'fieldName' => 'opis',
    'type' => 'text',
    'nullable' => false,
    'options' => array(
      'default' => ''
    )
));

$metadata->mapField(array(
    'fieldName' => 'zdj1',
    'type' => 'string',
    'nullable' => false,
    'options' => array(
      'default' => ''
    )
));

$metadata->mapField(array(
    'fieldName' => 'zdj2',
    'type' => 'string',
    'nullable' => false,
    'options' => array(
      'default' => ''
    )
));

$metadata->mapField(array(
    'fieldName' => 'zdj3',
    'type' => 'string',
    'nullable' => false,
    'options' => array(
      'default' => ''
    )
));

$metadata->mapField(array(
    'fieldName' => 'reviewed',
    'type' => 'boolean'
    'nullable' => false,
    'options' => array(
      'default' => 0
    )
));

$metadata->mapField(array(
    'fieldName' => 'ip',
    'type' => 'string',
    'nullable' => false,
    'options' => array(
      'default' => ''
    )
));

$metadata->mapField(array(
    'fieldName' => 'browser',
    'type' => 'string',
    'nullable' => false,
    'options' => array(
      'default' => ''
    )
));

$metadata->mapField(array(
    'fieldName' => 'data',
    'type' => 'datetime',
    'nullable' => false,
    'options' => array(
      'default' => 0
    )
));
 