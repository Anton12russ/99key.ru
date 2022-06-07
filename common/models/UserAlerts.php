<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_alerts".
 *
 * @property int $id
 * @property int $user_id
 * @property int $message
 * @property int $comment
 */
class UserAlerts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_alerts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'message', 'comment'], 'integer'],
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
            'message' => 'Оповещение о новых сообщениях в чате',
            'comment' => 'Оповещение о новых комментариях',
        ];
    }
}
