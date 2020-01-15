<?php

namespace rootlocal\crud\test\app\models\db;

use rootlocal\crud\test\app\models\query\BookQuery;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;
use yii\db\ActiveRecord;

/**
 * Class Book model
 * @package rootlocal\crud\test\app\models\db
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $url
 * @property array $statusItems
 */
class Book extends ActiveRecord
{
    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;
    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] =
            [
                'name',
                'status',
            ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'name',
            'status',
        ];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Url::to(['view', 'id' => $this->id]);
    }

    /**
     * @return BookQuery
     */
    public static function find()
    {
        return new BookQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function getStatusItems()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_DELETED => 'Deleted',
        ];
    }

    /**
     * @param $item int
     * @return string
     */
    public function getStatusItem($item)
    {
        $items = $this->getStatusItems();

        return array_key_exists($item, $items) ? $items[$item] : 'Incorrect value';
    }
}
