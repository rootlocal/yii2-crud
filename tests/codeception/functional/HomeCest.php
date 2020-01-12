<?php

namespace rootlocal\crud\tests\functional;

use rootlocal\crud\tests\FunctionalTester;
use Yii;

/**
 * Class HomeCest
 * @package rootlocal\crud\tests\functional
 */
class HomeCest
{

    /**
     * @param FunctionalTester $I
     */
    public function checkHome(FunctionalTester $I)
    {
        $I->amOnPage(Yii::$app->homeUrl);
        $I->see('My Application');
        $I->seeLink('About');
        $I->click('About');
        $I->see('This is the About page.', 'p');
    }
}