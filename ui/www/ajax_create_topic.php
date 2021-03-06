<?php

require_once dirname(dirname(__DIR__)) . '/include/www_init.php';

$topic = $topicmap->newTopic();

$topic->setId($topicmap->createId());

$name = $topic->newName();
$name->setType('http://schema.org/name');
$name->setValue(trim($_REQUEST[ 'name' ]));

if (! empty($_REQUEST[ 'type' ]))
{
    $types = $_REQUEST[ 'type' ];
    
    if (! is_array($types))
        $types = [ $types ];
        
    $topic->setTypeIds($types);
}

if (! empty($_REQUEST[ 'subject_identifier' ]))
{
    $topic->setSubjectIdentifiers([ $_REQUEST[ 'subject_identifier' ] ]);
}

$ok = $topic->save();

if (! isset($_SESSION[ 'choose_topic_history' ]))
    $_SESSION[ 'choose_topic_history' ] = [ ];

$what = $_REQUEST[ 'what' ];

if (! isset($_SESSION[ 'choose_topic_history' ][ $what ]))
    $_SESSION[ 'choose_topic_history' ][ $what ] = [ ];
    
$_SESSION[ 'choose_topic_history' ][ $what ][ ] = $topic->getId();

header('Content-type: application/json');

echo json_encode(array( 'id' => $topic->getId(), 'name' => $name->getValue() ));
