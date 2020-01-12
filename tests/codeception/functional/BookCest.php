<?php

namespace rootlocal\crud\tests\functional;

use rootlocal\crud\test\app\fixtures\BookFixture;
use rootlocal\crud\test\app\models\db\Book;
use rootlocal\crud\tests\FunctionalTester;
use Codeception\Util\HttpCode;

/**
 * Class BookCest
 * @package rootlocal\crud\tests\functional
 */
class BookCest
{

    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @return array
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @see \Codeception\Module\Yii2::_before()
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => BookFixture::class,
                'dataFile' => codecept_data_dir() . 'book.php'
            ],
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage(['site/index']);
    }

    /**
     * @param FunctionalTester $I
     */
    public function _after(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkIndexAction(FunctionalTester $I)
    {
        $I->see('My Application');
        $I->seeLink('Books');
        $I->click('Books');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('Index Books', 'h1');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkIndexAjaxAction(FunctionalTester $I)
    {
        $I->sendAjaxGetRequest(['book/index']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkIndexSortAction(FunctionalTester $I)
    {
        $I->amOnPage(['book/index']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('Index Books', 'h1');
        $I->click('a[data-sort=name]');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkViewAction(FunctionalTester $I)
    {
        $I->amOnPage(['book/index']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeLink('erewfwef');
        $I->click('erewfwef', '.item-view-book');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('View erewfwef', 'h1');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkViewLambdaAction(FunctionalTester $I)
    {
        $model = Book::findOne(['name' => 'erewfwef']);
        $I->amOnPage(['book/view-lambda', 'id' => $model->id]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('View erewfwef');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkViewAjaxAction(FunctionalTester $I)
    {
        $model = Book::find()->where(['name' => 'erewfwef'])->one();
        $I->sendAjaxGetRequest(['book/view', 'id' => $model->id]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkViewNotFoundAction(FunctionalTester $I)
    {
        $I->amOnPage(['book/view', 'id' => 1000000]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->see('The requested page does not exist');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkDeleteAction(FunctionalTester $I)
    {
        $model = Book::find()->where(['name' => 'erewfwef'])->one();
        $I->sendAjaxPostRequest(['book/delete', 'id' => $model->id]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeRecord(Book::class, array('name' => 'erewfwef'));
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkDeleteRedirectAction(FunctionalTester $I)
    {
        $model = Book::find()->where(['name' => 'erewfwef'])->one();
        $I->sendAjaxPostRequest(['book/delete', 'id' => $model->id, 'redirect' => '/site/index']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeRecord(Book::class, array('name' => 'erewfwef'));
        $I->see('This is the Index page', 'p');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkDeleteLambdaAction(FunctionalTester $I)
    {
        $model = Book::findOne(['name' => 'erewfwef']);
        $I->sendAjaxPostRequest(['book/delete-lambda', 'id' => $model->id]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeRecord(Book::class, array('name' => 'erewfwef'));
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkDeleteNotFoundAction(FunctionalTester $I)
    {
        $I->sendAjaxPostRequest(['book/delete', 'id' => 1000000]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->see('The requested page does not exist');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkCreateAction(FunctionalTester $I)
    {
        $I->amOnPage(['book/index']);
        $I->click('Create Book', '#create-book');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->fillField(['name' => 'Book[name]'], 'Miles');
        $I->selectOption(['name' => 'Book[status]'], 'Inactive');
        $I->click('Save');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('Successfully completed');
        $I->see('View Miles', 'h1');
        $I->see('Inactive', 'td');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkCreateAjaxAction(FunctionalTester $I)
    {
        // Test render ajax
        $I->sendAjaxGetRequest(['book/create']);
        $I->seeResponseCodeIs(HttpCode::OK);

        // Test send ajax data form
        $I->sendAjaxPostRequest([
            'book/create',
            'redirect' => 'index'
        ], ['Book[name]' => 'test_111']);

        $I->seeResponseCodeIs(HttpCode::OK);

        $model = Book::find()->where(['name' => 'test_111'])->one();
        $I->amOnPage(['book/view', 'id' => $model->id]);
        $I->see('View test_111');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkUpdateAction(FunctionalTester $I)
    {
        $I->amOnPage(['book/index']);
        $I->click('erewfwef', '.item-view-book');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('View erewfwef', 'h1');
        $I->see('Active', 'td');
        $I->click('Update', '.item-update-book');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('Update erewfwef', 'h1');
        $I->fillField(['name' => 'Book[name]'], 'erewfwef');
        $I->selectOption(['name' => 'Book[status]'], 'Inactive');
        $I->click('Save');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('Successfully completed');
        $I->see('View erewfwef', 'h1');
        $I->see('Inactive', 'td');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkUpdateAjaxAction(FunctionalTester $I)
    {
        $model = Book::find()->where(['name' => 'erewfwef'])->one();

        // Test render ajax
        $I->sendAjaxGetRequest(['book/update', 'id' => $model->id]);
        $I->seeResponseCodeIs(HttpCode::OK);

        // Test send ajax data form
        $I->sendAjaxPostRequest([
            'book/update',
            'id' => $model->id,
            'redirect' => 'index'
        ], ['Book[name]' => 'test_121']);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->amOnPage(['book/view', 'id' => $model->id]);
        $I->see('View test_121');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkUpdateLambdaAction(FunctionalTester $I)
    {
        $model = Book::findOne(['name' => 'erewfwef']);
        $I->sendAjaxPostRequest(['book/update-lambda', 'id' => $model->id], ['Book[name]' => 'test_121']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->amOnPage(['book/view', 'id' => $model->id]);
        $I->see('View test_121');
    }


    /**
     * @param FunctionalTester $I
     */
    public function checkUpdateNotFoundAction(FunctionalTester $I)
    {
        $I->amOnPage(['book/update', 'id' => 1000000]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->see('The requested page does not exist');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkValidateAction(FunctionalTester $I)
    {
        $model = Book::find()->where(['name' => 'erewfwef'])->one();

        // validate Update action
        $I->sendAjaxPostRequest([
            'book/validate',
            'id' => $model->id,
            'scenario' => $model::SCENARIO_UPDATE,
        ], ['Book[name]' => 'test_123']);
        $I->seeResponseCodeIs(HttpCode::OK);

        // validate create action
        $I->sendAjaxPostRequest([
            'book/validate',
        ], ['Book[name]' => 'test_444']);
        $I->seeResponseCodeIs(HttpCode::OK);

        // NOT_FOUND
        $I->sendAjaxPostRequest([
            'book/validate',
            'id' => 1000000,
            'scenario' => 'test',
        ], ['Book[name]' => 'test_555']);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->see('The requested page does not exist');

        // BAD_REQUEST
        $I->sendAjaxPostRequest(['book/validate'], []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->see('Invalid Request');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkValidateLambdaAction(FunctionalTester $I)
    {
        $model = Book::findOne(['name' => 'erewfwef']);
        // validate Update action
        $I->sendAjaxPostRequest([
            'book/validate-lambda',
            'id' => $model->id,
            'scenario' => $model::SCENARIO_UPDATE,
        ], ['Book[name]' => 'test_121']);
        $I->seeResponseCodeIs(HttpCode::OK);

        // validate Create action
        $I->sendAjaxPostRequest([
            'book/validate-lambda',
        ], ['Book[name]' => 'test_333']);
        $I->seeResponseCodeIs(HttpCode::OK);

        // NOT_FOUND
        $I->sendAjaxPostRequest([
            'book/validate-lambda',
            'id' => 1000000,
            'scenario' => 'test',
        ], ['Book[name]' => 'test_555']);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->see('The requested page does not exist');

        // BAD_REQUEST
        $I->sendAjaxPostRequest(['book/validate-lambda'], []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->see('Invalid Request');
    }
}