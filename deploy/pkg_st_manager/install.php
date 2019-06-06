<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

class Pkg_St_ManagerInstallerScript
{
    public function postflight($type, $parent)
    {
        if ($type !== 'install') {
            return;
        }

        JHtml::_('jquery.framework');
        JHTML::_('bootstrap.framework');
        JHTML::_('behavior.modal');

        $modal_params = array();
        $modal_params['title'] = "Information for contact";
        $modal_params['backdrop'] = "true";
        $modal_params['height'] = "500px";
        $modal_params['width'] = "400px";

        $body = <<<EOD
<!--[if lte IE 8]>
<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
<![endif]-->
<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
<script>
hbspt.forms.create({
    portalId: "4950983",
    formId: "5da21dc3-49d9-49bb-aec1-2272338dbdcb",
    onFormReady: function(form){
        form.find('input[name="utm_source"]').val('connectors')
        form.find('input[name="utm_medium"]').val('referral')
        form.find('input[name="utm_campaign"]').val('joomla')
    },
    onFormSubmit: function(){
        jQuery("#hbsptModal").modal("hide");
    },
});
</script>
EOD;

        echo JHTML::_('bootstrap.renderModal', 'hbsptModal', $modal_params, $body);
        echo '<script>setTimeout(function(){jQuery("#hbsptModal").modal("show");}, 500)</script>';
        echo '<style>#hbsptModal .modal-body{ max-height: 600px; box-sizing: border-box; padding: 10px 20px;}</style>';
    }
}
