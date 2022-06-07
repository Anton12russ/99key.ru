<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "blog_coord".
 *
 * @property int $id
 * @property int $blog_id
 * @property string $coordlat
 * @property string $coordlon
 * @property string $text
 */
class BlogCoord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_coord';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['blog_id', 'coordlat', 'coordlon', 'text'], 'required'],
            [['blog_id'], 'integer'],
            [['coordlat', 'coordlon'], 'string', 'max' => 200],
			[['text'], 'string']
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
            'coordlat' => 'Coordlat',
            'coordlon' => 'Coordlon',
        ];
    }
}
