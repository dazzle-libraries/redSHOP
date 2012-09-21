<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class categoryController extends JController
{
    function cancel()
    {
        $this->setRedirect('index.php');
    }

    /**
     * assign template to multiple categories
     *
     */
    function assignTemplate()
    {
        $post = JRequest::get('post');

        $model = $this->getModel('category');

        if ($model->assignTemplate($post))
        {
            $msg = JText::_('COM_REDSHOP_TEMPLATE_ASSIGN_SUCESS');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_ASSIGNING_TEMPLATE');
        }
        $this->setRedirect('index.php?option=com_redshop&view=category', $msg);
    }

    function saveorder()
    {
        $option = JRequest::getVar('option');

        $cid   = JRequest::getVar('cid', array(), 'post', 'array');
        $order = JRequest::getVar('order', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        $model = $this->getModel('category');
        $model->saveorder($cid, $order);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
    }

    function autofillcityname()
    {
        $db = JFactory::getDBO();
        ob_clean();
        $mainzipcode = JRequest::getString('q', '');
        $sel_zipcode = "select city_name from #__redshop_zipcode where zipcode='" . $mainzipcode . "'";
        $db->setQuery($sel_zipcode);
        echo $db->loadResult();
        exit;
    }
}
