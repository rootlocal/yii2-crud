<?php

namespace rootlocal\crud\test\app\models\query;

use rootlocal\crud\test\app\models\db\Book;
use yii\db\ActiveQuery;

/**
 * Class BookQuery
 * @package rootlocal\crud\test\app\models\query
 * @see \rootlocal\crud\test\app\models\db\Book
 */
class BookQuery extends ActiveQuery
{
    /**
     * @return BookQuery
     */
    public function active()
    {
        return $this->andWhere(['{{%book}}.status' => Book::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @return Book[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Book|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}