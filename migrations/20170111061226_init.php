<?php

use Phinx\Migration\AbstractMigration;

class Init extends AbstractMigration
{
    public function change()
    {
		$this->table('bors_thumb_votes', ['id' => false, 'primary_key' => 'id'])
			->addColumn('id', 'integer', ['signed' => false, 'identity' => true, 'limit' => 10])
			->addColumn('user_id', 'integer', ['signed' => false, 'length'=>10, 'null'=>true])
			->addColumn('target_user_id', 'integer', ['signed' => false, 'length'=>10, 'null'=>true])
			->addColumn('target_class_name', 'string', ['null'=>true])
			->addColumn('target_class_id', 'integer', ['signed' => false, 'length'=>10, 'null'=>true])
			->addColumn('target_object_id', 'string', ['null'=>true])
			->addColumn('score', 'integer')
			->addColumn('create_time', 'integer')

//			->addIndex('targets_count')
//			->addIndex('keyword')
//			->addIndex('modify_time')

			->create();
    }
}
