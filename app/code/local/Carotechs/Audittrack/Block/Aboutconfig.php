<?php
class Carotechs_Audittrack_Block_Aboutconfig
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{

    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = <<<HTML
		<div style="border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 5px 5px 10px;">
	     <p><b>You can enable or disable Audit Track to the following entities.</b></p>
</div>
HTML;
        return $html;
    }
}
