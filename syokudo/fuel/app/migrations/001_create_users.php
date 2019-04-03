<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'uid' => array('constraint' => 11, 'type' => 'int'),
			'uuid' => array('constraint' => 128, 'type' => 'varchar'),
			'langcode' => array('constraint' => 12, 'type' => 'varchar'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}