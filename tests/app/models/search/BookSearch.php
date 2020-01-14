<?php

namespace rootlocal\crud\test\app\models\search;

use rootlocal\crud\test\app\models\db\Book;
use rootlocal\crud\test\app\models\query\BookQuery;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use rootlocal\crud\components\SearchModelInterface;

/**
 * Class BookSearch
 *
 * @property BookQuery $query
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud\test\app\models
 */
class BookSearch extends Book implements SearchModelInterface
{

    /**
     * @var BookQuery
     */
    private $_query;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @return BookQuery
     */
    public function getQuery(): BookQuery
    {
        if ($this->_query === null) {
            $this->_query = self::find();
        }

        return $this->_query;
    }

    /**
     * @param BookQuery $query
     */
    public function setQuery(BookQuery $query): void
    {
        $this->_query = $query;
    }

    /**
     * {@inheritdoc}
     *
     * Creates data provider instance with search query applied
     * @param array $params The request GET parameter values.
     * @return ActiveDataProvider
     */
    public function search($params = [])
    {
        $query = $this->getQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }

}