<?php 

class Model_Newsmodel extends Orm\Model
{
    protected static $_table_name = 'news';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', 
        'description' => array(
            'data_type' => 'varchar'
        ),
        'id_usuario' => array(
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