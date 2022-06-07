<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cat_services".
 *
 * @property int $id
 * @property string $cat
 * @property string $reg
 * @property int $price
 */
class CatServices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat', 'reg', 'price'], 'required'],
            [['price'], 'integer'],
            [['cat', 'reg'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat' => 'Категория',
            'reg' => 'Регион',
            'price' => 'Стоимость в день',
        ];
    }
}
