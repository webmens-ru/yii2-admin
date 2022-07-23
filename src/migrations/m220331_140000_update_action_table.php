<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%test}}`.
 */
class m220331_140000_update_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%admin_grid_action}}', 'title', 'label');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%admin_grid_action}}', 'label', 'title');
    }
}
