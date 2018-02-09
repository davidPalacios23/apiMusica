<?php 

class Model_Privacitymodel extends Orm\Model
{
    protected static $_table_name = 'privacity';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'profile' => array(
            'data_type' => 'varchar'   
        ),
        'friends' => array(
        	'data_type' => 'varchar'
        ),
        'lists' => array(
            'data_type' => 'varchar'
        ),
        'notifications' => array(
            'data_type' => 'varchar'
        ),    
        'id_user' => array(
            'data_type' => 'int'
        )          
    );

    protected static $_belongs_to = array(
    'users' => array(
        'key_from' => 'id_user',
        'model_to' => 'Model_usersmodel',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => false,
    )
);
}    