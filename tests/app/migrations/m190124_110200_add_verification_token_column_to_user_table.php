<?php

use \yii\db\Migration;

/**
 * Class m190124_110200_add_verification_token_column_to_user_table
 */
class m190124_110200_add_verification_token_column_to_user_table extends Migration
{
    /** @var string */
    protected $table = '{{%user}}';

    /**
     * @return bool|void
     */
    public function up()
    {
        $this->addColumn($this->table, 'verification_token', $this->string()->defaultValue(null));
    }

    /**
     * @return bool|void
     */
    public function down()
    {
        $this->dropColumn($this->table, 'verification_token');
    }
}
