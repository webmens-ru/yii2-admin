<?php

class m221116_112600_update_json_to_text extends \yii\db\Migration
{
    public function up()
    {
        $this->alterColumn('admin_filter_field_setting', 'value', $this->text()->null());
        $this->alterColumn('admin_crm_user', 'LAST_ACTIVITY_DATE', $this->text());
        $this->alterColumn('admin_crm_user', 'TIMESTAMP_X', $this->text());
        $this->alterColumn('admin_crm_user', 'UF_DEPARTMENT', $this->text());
        $this->alterColumn('admin_form', 'action', $this->text()->null());
        $this->alterColumn('admin_form', 'params', $this->text());
        $this->alterColumn('admin_form', 'buttons', $this->text());
        $this->alterColumn('admin_form_fields', 'fieldParams', $this->text());
        $this->alterColumn('admin_filter_field', 'options', $this->text()->null());
    }

    public function down()
    {
    }
}
