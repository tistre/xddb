<?php

namespace TopicBank\Interfaces;


interface iAssociation extends iPersistent, iReified, iScoped, iTyped
{
    const EVENT_SAVING = 'association_saving';
    const EVENT_DELETING = 'association_deleting';
    const EVENT_INDEXING = 'association_indexing';
    
    public function getRoles(array $filters = [ ]);
    public function setRoles(array $roles);
    public function getFirstRole(array $filters = [ ]);
}