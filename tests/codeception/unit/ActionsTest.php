<?php

namespace rootlocal\crud\tests\unit;

use Codeception\Test\Unit;
use rootlocal\crud\actions\CreateAction;
use rootlocal\crud\actions\DeleteAction;
use rootlocal\crud\actions\IndexAction;
use rootlocal\crud\actions\UpdateAction;
use rootlocal\crud\actions\ValidateAction;
use rootlocal\crud\actions\ViewAction;
use rootlocal\crud\components\SearchModelInterface;
use rootlocal\crud\test\app\models\search\BookSearch;
use yii\base\Module;
use yii\web\Controller;
use rootlocal\crud\test\app\fixtures\BookFixture;
use rootlocal\crud\test\app\models\db\Book;
use yii\db\ActiveRecord;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\base\InvalidConfigException;

/**
 * Class ActionsTest
 * @package rootlocal\crud\tests\unit
 */
class ActionsTest extends Unit
{
    /**
     * @var \rootlocal\crud\tests\UnitTester
     */
    protected $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => BookFixture::class,
                'dataFile' => codecept_data_dir() . 'book.php'
            ]
        ]);
    }

    public function _after()
    {
    }

    public function testIndexAction()
    {
        // No model
        $this->tester->expectThrowable(ErrorException::class, function () {

            $action = new IndexAction('index', new Controller('book', new Module('test')));
            $action->getModel();
        });

        // $searchModel not instanceof SearchModelInterface
        $this->tester->expectThrowable(ErrorException::class, function () {

            $action = new IndexAction('index', new Controller('book', new Module('test')), [
                'searchModel' => Book::class,
            ]);
            $action->getModel();
        });

        // Model is string
        $action = new IndexAction('index', new Controller('book', new Module('test')), [
            'searchModel' => BookSearch::class,
        ]);
        expect($action->getModel())->isInstanceOf(SearchModelInterface::class);

        // Model is object ActiveRecord
        $action = new IndexAction('index', new Controller('book', new Module('test')), [
            'searchModel' => new BookSearch()
        ]);
        expect($action->getModel())->isInstanceOf(SearchModelInterface::class);

        // Model is Lambda
        $action = new IndexAction('index', new Controller('book', new Module('test')), [
            'searchModel' => function () {

                $model = new BookSearch();
                $model->query = $model::find()->active();

                return $model;
            },
        ]);
        expect($action->getModel())->isInstanceOf(SearchModelInterface::class);

        // Set DataProvider
        $action = new IndexAction('index', new Controller('book', new Module('test')), [
            'searchModel' => BookSearch::class,
            'dataProvider' => function ($model, $queryParams) {

                /**
                 * @var SearchModelInterface $model
                 */
                return $model->search($queryParams);
            },
        ]);
        expect($action->getDataProvider())->isInstanceOf(ActiveDataProvider::class);


        // Set DataProvider Exception
        $this->tester->expectThrowable(InvalidConfigException::class, function () {
            $action = new IndexAction('index', new Controller('book', new Module('test')), [
                'searchModel' => BookSearch::class,
                'dataProvider' => function ($model, $queryParams) {

                    /**
                     * @var SearchModelInterface $model
                     */
                    return '';
                },
            ]);

            $action->getDataProvider();
        });

    }

    public function testCreateAction()
    {
        // No model
        $this->tester->expectThrowable(ErrorException::class, function () {

            $action = new CreateAction('create', new Controller('book', new Module('test')), [
                'scenario' => 'test'
            ]);
            $action->getModel();
        });

        // Model is string
        $action = new CreateAction('create', new Controller('book', new Module('test')), [
            'model' => Book::class,
            'scenario' => Book::SCENARIO_CREATE,
        ]);
        expect($action->getModel())->isInstanceOf(ActiveRecord::class);

        // Model is object ActiveRecord
        $action = new CreateAction('create', new Controller('book', new Module('test')), [
            'model' => new Book(['scenario' => Book::SCENARIO_CREATE])
        ]);
        expect($action->getModel())->isInstanceOf(ActiveRecord::class);

        // Model is Lambda
        $action = new CreateAction('create', new Controller('book', new Module('test')), [
            'model' => function ($scenario) {

                /**
                 * @var string $scenario
                 */

                return new Book(['scenario' => $scenario]);
            },
            'scenario' => Book::SCENARIO_CREATE
        ]);
        expect($action->getModel())->isInstanceOf(ActiveRecord::class);
    }

    public function testViewAction()
    {
        // No model
        $this->tester->expectThrowable(ErrorException::class, function () {
            $action = new ViewAction('view', new Controller('book', new Module('test')));
            $action->getModel();
        });
    }

    public function testDeleteAction()
    {
        // No model
        $this->tester->expectThrowable(ErrorException::class, function () {
            $action = new DeleteAction('delete', new Controller('book', new Module('test')));
            $action->getModel();
        });
    }

    public function testUpdateAction()
    {
        // No model
        $this->tester->expectThrowable(ErrorException::class, function () {
            $action = new UpdateAction('update', new Controller('book', new Module('test')));
            $action->getModel();
        });
    }

    public function testValidateAction()
    {
        // No model
        $this->tester->expectThrowable(ErrorException::class, function () {
            $action = new ValidateAction('validate', new Controller('book', new Module('test')));
            $action->getModel();
        });

        // No scenario
        $action = new ValidateAction('validate', new Controller('book', new Module('test')), [
            'model' => Book::class
        ]);
        expect($action->getScenario())->equals(ActiveRecord::SCENARIO_DEFAULT);
    }

}