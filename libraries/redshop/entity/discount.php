<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Discount Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityDiscount extends RedshopEntity
{
	/**
	 * @var RedshopEntitiesCollection
	 */
	protected $shopperGroups;

	/**
	 * Method for get shopper groups associate with this discount
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getShopperGroups()
	{
		if (null === $this->shopperGroups)
		{
			$this->loadShopperGroups();
		}

		return $this->shopperGroups;
	}

	/**
	 * Method for load shopper groups associate with this discount
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function loadShopperGroups()
	{
		$this->shopperGroups = new RedshopEntitiesCollection;

		if (!$this->hasId())
		{
			return $this;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('shopper_group_id'))
			->from($db->qn('#__redshop_discount_shoppers'))
			->where($db->qn('discount_id') . ' = ' . $this->getId());

		$result = $db->setQuery($query)->loadColumn();

		if (empty($result))
		{
			return $this;
		}

		foreach ($result as $shopperGroupId)
		{
			$this->shopperGroups->add(RedshopEntityShopper_Group::getInstance($shopperGroupId));
		}

		return $this;
	}
}
