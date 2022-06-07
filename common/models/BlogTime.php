<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "blog_time".
 *
 * @property int $id
 * @property int $days
 * @property int $def
 * @property string $text
 * @property int $sort
 */
class BlogTime extends \yii\db\ActiveRecord
{
const STATUS_LIST = ['В резерве','Задействован'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['days', 'text', 'sort'], 'required'],
            [['id', 'def', 'days', 'sort'], 'integer'],
            [['text'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'days' => 'количество дней',
            'text' => 'Описание',
			'def' => 'По умолчанию',
			'defaults' => 'По умолчанию',
            'sort' => 'Сортировка',
        ];
    }
	
	
	
	public function getDefaults() {
    $arrey = [['В резерве'],['Задействован']];
	return $arrey[$this->def][0];	
	}
}
