<?php 

class Model_Followmodel extends Orm\Model
{
    protected static $_table_name = 'follow';
    protected static $_primary_key = array('id_follower','id_followed');
    protected static $_properties = array(
        'id_follower', // both validation & typing observers will ignore the PK
        'name' => array(
            'data_type' => 'int'   
        ),
        'id_followed' => array(
        	'data_type' => 'int'
        )
    );

    

 
   
}