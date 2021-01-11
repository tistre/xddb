<?php

namespace TopicBank\Backends\Db;


trait TopicMapDbAdapter
{
    public function selectTopics(array $filters)
    {
        if (! isset($filters[ 'limit' ]))
            $filters[ 'limit' ] = 500;

        if (isset($filters[ 'type' ]))
            $filters[ 'type_id' ] = $this->getTopicIdBySubject($filters[ 'type' ]);
            
        $ok = $this->services->db_utils->connect();
        
        if ($ok < 0)
            return $ok;
        
        $prefix = $this->getDbTablePrefix();
        $limit_clause = ($filters[ 'limit' ] > 0 ? sprintf(' limit %d', $filters[ 'limit' ]) : '');
        
        if ((! empty($filters[ 'name_like' ])) && (! empty($filters[ 'type' ])))
        {
            $sql = $this->services->db->prepare(sprintf
            (
                'select distinct name_topic as topic_id from %sname, %stype'
                . ' where lower(name_value) like lower(:name_value)'
                . ' and type_type = :type_type'
                . ' and type_topic = name_topic%s', 
                $prefix, $prefix, $limit_clause
            ));

            $sql->bindValue(':name_value', $filters[ 'name_like' ], \PDO::PARAM_STR);
            $sql->bindValue(':type_type', $filters[ 'type' ], \PDO::PARAM_STR);
        }
        elseif (! empty($filters[ 'type_id' ]))
        {
            $sql = $this->services->db->prepare(sprintf
            (
                'select distinct type_topic as topic_id from %stype'
                . ' where type_type = :type_type%s', 
                $prefix, $limit_clause
            ));

            $sql->bindValue(':type_type', $filters[ 'type_id' ], \PDO::PARAM_STR);
        }
        elseif (! empty($filters[ 'name_like' ]))
        {
            $sql = $this->services->db->prepare(sprintf
            (
                'select distinct name_topic as topic_id from %sname'
                . ' where lower(name_value) like lower(:name_value)%s', 
                $prefix, $limit_clause
            ));

            $sql->bindValue(':name_value', $filters[ 'name_like' ], \PDO::PARAM_STR);
        }
        else
        {
            $sql = $this->services->db->prepare(sprintf
            (
                'select topic_id from %stopic%s', 
                $prefix, $limit_clause
            ));
        }
        
        $ok = $sql->execute();
        
        if ($ok === false)
            return -1;

        $result = [ ];
        
        foreach ($sql->fetchAll() as $row)
            $result[ ] = $row[ 'topic_id' ];

        return $result;
    }
    
    
    public function selectTopicBySubject($uri)
    {
        $ok = $this->services->db_utils->connect();
        
        if ($ok < 0)
            return false;
        
        $sql = $this->services->db->prepare(sprintf
        (
            'select subject_topic as topic_id from %ssubject'
            . ' where subject_value = :subject_value'
            // XXX "limit" MySQL specific? Does PDO have a better way?
            . ' limit 1', 
            $this->getDbTablePrefix()
        ));

        $sql->bindValue(':subject_value', $uri, \PDO::PARAM_STR);

        $ok = $sql->execute();
        
        if ($ok === false)
            return false;

        foreach ($sql->fetchAll() as $row)
            return $row[ 'topic_id' ];

        return false;
    }
    
    
    public function selectTopicSubjectIdentifier($topic_id)
    {
        return $this->selectTopicSubject($topic_id, 0);
    }
    
    
    public function selectTopicSubjectLocator($topic_id)
    {
        return $this->selectTopicSubject($topic_id, 1);
    }
    
    
    protected function selectTopicSubject($topic_id, $islocator)
    {
        $ok = $this->services->db_utils->connect();
        
        if ($ok < 0)
            return false;
        
        $prefix = $this->getDbTablePrefix();
        
        $sql = $this->services->db->prepare(sprintf
        (
            'select subject_value from %ssubject'
            . ' where subject_topic = :subject_topic'
            . ' and subject_islocator = :subject_islocator'
            . ' order by subject_id', 
            $prefix
        ));
        
        $sql->bindValue(':subject_topic', $topic_id, \PDO::PARAM_STR);
        $sql->bindValue(':subject_islocator', $islocator, \PDO::PARAM_INT);
        
        $ok = $sql->execute();
        
        if ($ok === false)
            return false;

        $row = $sql->fetch();
        
        if ($row === false)
            return false;
        
        return $row[ 'subject_value' ];
    }
    
    
    public function selectAssociations(array $filters)
    {
        if (isset($filters[ 'type' ]))
            $filters[ 'type_id' ] = $this->getTopicIdBySubject($filters[ 'type' ]);

        if (isset($filters[ 'role_player' ]))
            $filters[ 'role_player_id' ] = $this->getTopicIdBySubject($filters[ 'role_player' ]);
        
        if (isset($filters[ 'role_type' ]))
            $filters[ 'role_type_id' ] = $this->getTopicIdBySubject($filters[ 'role_type' ]);
        
        if (! isset($filters[ 'limit' ]))
            $filters[ 'limit' ] = 500;
            
        $ok = $this->services->db_utils->connect();
        
        if ($ok < 0)
            return $ok;

        $prefix = $this->getDbTablePrefix();

        $sql_str = sprintf('select association_id from %sassociation', $prefix);
        
        $where = [ ];
        $bind = [ ];

        if (! empty($filters[ 'type_id' ]))
        {
            $where[ ] = 'association_type = :association_type';
            
            $bind[ ] = 
            [
                'bind_param' => ':association_type', 
                'value' => $filters[ 'type_id' ] 
            ];
        }

        if (! empty($filters[ 'role_player_id' ]))
        {
            $where[ ] = sprintf
            (
                'exists (select role_id from %srole where role_player = :role_player'
                . ' and role_association = association_id)',
                $prefix
            );
            
            $bind[ ] = 
            [
                'bind_param' => ':role_player', 
                'value' => $filters[ 'role_player_id' ]
            ];
        }

        if (! empty($filters[ 'role_type_id' ]))
        {
            $where[ ] = sprintf
            (
                'exists (select role_id from %srole where role_type = :role_type'
                . ' and role_association = association_id)',
                $prefix
            );
            
            $bind[ ] = 
            [
                'bind_param' => ':role_type', 
                'value' => $filters[ 'role_type_id' ]
            ];
        }

        if (count($where) > 0)
            $sql_str .= ' where ' . implode(' and ', $where);
            
        if ($filters[ 'limit' ] > 0)
            $sql_str .= sprintf(' limit %d', $filters[ 'limit' ]);

        $sql = $this->services->db->prepare($sql_str);
        
        $this->services->db_utils->bindValues($sql, $bind);
        
        $ok = $sql->execute();
        
        if ($ok === false)
            return -1;

        $result = [ ];
        
        foreach ($sql->fetchAll() as $row)
            $result[ ] = $row[ 'association_id' ];

        return $result;
    }
    
    
    public function selectTopicTypes(array $filters)
    {
        return $this->selectWhat('type', 'type_type', $filters);
    }
    
    
    public function selectNameTypes(array $filters)
    {
        return $this->selectWhat('name', 'name_type', $filters);
    }
    

