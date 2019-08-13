<?php
class Carotechs_Audittrack_Block_Aboutprice
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
	     <p><b>You may receive an email notification when product price is changed.</b> 
		 <br/>Enable or Disable email notification alert here.</p>
</div>
HTML;
        return $html;
    }
}
