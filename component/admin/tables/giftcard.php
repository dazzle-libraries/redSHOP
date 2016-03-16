<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Giftcard table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.Giftcard
 * @since       1.6
 */
class RedshopTableGiftcard extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database driver object.
	 *
	 * @since  11.1
	 */
	public function __construct($db)
	{
		parent::__construct('#__redshop_giftcard', 'giftcard_id', $db);
	}

	/**
	 * Deletes this row in database (or if provided, the row of key $pk)
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($pk = null)
	{
		if ($this->giftcard_image != '' && file(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_image))
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_image);
		}

		if ($this->giftcard_bgimage != '' && file(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_bgimage))
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_bgimage);
		}

		return parent::delete($pk);
	}

	/**
	 * Method to store a node in the database table.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 */
	public function store($updateNulls = false)
	{
		JLoader::load('RedshopHelperProduct');
		JLoader::load('RedshopHelperAdminImages');
		JLoader::load('RedshopHelperAdminEconomic');

		$productHelper = new producthelper;

		// Get input
		$app = JFactory::getApplication();
		$input = $app->input;

		$giftcardfile = $input->files->get('jform');
		$image        = $giftcardfile['giftcard_image_file'];

		if ($image['name'] != '' && $this->giftcard_image != '')
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_image);
			$this->giftcard_image = '';
		}

		if ($image['name'] != '')
		{
			$image['name']        = RedShopHelperImages::cleanFileName($image['name']);
			$this->giftcard_image = $image['name'];
			JFile::upload($image['tmp_name'], REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $image['name']);
		}

		// Get background image file
		$bgImage = $giftcardfile['giftcard_bgimage_file'];

		if (($bgImage['name'] != '' && $this->giftcard_bgimage != ''))
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_bgimage);
			$this->giftcard_bgimage = '';
		}

		if ($bgImage['name'] != '')
		{
			$bgImage['name'] = RedShopHelperImages::cleanFileName($bgImage['name']);
			$this->giftcard_bgimage = $bgImage['name'];
			JFile::upload($bgImage['tmp_name'], REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $bgImage['name']);
		}

		$this->giftcard_price = $productHelper->redpriceDecimal($this->giftcard_price);
		$this->giftcard_value = $productHelper->redpriceDecimal($this->giftcard_value);

		if (!parent::store($updateNulls))
		{
			return false;
		}

		if (ECONOMIC_INTEGRATION == 1)
		{
			$economic                  = new economic;

			$giftdata                  = new stdClass;
			$giftdata->product_id      = $this->giftcard_id;
			$giftdata->product_number  = "gift_" . $this->giftcard_id . "_" . $this->giftcard_name;
			$giftdata->product_name    = $this->giftcard_name;
			$giftdata->product_price   = $this->giftcard_price;
			$giftdata->accountgroup_id = $this->accountgroup_id;
			$giftdata->product_volume  = 0;

			$economic->createProductInEconomic($giftdata);
		}

		return true;
	}
}