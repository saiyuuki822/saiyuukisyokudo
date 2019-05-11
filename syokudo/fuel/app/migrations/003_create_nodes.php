<?php

namespace Fuel\Migrations;

class Create_nodes
{
	public function up()
	{
		\DBUtil::create_table('nodes', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'nid' => array('constraint' => 11, 'type' => 'int'),
			'vid' => array('constraint' => 11, 'type' => 'int'),
			'type' => array('constraint' => 32, 'type' => 'varchar'),
			'uuid' => array('constraint' => 128, 'type' => 'varchar'),
			'langcode' => array('constraint' => 12, 'type' => 'varchar'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('nodes');
	}
}