<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dispute_mess".
 *
 * @property int $id
 * @property int $user_id
 * @property int $bayer_id
 * @property int $dispute_id
 * @property string $text
 * @property string $flag_bayer
 * @property string $flag_shop
 * @property string $flag_admin 
 * @property string $date
 */
class DisputeMess extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dispute_mess';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'bayer_id', 'dispute_id', 'text'], 'required'],
            [['user_id', 'bayer_id', 'dispute_id', 'flag_bayer', 'flag_shop', 'flag_admin'], 'integer'],
            [['text', 'date'], 'string'],
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
            'bayer_id' => 'Bayer ID',
            'dispute_id' => 'Dispute ID',
            'text' => 'Text',

        ];
    }
}
