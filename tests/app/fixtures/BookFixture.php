<?php

namespace rootlocal\crud\test\app\fixtures;

use rootlocal\crud\test\app\models\db\Book;
use yii\test\ActiveFixture;

/**
 * Class BookFixture
 * @package rootlocal\crud\tests\fixtures
 */
class BookFixture extends ActiveFixture
{
    public $modelClass = Book::class;
}