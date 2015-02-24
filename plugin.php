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
		echo "<br /><p><a href=\"/transcribe/admin/export/?c=" . collection('id') . "\">Download ZIP file containing a transcription pdf for each file (collection->item->file)</a></p>";
	}

}
$exportPlugin = new ExportPlugin();
$exportPlugin->setUp();

// directory where PDF files will be created and ZIP'd. httpd service must have write access
defined('PDF_EXPORT_DIRECTORY') or define('PDF_EXPORT_DIRECTORY', dirname(__FILE__)."/PDF/");
// FPDF file location
defined('FPDF_LOCATION') or define('FPDF_LOCATION', dirname(__FILE__)."/fpdf.php");