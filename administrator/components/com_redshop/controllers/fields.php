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

class fieldsController extends JController
{
    function cancel()
    {
        $this->setRedirect('index.php');
    }

    function saveorder()
    {
        $option = JRequest::getVar('option');
        $cid    = JRequest::getVar('cid', array(0), 'post', 'array');
        $model  = $this->getModel('fields');

        if ($model->saveorder($cid))
        {
            $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_NEW_ORDERING_ERROR');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
    }
}
