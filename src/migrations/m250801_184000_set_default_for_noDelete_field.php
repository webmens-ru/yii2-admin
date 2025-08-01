<?php

use yii\db\Migration;

/**
 * Class m240405_123456_set_default_for_noDelete_field
 */
class m250801_184000_set_default_for_noDelete_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('admin_synchronization_field', 'noDelete', $this->tinyInteger(1)->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('admin_synchronization_field', 'noDelete', $this->tinyInteger(1)->notNull());
    }
}