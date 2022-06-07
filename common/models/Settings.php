<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $name
 * @property string $text
 * @property string $value
 * @property string $type
 * @property string $val_text
 * @property string $placeholder
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'text', 'type'], 'required'],
            [['name', 'value', 'sort'], 'string', 'max' => 10000],
            [['text'], 'string', 'max' => 150],
			[['placeholder'], 'string', 'max' => 300],
			[['val_text'], 'string'],
			[['sort'], 'default', 'value'=> 10000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'name' => 'Опция',
            'text' => 'Название опиции',
            'value' => 'Значение',
			'type'  => 'Тип поля',
		    'val_text'  => 'Значения',
		    'sort'  => 'Сортировка',			
			'placeholder'  => 'Описание внутри текстового поля',
        ];
    }
}
