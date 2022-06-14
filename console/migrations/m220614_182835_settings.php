<?php

use yii\db\Migration;

/**
 * Class m220614_182835_settings
 */
class m220614_182835_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
public function safeUp()
    {
        $this->insert('settings', [
            'name' => 'express_add',
			'text' => 'Количество дней публикации для express объявлений',
            'value' => '14',
			'type' => 'v',
			'val_text' => '',
			'placeholder'=> '',
			'sort' => '10000'
        ]);
    }

    public function safeDown()
    {
        $this->delete('settings', [
            'name' => 'express_add',
			'text' => 'Количество дней публикации для express объявлений',
            'value' => '14',
			'type' => 'v',
			'val_text' => '',
			'placeholder'=> '',
			'sort' => '10000'
        ]);
    }

}
