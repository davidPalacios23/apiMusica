<?php 

class Model_songsmodel extends Orm\Model
{
    protected static $_table_name = 'songs';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'name' => array(
            'data_type' => 'varchar'   
        ),
        'url' => array(
        	'data_type' => 'varchar'
        )
    );

     // in a Model_Post which has and belongs to many Users
// = multiple posts per user and multiple users (authors) per post
protected static $_many_many = array(
    'lists' => array(
        'key_from' => 'id',
        'key_through_from' => 'id_list', // column 1 from the table in between, should match a posts.id
        'table_through' => 'content', // both models plural without prefix in alphabetical order
        'key_through_to' => 'id_song', // column 2 from the table in between, should match a users.id
        'model_to' => 'Model_songsmodel',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => false,
    )
);

}