<?php

namespace Page\WorkOrders;

use \FunctionalTester;
use \common\BaseTest;

class TrashWorkOrderPage
{
    // include url of current page
    public static $URL = '/workOrder/1';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $btnTrash = [
        'txt'       =>  'Mover a Papelera',
        'selector'  =>  'button.btn.btn-danger'
    ];
    
    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var FunctionalTester;
     */
    protected $functionalTester;

    public function __construct(FunctionalTester $I)
    {
        $this->functionalTester = $I;
    }

    /**
     * @return TrashWorkOrderPagwePage
     */
    public static function of(FunctionalTester $I)
    {
        return new static($I);
    }
}