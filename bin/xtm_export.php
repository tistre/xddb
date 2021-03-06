<?php

namespace TopicCardsUi\Bin;

use \TopicCards\Interfaces\TopicMapInterface;
use \TopicCards\Utils\XtmExport;
use \Ulrichsg\Getopt\Getopt;
use \Ulrichsg\Getopt\Option;

require_once dirname(__DIR__) . '/include/init.php';

/** @var TopicMapInterface $topicmap */


function addObject($id)
{
    global $getopt;
    
    if ($getopt[ 'topics' ])
    {
        addTopic($id);
    }
    elseif ($getopt[ 'associations' ])
    {
        addAssociation($id);
    }
}


function addTopic($topic_id)
{
    global $topicmap;
    global $getopt;
    global $objects;
    
    $topic = $topicmap->newTopic();

    // Allow specifying topic ID or subject identifier
    
    $ok = $topic->load($topic_id);

    if ($ok < 0)
    {
        $topic_id = $topicmap->getTopicIdBySubject($topic_id);
        
        if (strlen($topic_id) > 0)
        {
            $ok = $topic->load($topic_id);
        }
    }
    
    if ($ok < 0)
    {
        return;
    }

    $objects[ ] = $topic;
    
    if ($getopt[ 'with_associations' ])
    {
        foreach ($topicmap->getAssociationIds([ 'role_player_id' => $topic_id ]) as $association_id)
        {
            addAssociation($association_id);
        }
    }    
}


function addAssociation($association_id)
{
    global $topicmap;
    global $getopt;
    global $objects;
    
    $association = $topicmap->newAssociation();
    $reifier = $topicmap->newTopic();
    
    $ok = $association->load($association_id);

    if ($ok < 0)
        return;
        
    $reifier_id = $association->getReifierId();
 
    if ($getopt[ 'with_reifiers' ] && (strlen($reifier_id) > 0))
    {
        $ok = $reifier->load($reifier_id);

        if ($ok >= 0)
            $objects[ ] = $reifier;
    }
    
    $objects[ ] = $association;
}


$getopt = new Getopt(
[
    new Option(null, 'topics'),
    new Option(null, 'associations'),
    new Option(null, 'with_associations'),
    new Option(null, 'with_reifiers'),
    new Option(null, 'config', Getopt::REQUIRED_ARGUMENT),
    new Option('h', 'help')
]);

$getopt->parse();

if ($getopt[ 'help' ])
{
    $getopt->setBanner("\nTopicBank XTM export\n\n");
    
    echo $getopt->getHelpText();
    exit;
}

$objects = [ ];

if ($getopt->getOperand(0) === '-')
{
    while (! feof(STDIN))
    {
        $id = trim(fgets(STDIN));
        
        if ($id === '')
            continue;
            
        addObject($id);
    }
}
else
{
    foreach ($getopt->getOperands() as $id)
    {
        addObject($id);
    }
}

$exporter = new XtmExport();

echo $exporter->exportObjects($objects);

