<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "support".
 *
 * @property int $id
 * @property string $date_add
 * @property int $subject_id
 * @property int $user_id
 * @property int $admin
 * @property string $text
 */
class Support extends \yii\db\ActiveRecord
{
	public $subject;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'support';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_add', 'subject_id', 'user_id', 'text', 'subject'], 'required'],
            [['date_add'], 'safe'],
            [['subject_id', 'user_id', 'admin'], 'integer'],
            [['text', 'subject'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_add' => 'Date Add',
            'subject_id' => 'Subject ID',
            'user_id' => 'User ID',
            'text' => 'Текст',
			'subject' => 'Тема',
			'admin' => 'admin',
        ];
    }
}
