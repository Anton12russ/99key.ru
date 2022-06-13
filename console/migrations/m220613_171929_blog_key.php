<?php

use yii\db\Migration;

/**
 * Class m220613_171929_key_blog
 */
class m220613_171929_blog_key extends Migration
{
   public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%blog_key}}', [
            'id' => $this->primaryKey(),
			'blog_id' => $this->integer()->notNull(),
			'key' => $this->string()->notNull(),
			'date' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%blog_key}}');
    }
}
