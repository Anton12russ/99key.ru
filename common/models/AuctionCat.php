<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auction_cat".
 *
 * @property int $id
 * @property string $cat
 */
class AuctionCat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auction_cat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat'], 'required'],
            [['cat'], 'string', 'max' => 11],
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
        ];
    }
}
