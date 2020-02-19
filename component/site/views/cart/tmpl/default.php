<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JHTML::_('behavior.modal');

// Define array to store product detail for ajax cart display
$cartData = $this->data[0]->template_desc;

if ($cartData == "")
{
	if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'))
	{
		$cartData = '<h1>{cart_lbl}</h1><div class="category_print">{print}</div><br/><br/><table style="width: 90%;" border="0" cellspacing="10" cellpadding="10"><tbody><tr><td><table class="tdborder" style="width: 100%;" border="0" cellspacing="0" cellpadding="0"><thead><tr><th width="40%" align="left">{product_name_lbl}</th> <th width="35%"> </th><th width="25%">{quantity_lbl}</th></tr></thead><tbody>{product_loop_start}<tr class="tdborder"><td><div class="cartproducttitle">{product_name}</div><div class="cartattribut">{product_attribute}</div><div class="cartaccessory">{product_accessory}</div><div class="cartwrapper">{product_wrapper}</div><div class="cartuserfields">{product_userfields}</div></td><td>{product_thumb_image}</td><td><table border="0"><tbody><tr><td>{update_cart}</td><td>{remove_product}</td></tr></tbody></table></td></tr>{product_loop_end}</tbody></table></td></tr><tr><td><br></td></tr><tr><td><table style="width: 100%;" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td>{update}</td><td>{empty_cart}</td><td>{quotation_request}</td><td>{shop_more}</td></tr></tbody></table></td></tr></tbody></table>';
	}
	else
	{
		$cartData = RedshopHelperTemplate::getDefaultTemplateContent('cart');
	}
}

echo RedshopTagsReplacer::_(
	'cart',
	$cartData,
	array(
		'cart' => $this->cart
	)
);

