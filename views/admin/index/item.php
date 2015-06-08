<?php
// Loop over all of the .pdf files in the PDF folder
foreach (glob(PDF_EXPORT_DIRECTORY . "*.pdf") as $file) {
	unlink($file); // unlink deletes a file
}

//include FPDF 
+require_once(FPDF_LOCATION);

//get collection from query string
$collectionID = $_GET['c'];
set_current_collection(get_collection_by_id($collectionID));

//create empty array to hold pdf files that we will ZIP
$arrayOfPDFs = array();

while (loop_items_in_collection(total_items_in_collection())):
	if (item_has_files()):
	    $pdf = new FPDF();
		while(loop_files_for_item()):
			if (item_file('Scripto', 'Transcription')) {
				//remove domain and any directory from orginal filename (jpg)
				if (strpos(item_file('original filename'), '/') !== FALSE) {
					$jpgFileName = substr(strrchr(item_file('original filename'), "/"), 1);
				} else {
					$jpgFileName = item_file('original filename');
				}
				//original filenames are standerdized in ####_####_###_page#.jpg format
				//set pdf name, substr -9 is taking off _page#.jpg
				$pdfFileName = substr($jpgFileName, 0, -9) . ".pdf";
				
				//clean <p>, </p>, <pre>, and </pre> out of the transcription
				$transcriptionText = preg_replace("/<[\/]*p>/", "", item_file('Scripto', 'Transcription'));
				$transcriptionText = preg_replace("/<[\/]*pre>/", "", $transcriptionText);
				$transcriptionText = preg_replace("/<br \/>/", "", $transcriptionText);
				//replace &amp; with &
				$transcriptionText = preg_replace("/&amp;/", "&", $transcriptionText);
				$transcriptionText = iconv('UTF-8', 'windows-1252', $transcriptionText);
				$pdf->AddPage();
				$pdf->SetFont('Times','',12);
				$pdf->Cell(0,5,item('Dublin Core', 'Title'),0,1);
				$pdf->Cell(0,5,item('Dublin Core', 'Date'),0,1);
				$pdf->Cell(0,5,$jpgFileName,0,1);
				$pdf->Cell(0,5,"",0,1);
				$pdf->MultiCell(0,5,$transcriptionText);
			}
		endwhile;
		
		$content = $pdf->Output(PDF_EXPORT_DIRECTORY . $pdfFileName,'F');
		//add the pdf name to an array used later to zip the files
		$arrayOfPDFs[] = $pdfFileName;
	endif;
endwhile;

$result = create_zip($arrayOfPDFs,PDF_EXPORT_DIRECTORY . "collection.zip",PDF_EXPORT_DIRECTORY);
if($result) {
	header("Content-type: application/zip"); 
	header("Content-Disposition: attachment; filename=collection.zip"); 
    header("Pragma: no-cache"); 
    header("Expires: 0"); 
    readfile(PDF_EXPORT_DIRECTORY . "collection.zip");
}


/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$localPdfDirectory = '',$overwrite = true) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($localPdfDirectory . $file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($localPdfDirectory . $file,$file);
		}
		//debug ** turning this on will break the forced download of ZIP file as the echo result will be added to the zip file downloaded
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		//close the zip
		$zip->close();
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}
?>