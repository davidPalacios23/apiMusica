<?php 
namespace Fuel\Migrations;

class Follower_followed
{
    function up()
    {
        \DBUtil::create_table('follower_followed', array(
            'id_follower' => array('type' => 'int', 'constraint' => 11),
            'id_followed' => array('type' => 'int', 'constraint' => 11)
        ), array('id_follower','id_followed'), true, 'InnoDB', 'utf8_general_ci',
            array(
                array(
                    'constraint' => 'claveAjenaFollowerFollowedAFollower',
                    'key' => 'id_follower',
                    'reference' => array(
                        'table' => 'users',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'RESTRICT'
                ),array(
                    'constraint' => 'claveAjenaFollowerFollowedAFollowed',
                    'key' => 'id_followed',
                    'reference' => array(
                        'table' => 'users',
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
       \DBUtil::drop_table('follower_followed');
    }
}



 ?>