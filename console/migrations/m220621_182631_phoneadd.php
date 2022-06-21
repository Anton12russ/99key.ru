<?php

use yii\db\Migration;

/**
 * Class m220621_182631_phoneadd
 */
class m220621_182631_phoneadd extends Migration
{
    /**
     * {@inheritdoc}
     */
   
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('user', 'phone', $this->string(20)->Null());
    }

    public function down()
    {
        echo "m220615_171438_blog cannot be reverted.\n";

        return false;
    }
}
