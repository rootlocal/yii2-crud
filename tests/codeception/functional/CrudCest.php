<?php

namespace rootlocal\crud\tests\functional;

use rootlocal\crud\test\app\fixtures\BookFixture;
use rootlocal\crud\test\app\models\db\Book;
use rootlocal\crud\tests\FunctionalTester;
use Codeception\Util\HttpCode;

/**
 * Class CrudCest
 * @package rootlocal\crud\tests\functional
 */
class CrudCest
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
        $I->amOnPage(['crud/index']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('Index Books', 'h1');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkIndexAjaxAction(FunctionalTester $I)
    {
        $I->sendAjaxGetRequest(['crud/index']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkViewAction(FunctionalTester $I)
    {
        $I->amOnPage(['crud/index']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeLink('erewfwef');
        $I->click('erewfwef', '.item-view-book');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('View erewfwef', 'h1');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkViewAjaxAction(FunctionalTester $I)
    {
        $model = Book::find()->where(['name' => 'erewfwef'])->one();
        $I->sendAjaxGetRequest(['crud/view', 'id' => $model->id]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkViewNotFoundAction(FunctionalTester $I)
    {
        $I->amOnPage(['crud/view', 'id' => 1000000]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->see('The requested page does not exist');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkDeleteAction(FunctionalTester $I)
    {
        // check allow access
        $model = Book::find()->where(['name' => 'erewfwef'])->one();
        $I->sendAjaxPostRequest(['crud/delete', 'id' => $model->id]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeRecord(Book::class, array('name' => 'erewfwef'));

        // check forbidden access
        $model = Book::find()->where(['name' => 'checkAccessForbidden'])->one();
        $I->sendAjaxPostRequest(['crud/delete', 'id' => $model->id]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkCreateAction(FunctionalTester $I)
    {
        $I->amOnPage(['crud/index']);
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
        $I->sendAjaxGetRequest(['crud/create']);
        $I->seeResponseCodeIs(HttpCode::OK);

        // Test send ajax data form
        $I->sendAjaxPostRequest([
            'book/create',
            'redirect' => 'index'
        ], ['Book[name]' => 'test_111']);

        $I->seeResponseCodeIs(HttpCode::OK);

        $model = Book::find()->where(['name' => 'test_111'])->one();
        $I->amOnPage(['crud/view', 'id' => $model->id]);
        $I->see('View test_111');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkUpdateAction(FunctionalTester $I)
    {
        $I->amOnPage(['crud/index']);
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
        $I->sendAjaxGetRequest(['crud/update', 'id' => $model->id]);
        $I->seeResponseCodeIs(HttpCode::OK);

        // Test send ajax data form
        $I->sendAjaxPostRequest([
            'book/update',
            'id' => $model->id,
            'redirect' => 'index'
        ], ['Book[name]' => 'test_121']);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->amOnPage(['crud/view', 'id' => $model->id]);
        $I->see('View test_121');
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkValidateAction(FunctionalTester $I)
    {
        $model = Book::find()->where(['name' => 'erewfwef'])->one();

        // validate Update action
        $I->sendAjaxPostRequest([
            'crud/validate',
            'id' => $model->id,
            'scenario' => $model::SCENARIO_UPDATE,
        ], ['Book[name]' => 'test_123']);
        $I->seeResponseCodeIs(HttpCode::OK);

        // validate create action
        $I->sendAjaxPostRequest([
            'crud/validate',
        ], ['Book[name]' => 'test_444']);
        $I->seeResponseCodeIs(HttpCode::OK);

        // NOT_FOUND
        $I->sendAjaxPostRequest([
            'crud/validate',
            'id' => 1000000,
            'scenario' => 'test',
        ], ['Book[name]' => 'test_555']);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        //$I->see('The requested page does not exist');

        // BAD_REQUEST
        $I->sendAjaxPostRequest(['crud/validate'], []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        //$I->see('Invalid Request');
    }


}