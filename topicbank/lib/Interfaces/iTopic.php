<?php

namespace TopicBank\Interfaces;


interface iTopic extends iPersistent
{
    const REIFIES_NONE = 0;
    const REIFIES_NAME = 1;
    const REIFIES_OCCURRENCE = 2;
    const REIFIES_ASSOCIATION = 3;
    const REIFIES_ROLE = 4;
    
    const EVENT_SAVING = 'topic_saving';
    const EVENT_DELETING = 'topic_deleting';
    const EVENT_INDEXING = 'topic_indexing';
    
    public function getSubjectIdentifiers();
    public function setSubjectIdentifiers(array $strings);
    public function getSubjectLocators();
    public function setSubjectLocators(array $strings);
    public function getTypeIds();
    public function setTypeIds(array $topic_ids);
    public function getTypes();
    public function setTypes(array $topic_subjects);
    public function hasTypeId($topic_id);
    public function hasType($topic_subject);
    public function newName();
    public function getNames(array $filters = [ ]);
    public function getFirstName(array $filters = [ ]);
    public function setNames(array $names);
    public function getLabel();
    public function newOccurrence();
    public function getOccurrences(array $filters = [ ]);
    public function getFirstOccurrence(array $filters = [ ]);
    public function setOccurrences(array $occurrences);
    public function isReifier(&$reifies_what, &$reifies_id);
    public function getReifiedObject($reifies_what);
}