<?php
/**
 * Created by PhpStorm.
 * User: Hieu
 * Date: 06/08/2014
 * Time: 10:03
 */
class Baokim_PaymentPro_Block_Adminhtml_Group extends Mage_Adminhtml_Block_System_Config_Form_Fieldset{
	/**
	 * Return header comment part of html for fieldset
	 *
	 * @param Varien_Data_Form_Element_Abstract $element
	 * @return string
	 */
	protected function _getHeaderCommentHtml($element)
	{

		$html = '<div class="baokim-config-heading" ><div class="heading"><strong>Payments Pro';
		$html .= '</strong>';
		  $html .= '<a class="link-more" href="https://www.baokim.vn/developers/tai-lieu/27/tq-tich-hop-pro" target="_blank">'
			 . $this->__('  Learn More') . '</a>';

		$groupConfig = $this->getGroup($element)->asArray();

		if (empty($groupConfig['help_url']) || !$element->getComment()) {
			return parent::_getHeaderCommentHtml($element);
		}

		$html .= '<div class="comment">' . $element->getComment()
			. '</div></div>';

		$html .= '<div class="bk-allinone"></div>';
		$html .= '</div>';



		return $html;
	}

	/**
	 * Return collapse state
	 *
	 * @param Varien_Data_Form_Element_Abstract $element
	 * @return bool
	 */
	protected function _getCollapseState($element)
	{
		$extra = Mage::getSingleton('admin/session')->getUser()->getExtra();
		if (isset($extra['configState'][$element->getId()])) {
			return $extra['configState'][$element->getId()];
		}

		if ($element->getExpanded() !== null) {
			return 1;
		}

		return false;
	}
}