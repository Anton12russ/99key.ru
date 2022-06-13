<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "blog_key".
 *
 * @property int $id
 * @property int $blog_id
 * @property int $key
 * @property string $date
 */
class BlogKey extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_key';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['blog_id', 'key', 'date'], 'required'],
            [['blog_id'], 'integer'],
            [['date', 'key'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'blog_id' => 'Blog ID',
            'key' => 'Key',
            'date' => 'Date',
        ];
    }
}
