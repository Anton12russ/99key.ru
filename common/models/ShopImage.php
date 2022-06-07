<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shop_image".
 *
 * @property int $id
 * @property int $shop_id
 * @property string $image
 */
class ShopImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'image'], 'required'],
            [['shop_id'], 'integer'],
            [['image'], 'string', 'max' => 111],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'image' => 'Image',
        ];
    }
}
