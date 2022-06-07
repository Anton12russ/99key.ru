<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "blog_image".
 *
 * @property int $id
 * @property int $blog_id
 * @property string $image
 */
class BlogImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['blog_id', 'image'], 'required'],
            [['blog_id'], 'integer'],
            [['image'], 'string', 'max' => 1111],
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
            'image' => 'Image',
        ];
    }
}
