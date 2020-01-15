<?php

namespace rootlocal\crud\components;

use yii\data\ActiveDataProvider;

/**
 * SearchModelInterface
 * is an interface that should be implemented in classes of search models.
 *
 * examples:
 *
 * ```php
 * use rootlocal\crud\components\SearchModelInterface;
 *
 * class BookSearch extends Book implements SearchModelInterface
 * {
 *
 *      // ...
 *
 *      public function search($params = [])
 *      {
 *          // ...
 *          return $dataProvider;
 *      }
 * }
 * ```
 *
 * @see \rootlocal\crud\actions\IndexAction
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud\components
 */
interface SearchModelInterface
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params The request GET parameter values.
     * @return ActiveDataProvider ActiveDataProvider implements a data provider based on [[\yii\db\Query]] and [[\yii\db\ActiveQuery]].
     */
    public function search($params = []);

}