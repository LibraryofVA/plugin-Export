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

//create empty array to hold files
$arrayOfFiles = array();

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
				$transcriptionTitle = preg_replace("/&#039;/", "'", item('Dublin Core', 'Title'));
				$transcriptionTitle = iconv('UTF-8', 'windows-1252', $transcriptionTitle);
				
				//build array of files
				$arrayOfFiles[] = array('id' => item_file('id'), 'of' => $jpgFileName, 'title' => $transcriptionTitle, 'date' => item('Dublin Core', 'Date'), 'trans' => $transcriptionText);
			}
		endwhile;
		
		//sort the array of files
		$arr2 = array_msort($arrayOfFiles, array('of'=>SORT_ASC));
		//build pdf page for each file
		foreach ($arr2 as $key => $row) {
			$pdf->AddPage();
			$pdf->SetFont('Times','',12);
			$pdf->Cell(40,15,$pdf->Image('http://www.virginiamemory.com/transcribe/plugins/Export/logo.png', 10, 10, 35),0,0);
			$pdf->Cell(0,5,$row['title'],0,1);
			$pdf->SetX(50); //indent the next cell
			$pdf->Cell(0,5,$row['date'],0,1);
			$pdf->SetX(50); //indent the next cell
			$pdf->Cell(0,5,$row['of'],0,1);
			$pdf->Cell(0,5,"",0,1);
			$pdf->MultiCell(0,5,$row['trans']);
		}
		//clear arrays
		$arr2 = array();
		$arrayOfFiles = array();
		
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

function array_msort($array, $cols) {
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;
}
?>
