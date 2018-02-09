<?php 

namespace Fuel\Migrations;

class Privacity
{

    function up()
    {
        \DBUtil::create_table('privacity', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'profile' => array('type' => 'varchar', 'constraint' => 200),
            'friends' => array('type' => 'varchar', 'constraint' => 50),
            'lists' => array('type' => 'varchar', 'constraint' => 50),
            'notifications' => array('type' => 'varchar', 'constraint' => 50),
            'id_user' => array('type' => 'int', 'constraint' => 11),
        ), array('id'), true, 'InnoDB', 'utf8_general_ci',
            array(
                array(
                    'constraint' => 'claveAjenaPrivacityAUsers',
                    'key' => 'id_user',
                    'reference' => array(
                        'table' => 'user',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'RESTRICT'
                )
            )
        );
    }

    function down()
    {
       \DBUtil::drop_table('privacity');
    }
}