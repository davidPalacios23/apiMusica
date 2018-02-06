<?php 
namespace Fuel\Migrations;

class Content
{
    function up()
    {
        \DBUtil::create_table('content', array(
            'id_list' => array('type' => 'int', 'constraint' => 11),
            'id_song' => array('type' => 'int', 'constraint' => 11)
        ), array('id_list','id_song'), true, 'InnoDB', 'utf8_general_ci',
            array(
                array(
                    'constraint' => 'claveAjenaContentALists',
                    'key' => 'id_list',
                    'reference' => array(
                        'table' => 'lists',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'RESTRICT'
                ),array(
                    'constraint' => 'claveAjenaContentASongs',
                    'key' => 'id_song',
                    'reference' => array(
                        'table' => 'songs',
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
       \DBUtil::drop_table('content');
    }
}



 ?>