<?php 

class Model_Contentmodel extends Orm\Model
{
    protected static $_table_name = 'content';
    protected static $_primary_key = array('id_list','id_song');
    protected static $_properties = array(
        'id_list', // both validation & typing observers will ignore the PK
        'name' => array(
            'data_type' => 'int'   
        ),
        'id_song' => array(
        	'data_type' => 'int'
        )
    );

    

 
   
}