    public function selectNameScopes(array $filters)
    {
        // XXX selects all scopes, not just name scopes
        return $this->selectWhat('scope', 'scope_scope', $filters);
    }
    
    
    public function selectOccurrenceTypes(array $filters)
    {
        return $this->selectWhat('occurrence', 'occurrence_type', $filters);
    }


    public function selectOccurrenceDatatypes(array $filters)
    {
        return $this->selectWhat('occurrence', 'occurrence_datatype', $filters);
    }

    
    public function selectOccurrenceScopes(array $filters)
    {
        // XXX selects all scopes, not just occurrence scopes
        return $this->selectWhat('scope', 'scope_scope', $filters);
    }
    
    
    public function selectAssociationTypes(array $filters)
    {
        return $this->selectWhat('association', 'association_type', $filters);
    }
    
    
    public function selectAssociationScopes(array $filters)
    {
        // XXX selects all scopes, not just association scopes
        return $this->selectWhat('scope', 'scope_scope', $filters);
    }
    
    
    public function selectRoleTypes(array $filters)
    {
        return $this->selectWhat('role', 'role_type', $filters);
    }
    
    
    public function selectRolePlayers(array $filters)
    {
        return $this->selectWhat('role', 'role_player', $filters);
    }
    
    
    protected function selectWhat($table, $column, array $filters)
    {
        if (! isset($filters[ 'get_mode' ]))
            $filters[ 'get_mode' ] = 'all';

        if (! isset($filters[ 'limit' ]))
            $filters[ 'limit' ] = 500;

        $ok = $this->services->db_utils->connect();
        
        if ($ok < 0)
            return $ok;

        $sort_column = '';
        
        if ($filters[ 'get_mode' ] === 'recent')
        {
            // XXX not so nice: associations ordered by updated, others by created...
            $table_sortcolumn =
            [
                'association' => 'association_updated',
                'name' => 'name_id',
                'occurrence' => 'occurrence_id',
                'role' => 'role_id',
                'scope' => 'scope_id',
                'type' => 'type_id'
            ];
            
            if (isset($table_sortcolumn[ $table ]))
                $sort_column = $table_sortcolumn[ $table ];
        }
        
        $prefix = $this->getDbTablePrefix();

        if ($sort_column === '')
        {
            $sql_stmt = sprintf
            (
                'select distinct %s from %s%s limit %d',
                $column,
                $prefix,
                $table,
                $filters[ 'limit' ]
            );
        }
        else
        {
            $sql_stmt = sprintf
            (
                'select distinct a.%s from (select %s, %s from %s%s order by %s desc) a limit %d',
                $column,
                $column,
                $sort_column,
                $prefix,
                $table,
                $sort_column,
                $filters[ 'limit' ]
            );
        }

        $sql = $this->services->db->prepare($sql_stmt);

        $ok = $sql->execute();
        
        if ($ok === false)
            return -1;

        $result = [ ];

        foreach ($sql->fetchAll() as $row)
            $result[ ] = $row[ $column ];

        return $result;
    }
}