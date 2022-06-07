<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "blog_payment".
 *
 * @property int $id
 * @property string $type
 * @property int $price
 * @property int $days
 * @property string $name
 * @property string $text
 */
class BlogPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'price', 'name', 'text'], 'required'],
            [['id', 'price'], 'integer'],
            [['text'], 'string'],
            [['type'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип услуги',
            'price' => 'Стоимость в день',
            'name' => 'Название',
            'text' => 'Описание',
        ];
    }
}
