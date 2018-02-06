<?php 
namespace Fuel\Migrations;

class Usuarios
{
	function up()
	{
		\DBUtil::create_table('usuarios', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'name' => array('type' => 'varchar', 'constraint' => 100),
            'email' => array('type' => 'varchar', 'constraint' => 100),
            'password' => array('type' => 'varchar', 'constraint' => 100),
            'description' => array('type' => 'varchar', 'constraint' => 400),
            'birthday' => array('type' => 'varchar', 'constraint' => 100),
            'location' => array('type' => 'varchar', 'constraint' => 100),
            'x' => array('type' => 'varchar', 'constraint' => 100),
            'y' => array('type' => 'varchar', 'constraint' => 100),
            'id_roles' => array('type' => 'int', 'constraint' => 11),
        ), array('id'), true, 'InnoDB', 'utf8_general_ci',
            array(
                array(
                    'constraint' => 'claveAjenaUsuariosARoles',
                    'key' => 'id_roles',
                    'reference' => array(
                        'table' => 'roles',
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
       \DBUtil::drop_table('usuarios');
    }
}



 ?>