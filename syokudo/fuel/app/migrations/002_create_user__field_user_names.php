<?php

namespace Fuel\Migrations;

class Create_user__field_user_names
{
	public function up()
	{
		\DBUtil::create_table('user__field_user_names', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'bundle' => array('constraint' => 128, 'type' => 'varchar'),
			'deleted' => array('type' => 'tinyint'),
			'entity_id' => array('constraint' => 11, 'type' => 'int'),
			'revision_id' => array('constraint' => 11, 'type' => 'int'),
			'langcode' => array('constraint' => 32, 'type' => 'varchar'),
			'delta' => array('constraint' => 11, 'type' => 'int'),
			'field_user_name_value' => array('constraint' => 255, 'type' => 'varchar'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('user__field_user_names');
	}
}