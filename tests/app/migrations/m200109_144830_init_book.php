<?php

use yii\db\Migration;

/**
 * Class m200109_144830_init_book
 */
class m200109_144830_init_book extends Migration
{

    /**
     * @var string
     */
    public $table = '{{%book}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
