<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class attribute_setViewattribute_set extends JView
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($tpl = null)
	{
		global $mainframe, $context;

		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_ATTRIBUTE_SET'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_ATTRIBUTE_SET'), 'redshop_attribute_bank48');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', 'Copy', true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri =& JFactory::getURI();

		$filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'attribute_set_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists = array();
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$products = & $this->get('Data');
		$pagination = & $this->get('Pagination');

		$this->assignRef('lists', $lists);
		$this->assignRef('products', $products);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());
		parent::display($tpl);
	}
}
