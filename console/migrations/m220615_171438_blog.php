<?php

use yii\db\Migration;

/**
 * Class m220615_171438_blog
 */
class m220615_171438_blog extends Migration
{
    /**
     * {@inheritdoc}
     */


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('blog', 'express', $this->integer(1));
    }

    public function down()
    {
        echo "m220615_171438_blog cannot be reverted.\n";

        return false;
    }
   
}
