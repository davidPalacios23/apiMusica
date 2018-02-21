<?php 
namespace Fuel\Migrations;

class Songs
{
	function up()
	{
		\DBUtil::create_table('songs', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'name' => array('type' => 'varchar', 'constraint' => 100),
            'url' => array('type' => 'varchar', 'constraint' => 100),
            'playsnumber' => array('type' => 'int', 'constraint' => 11, 'null' => true)
        ), array('id'));
	}

	function down()
    {
       \DBUtil::drop_table('songs');
    }
}



 ?>