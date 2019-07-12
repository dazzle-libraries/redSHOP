<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Administrator\plugins\PluginPaymentManagerJoomla;
use Configuration\ConfigurationSteps;
use Frontend\payment\CheckoutWithEWAYPayment;

/**
 * Class ProductsCheckoutEWAYCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutEWAYCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.2
	 */
	public $faker;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $productName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $productNumber;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $productPrice;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $minimumQuantity;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $maximumQuantity;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $customerInformation;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $checkoutAccountInformation;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $group;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $extensionURL;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $pluginName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $pluginURL;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $package;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public $cartSetting;

	public function __construct()
	{
		$this->faker            = Faker\Factory::create();
		$this->categoryName     = $this->faker->bothify('CategoryName ?###?');
		$this->productName      = $this->faker->bothify('Testing Product ??####?');
		$this->productNumber    = $this->faker->numberBetween(999, 9999);
		$this->productPrice     = 100;
		$this->minimumQuantity  = 1;
		$this->maximumQuantity  = $this->faker->numberBetween(11, 100);

		//configuration enable one page checkout
		$this->cartSetting = array(
			"addcart"            => 'product',
			"allowPreOrder"      => 'yes',
			"cartTimeOut"        => $this->faker->numberBetween(100, 10000),
			"enabldAjax"         => 'no',
			"defaultCart"        => null,
			"buttonCartLead"     => 'Back to current view',
			"onePage"            => 'yes',
			"showShippingCart"   => 'no',
			"attributeImage"     => 'no',
			"quantityChange"     => 'no',
			"quantityInCart"     => 0,
			"minimunOrder"       => 0,
			"enableQuation"      => 'no',
			"onePageNo"          => 'no',
			"onePageYes"         => 'yes'
		);

		$this->customerInformation = array(
			"userName"      => $this->faker->bothify('UserName ?####?'),
			"password"      => $this->faker->bothify('Password ?##?'),
			"email"         => $this->faker->email,
			"firstName"     => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"      => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"       => "Some Place in the World",
			"postalCode"    => "23456",
			"city"          => "HCM",
			"country"       => "Denmark",
			"state"         => "Karnataka",
			"phone"         => "8787878787",
			"shopperGroup"  => 'Default Private',
		);
		$this->group          = 'Registered';

		$this->extensionURL   = 'extension url';
		$this->pluginName     = 'E-Way Payments';
		$this->pluginURL      = 'paid-extensions/tests/releases/plugins/';
		$this->pakage         = 'plg_redshop_payment_rs_payment_eway.zip';

		$this->checkoutAccountInformation = array(
			"customerID" => "87654321",
			"debitCardNumber" => "4444333322221111",
			"cvv"             => "123",
			"cardExpiryMonth" => '5',
			"cardExpiryYear" => '2025',
			"shippingAddress" => "some place on earth",
			"customerName" => 'Your Name'
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since  2.1.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AdminManagerJoomla3Steps $I
	 * @throws Exception
	 * @since  2.1.2
	 */
	public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
	{
//		$I->wantTo("install plugin payment E-Way");
//		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->pakage);
//		$I->waitForText(AdminJ3Page:: $messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable Plugin E-Way Payments in Administrator');
		$I->enablePlugin($this->pluginName);
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->configEWayPlugin($this->pluginName, $this->checkoutAccountInformation['customerID']);
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since  2.1.2
	 */
	public function testEWAYPaymentPlugin(ConfigurationSteps $I, $scenario)
	{
		$I->cartSetting($this->cartSetting["addcart"], $this->cartSetting["allowPreOrder"], $this->cartSetting["enableQuation"],$this->cartSetting["cartTimeOut"], $this->cartSetting["enabldAjax"], $this->cartSetting["defaultCart"],
			$this->cartSetting["buttonCartLead"], $this->cartSetting["onePageYes"], $this->cartSetting["showShippingCart"], $this->cartSetting["attributeImage"], $this->cartSetting["quantityChange"], $this->cartSetting["quantityInCart"], $this->cartSetting["minimunOrder"]);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I = new CheckoutWithEWAYPayment($scenario);
		$I->checkoutProductWithEWAYPayment($this->checkoutAccountInformation,$this->productName, $this->categoryName, $this->customerInformation);

		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Check Order');
		$I->checkPriceTotal($this->productPrice, $this->customerInformation["firstName"], $this->customerInformation["firstName"], $this->customerInformation["lastName"], $this->productName, $this->categoryName, $this->pluginName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since  2.1.2
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Order in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder( $this->customerInformation['firstName']);

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantToTest('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->customerInformation["firstName"]);
	}
}
