<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shop_images".
 *
 * @property int $id
 * @property int $user_id
 * @property string $image
 * @property string $url
 */
class ShopImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','shop_id'], 'required'],
            [['user_id','shop_id'], 'integer'],
            [['image', 'url'], 'string', 'max' => 200],
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
            'image' => 'Image',
            'url' => 'Url',
        ];
    }
}
