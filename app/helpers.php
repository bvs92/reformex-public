<?php


function get_mime_type($file) {
	$mtype = false;
	if (function_exists('finfo_open')) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mtype = finfo_file($finfo, $file);
		finfo_close($finfo);
	} elseif (function_exists('mime_content_type')) {
		$mtype = mime_content_type($file);
	} 
	return $mtype;
}


function formatCarbonDate($carbonDate){
	$carbonDate->locale('ro_RO');
	return $carbonDate->isoFormat('LLLL');
}

function formatShortCarbonDate($carbonDate){
	$carbonDate->locale('ro_RO');
	return $carbonDate->isoFormat('Do MMMM YYYY');
}


function carbonDateToRo($carbonDate){
	$carbonDate->locale('ro_RO');
	return $carbonDate->diffForHumans();
}


function convertFormatShortCarbonDate($carbonDate){
	$carbonDate = new \Carbon\Carbon($carbonDate);
	$carbonDate->locale('ro_RO');
	return $carbonDate->isoFormat('Do MMMM YYYY');
}

function convertCarbonDateToRo($carbonDate){
	$carbonDate = new \Carbon\Carbon($carbonDate);
	$carbonDate->locale('ro_RO');
	return $carbonDate->diffForHumans();
}



// status payment

function getStatus($status)
{
	if($status == 'succeeded'){
		return "Succes";
	} elseif($status == 'canceled'){
		return "Renuntare"; 
	} elseif($status == 'processing'){
		return "Procesare"; 
	} elseif($status == 'requires_payment_method'){
		return "Necesita metoda plata"; 
	} elseif($status == 'requires_confirmation'){
		return "Necesita confirmare"; 
	} elseif($status == 'requires_action'){
		return "Necesita actiune"; 
	} elseif($status == 'requires_capture'){
		return "Necesita captura"; 
	}
}