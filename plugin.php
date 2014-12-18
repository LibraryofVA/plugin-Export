<?php
class ExportPlugin extends Omeka_Plugin_Abstract
{
    protected $_hooks = array(
		'admin_append_to_collections_show_primary'
	);
 	
	/**
	* Hook into admin_append_to_collections_show_primary
	*
	* @param $collection
	*/
	public function hookAdminAppendToCollectionsShowPrimary($collection)
	{
		echo "<br /><p><a href=\"/transcribe/admin/export/?c=" . collection('id') . "\">Download PDF file for each collection->item->files transcription</a></p>";
	}
	
}
$exportPlugin = new ExportPlugin();
$exportPlugin->setUp();