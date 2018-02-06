<?php 

class Model_Usersmodel extends Orm\Model
{
    protected static $_table_name = 'usuarios';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'nombre' => array(
            'data_type' => 'varchar'   
        ),
        'password' => array(
        	'data_type' => 'varchar'
        ),
        'email' => array(
            'data_type' => 'varchar'
        ),
        'drescription' => array(
            'data_type' => 'varchar'
        ),    
        'birthday' => array(
            'data_type' => 'varchar'
        ),
        'location' => array(
            'data_type' => 'varchar'  
        ),
        'x' => array(
            'data_type' => 'varchar'      
        ),
        'y' => array(
            'data_type' => 'varchar'
        ),
        'id_device' => array(
            'data_type' => 'int'  
        ),
        'id_rol' => array(
            'data_type' => 'int'
        )          
    );

//esto está bien
    protected static $_has_many = array(
    'lists' => array(
        'key_from' => 'id',
        'model_to' => 'Model_listsmodel',
        'key_to' => 'id_usuarios',
        'cascade_save' => true,
        'cascade_delete' => false,
    ),
    'news' => array(
        'key_from' => 'id',
        'model_to' => 'Model_newsmodel',
        'key_to' => 'id_usuarios',
        'cascade_save' => true,
        'cascade_delete' => false,
    )
);
    
    protected static $_belongs_to = array(
    'users' => array(
        'key_from' => 'id_roles',
        'model_to' => 'Model_rolesmodel',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => false,
    )
);
    
    protected static $_has_one = array(
    'privacity' => array(
        'key_from' => 'id',
        'model_to' => 'Model_privacitymodel',
        'key_to' => 'id_usuarios',
        'cascade_save' => true,
        'cascade_delete' => false,
    )
);
    protected static $_many_many = array(
        'follower' => array(
            'key_from' => 'id',
            'key_through_from' => 'id_follower',
            'table_through' => 'follow',
            'key_through_to' => 'id_follower',
            'model_to' => 'Model_usersmodel',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false
        ),
        'followed' => array(
            'key_from' => 'id',
            'key_through_from' => 'id_followed',
            'table_through' => 'follow',
            'key_through_to' => 'id_followed',
            'model_to' => 'Model_usersmodel',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false
        )
    );

}