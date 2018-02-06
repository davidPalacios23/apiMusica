<?php 

class Model_Rolesmodel extends Orm\Model
{
    protected static $_table_name = 'roles';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'type' => array(
            'data_type' => 'varchar'
        )
    );
    

    protected static $_has_many = array(
        'lists' => array(
            'key_from' => 'id',
            'model_to' => 'Model_usersmodel',
            'key_to' => 'id_roles',
            'cascade_save' => true,
            'cascade_delete' => false,
        )
    );  

}