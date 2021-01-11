<?php

namespace TopicBank\Backends\Db;


trait RoleDbAdapter
{
    public function selectAll(array $filters)
    {
        $ok = $this->services->db_utils->connect();
        
        if ($ok < 0)
            return $ok;

        if (isset($filters[ 'association' ]))
        {
            $where = 'role_association = :association_id';
        }
        elseif (isset($filters[ 'reifier' ]))
        {
            $where = 'role_reifier = :reifier_id';
        }
        
        $prefix = $this->topicmap->getDbTablePrefix();
        
        $sql = $this->services->db->prepare(sprintf
        (
            'select * from %srole where ' . $where,
            $prefix
        ));

        if (isset($filters[ 'association' ]))
        {
            $sql->bindValue(':association_id', $filters[ 'association' ], \PDO::PARAM_STR);
        }
        elseif (isset($filters[ 'reifier' ]))
        {
            $sql->bindValue(':reifier_id', $filters[ 'reifier' ], \PDO::PARAM_STR);
        }
        
        $ok = $sql->execute();
        
        if ($ok === false)
            return -1;

        $result = [ ];
        
        foreach ($sql->fetchAll() as $row)
        {
            $row = $this->services->db_utils->stripColumnPrefix('role_', $row);
            $result[ ] = $row;
        }

        return $result;
    }


    public function insertAll($association_id, array $data)
    {
        $ok = $this->services->db_utils->connect();
        
        if ($ok < 0)
            return $ok;
        
        foreach ($data as $name_data)
        {
            $values = [ ];
        
            $name_data[ 'association' ] = $association_id;

            foreach ($name_data as $key => $value)
            {
                // PostgreSQL "serial" does not kick in if we provide an empty value
                
                if (($key === 'id') && (strlen($value) === 0))
                    continue;
                    
                $values[ ] =
                [
                    'column' => 'role_' . $key,
                    'value' => $value
                ];
            }
        
            $sql = $this->services->db_utils->prepareInsertSql
            (
                $this->topicmap->getDbTablePrefix() . 'role', 
                $values
            );
        
            $ok = $sql->execute();
        
            if ($ok === false)
                return -1;
        }
        
        return 1;
    }
    
    
    public function updateAll($association_id, array $data)
    {
        $ok = $this->services->db_utils->connect();
        
        if ($ok < 0)
            return $ok;

        $sql = $this->services->db_utils->prepareDeleteSql
        (
            $this->topicmap->getDbTablePrefix() . 'role', 
            [ [ 'column' => 'role_association', 'value' => $association_id ] ]
        );
    
        $ok = $sql->execute();
    
        if ($ok === false)
            return -1;
        
        return $this->insertAll($association_id, $data);
    }
}