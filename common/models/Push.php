<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "push".
 *
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property string $href
 * @property int $flag
 */
class Push extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'push';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'text', 'href', 'flag'], 'required'],
            [['user_id', 'flag'], 'integer'],
            [['text', 'href'], 'string', 'max' => 200],
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
            'text' => 'Text',
            'href' => 'Href',
            'flag' => 'Flag',
        ];
    }
}
