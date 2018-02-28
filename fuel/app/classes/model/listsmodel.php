<?php 

class Model_Listsmodel extends Orm\Model
{
    protected static $_table_name = 'lists';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'title' => array(
            'data_type' => 'varchar'
        ),
        'id_user' => array(
            'data_type' => 'int'
        )
        
    );
    //esto está bien
    protected static $_belongs_to = array(
    'users' => array(
        'key_from' => 'id_user',
        'model_to' => 'Model_usersmodel',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => false,
    )
);

   
protected static $_many_many = array(
    'songs' => array(
        'key_from' => 'id',
        'key_through_from' => 'id_list', // column 1 from the table in between, should match a posts.id
        'table_through' => 'lists_songs', // both models plural without prefix in alphabetical order
        'key_through_to' => 'id_song', // column 2 from the table in between, should match a users.id
        'model_to' => 'Model_songsmodel',
        'key_to' => 'id',
        'cascade_save' => false,
        'cascade_delete' => false,
    )
);
}