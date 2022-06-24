<?php

use yii\db\Migration;

/**
 * Class m220624_185721_mail_comment_author
 */
class m220624_185721_mail_comment_author extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('mail', [
            'name' => 'Оповещение автору о новом комментарии',
			'text' => '<p>Уважаемый, {username}, У Вас есть новый комментарий {title}.</p>'
        ]);
    }

    public function safeDown()
    {
        $this->delete('mail', [
            'name' => 'Оповещение автору о новом комментарии',
			'text' => '<p>Уважаемый, {username}, У Вас есть новый комментарий {title}.</p>'
        ]);
    }
}
