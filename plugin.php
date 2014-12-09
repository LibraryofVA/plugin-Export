<?php
class ExportPlugin extends Omeka_Plugin_Abstract
{
    protected $_hooks = array('install');
 
    public function hookInstall()
    {
       //no install work needed
    }
	
	protected $_filters = array(
        'admin_navigation_main',
    );
	 /**
     * Add Export to the admin navigation.
     * 
     * @param array $nav
     * @return array
     */

    public function filterAdminNavigationMain($nav)
    {
        $nav['Export'] = uri('export');
        return $nav;
    }

	
}
$exportPlugin = new ExportPlugin();
$exportPlugin->setUp();