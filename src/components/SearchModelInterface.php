<?php

namespace rootlocal\crud\components;

use yii\data\ActiveDataProvider;

/**
 * Interface SearchModelInterface
 * @package rootlocal\crud\components
 */
interface SearchModelInterface
{

    /**
     * @param array|null $params
     * @return ActiveDataProvider
     */
    public function search($params);

}