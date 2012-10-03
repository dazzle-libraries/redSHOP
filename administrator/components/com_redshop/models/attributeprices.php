<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model.php';

class RedshopModelAttributeprices extends RedshopCoreModel
{
    public $_sectionid = 0;

    public $_section = null;

    public $_total = null;

    public $_pagination = null;

    public $_context = 'price_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit      = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

        $section_id       = JRequest::getVar('section_id');
        $this->_section   = JRequest::getVar('section');
        $this->_sectionid = (int)$section_id;
    }

    public function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_data;
    }

    public function getTotal()
    {
        if (empty($this->_total))
        {
            $query        = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }

    public function getPagination()
    {
        if (empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_pagination;
    }

    public function _buildQuery()
    {
        if ($this->_section == "property")
        {
            $field = "ap.property_name ";
            $q     = 'LEFT JOIN ' . $this->_table_prefix . 'product_attribute_property AS ap ON p.section_id = ap.property_id ';
        }
        else
        {
            $field = "ap.subattribute_color_name AS property_name ";
            $q     = 'LEFT JOIN ' . $this->_table_prefix . 'product_subattribute_color AS ap ON p.section_id = ap.subattribute_color_id ';
        }
        $query = 'SELECT p.*, g.shopper_group_name, ' . $field . ' FROM ' . $this->_table_prefix . 'product_attribute_price AS p ' . 'LEFT JOIN ' . $this->_table_prefix . 'shopper_group AS g ON p.shopper_group_id = g.shopper_group_id ' . $q . 'WHERE p.section_id="' . $this->_sectionid . '" ' . 'AND p.section = "' . $this->_section . '" ';
        return $query;
    }

    public function delete($cid = array())
    {
        if (count($cid))
        {
            $cids  = implode(',', $cid);
            $query = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_price ' . 'WHERE price_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }
}

