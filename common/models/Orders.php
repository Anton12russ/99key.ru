<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property int $flag
 * @property int $colvo
 * @property string $email
 * @property string $phone
 * @property string $name
 * @property string $text
 * @property string $data
 * @property string $board_id
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'colvo'], 'required'],
            [['user_id', 'flag', 'board_id', 'colvo'], 'integer'],
            [['text', 'data'], 'string'],
            [['email', 'phone'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'email' => 'Email',
			'colvo' => 'Количество товара (шт)',
            'phone' => 'Телефон',
            'name' => 'Имя',
            'text' => 'Примечание к заказу',
        ];
    }
}
