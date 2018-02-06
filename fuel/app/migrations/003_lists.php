<?php 

namespace Fuel\Migrations;

class Lists
{

    function up()
    {
        \DBUtil::create_table('lists', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'title' => array('type' => 'varchar', 'constraint' => 100),
            'id_usuario' => array('type' => 'int', 'constraint' => 11),
        ), array('id'), true, 'InnoDB', 'utf8_general_ci',
            array(
                array(
                    'constraint' => 'claveAjenaListasAUsuarios',
                    'key' => 'id_usuario',
                    'reference' => array(
                        'table' => 'usuarios',
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
       \DBUtil::drop_table('lists');
    }
}