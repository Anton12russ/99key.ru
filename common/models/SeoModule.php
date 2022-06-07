<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "seo_module".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $h1
 * @property string $redirect
 * @property string $url
 * @property int $cod_redirect

 */
class SeoModule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seo_module';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['description', 'keywords'], 'string'],
            [['title', 'h1'], 'string', 'max' => 150],
            [['redirect', 'url'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Метатег TITLE',
            'description' => 'Метатег Description',
            'keywords' => 'Метатег Keywords',
            'h1' => 'Метатег H1',
            'redirect' => 'Страница перенаправления',
            'url' => 'Адрес станицы URL',
            'cod_redirect' => 'Код редиректа',
        ];
    }
}
