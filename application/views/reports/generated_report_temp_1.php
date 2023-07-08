<?
$path = '/var/www/html/template/application/third_party/php/pear';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
$_SERVER['DOCUMENT_ROOT'] .= '/template';
include_once($_SERVER['DOCUMENT_ROOT']. '/application/third_party/Spreadsheet/Excel/Writer.php');

//mon (2017-06-17)
//Create new template report for template_1 campaign

// Creating a workbook
$workbook = new Spreadsheet_Excel_Writer();
$workbook->setVersion(8);

$setSize = 11; //common font size
$setFamily = 'Calibri'; //common font family


$yellowHighlight =& $workbook->addFormat();
$yellowHighlight->setFgColor('yellow');
$yellowHighlight->setBorder(1);
$yellowHighlight->setFontFamily($setFamily);
$yellowHighlight->setSize($setSize);

$boldHeader =& $workbook->addFormat();
$boldHeader->setHAlign('left');
$boldHeader->setBold();  
$boldHeader->setBorder(1);  
$boldHeader->setTextWrap(1);
$boldHeader->setFontFamily($setFamily);
$boldHeader->setSize($setSize);


$border =& $workbook->addFormat();
$border->setHAlign('left');
$border->setBorder(1);  
$border->setFontFamily($setFamily);
$border->setSize($setSize);

//smaller size of $border has txt wrap
$border_small_size_txt_wrap =& $workbook->addFormat();
$border_small_size_txt_wrap->setHAlign('left');
$border_small_size_txt_wrap->setBorder(1);  
$border_small_size_txt_wrap->setTextWrap(1);
$border_small_size_txt_wrap->setFontFamily($setFamily);
$border_small_size_txt_wrap->setSize(($setSize-1));


//smaller size of $border has txt wrap
$small_size_txt =& $workbook->addFormat();
$small_size_txt->setHAlign('left');
$small_size_txt->setBorder(1);  
$small_size_txt->setTextWrap(1);
$small_size_txt->setFontFamily($setFamily);
$small_size_txt->setSize(($setSize-2));

//border with wrap text
$border_txt_wrap=& $workbook->addFormat();
$border_txt_wrap->setHAlign('left');
$border_txt_wrap->setBorder(1);  
$border_txt_wrap->setTextWrap(1);
$border_txt_wrap->setFontFamily($setFamily);
$border_txt_wrap->setSize($setSize);

//no border with wrap text
$no_border_txt_wrap=& $workbook->addFormat();
$no_border_txt_wrap->setHAlign('left');
$no_border_txt_wrap->setBold();  
$no_border_txt_wrap->setBorder(0);  
$no_border_txt_wrap->setTextWrap(1);
$no_border_txt_wrap->setFontFamily($setFamily);
$no_border_txt_wrap->setSize($setSize);

//CENTER BOLD and wraping is true
$center_bold =& $workbook->addFormat();
$center_bold->setHAlign('center');
$center_bold->setBold();  
$center_bold->setBorder(1);  
$center_bold->setTextWrap(1);
$center_bold->setFontFamily($setFamily);
$center_bold->setSize($setSize);


//CENTER BOLD and wraping is true AND YELLOW BG
$center_yellowbg =& $workbook->addFormat();
$center_yellowbg->setHAlign('center');
$center_yellowbg->setFgColor('yellow');
$center_yellowbg->setBold();  
$center_yellowbg->setBorder(1);  
$center_yellowbg->setTextWrap(1);
$center_yellowbg->setFontFamily($setFamily);
$center_yellowbg->setSize($setSize);


//CENTER BOLD and wraping is true and VAlign is in MIDDLE
$center_bold_valign =& $workbook->addFormat();
$center_bold_valign->setHAlign('center');
$center_bold_valign->setBold();  
$center_bold_valign->setBorder(1);  
$center_bold_valign->setTextWrap(1);
$center_bold_valign->setFontFamily($setFamily);
$center_bold_valign->setSize($setSize);
$center_bold_valign->setAlign('vcenter');  

//SET gray BG
$gray_bg=& $workbook->addFormat();
$gray_bg->setBgColor('#F0F0F0');
$gray_bg->setPattern(2);

$col = 1;
$lblCol = 0;
$rowHeight = 17.25;

foreach($details as $d){
	$row =0;
	//max sheet name is 31
	$full_name = $d['c_firstname'] . '_' . $d['c_lastname'];
	$setName = substr($full_name.'_'.$d['id'],0,30);
	// Creating a worksheet
	$worksheet =& $workbook->addWorksheet($setName);
	$worksheet->setInputEncoding('utf-8'); 
	
	//check for the list of paper : http://rocksolidsolutions.org/reference/excel_file_format_paper_size_table.html
	//1 = Letter (short), 5 = Legal (Long), 14 (8 1/2 X 13)
	// $worksheet->setPaper(5);  //LONG
	$worksheet->setPaper(5); //LONG (LEGAL)
	
	$worksheet->setColumn(0,1,53);
	$worksheet->setColumn(2,3,12);
	// $worksheet->fitToPages (1,2); //this will the system do the actual paging based on the paper content and size Same as "Fit to:" in Excel
	$worksheet->setPrintScale(78); //Same as "Adjust to:" in Excel
	$worksheet->setZoom(100);
	
	//margin changed at 2019-03-02 from 0.25 to 0.0 in order to fit the reporting on Nino's PC
	$worksheet->setMargins_LR('0.0',0); //no margin for Left and Right
	
	$worksheet->setMargins_TB('0.5',0); //no margin for Top and Bottom
	
	//paging was removed at 2019-03-02 in order to fit the reporting on Nino's PC
	//$worksheet->setFooter ('Page &P of &N','0.15'); //show paging number 
	

  $date = substr($d['calldate'],0,10);
	$enrolled_date = date('F d, Y',strtotime($date));

	addBlackCell($worksheet,$row,0,3,$border);	
  $worksheet->write($row, $lblCol,  'DATE',$border);
	$worksheet->write($row, $col,  $enrolled_date,$boldHeader);
	
	$amf = $d['amf_product']; 
	$amf_selected = '';
	if(isset($amf_product[$amf])){
		$amf_selected = $amf_product[$amf];
	}
	
	$worksheet->setMerge($row, $lblCol+2, $row+1, $lblCol+3);
	$worksheet->write($row++, $lblCol+2,  $amf_selected,$center_bold_valign);
	addBlackCell($worksheet,$row,0,3,$border);	
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  '2ND CARD TYPE/CL',$border);
	
	$card_request = $d['sv_card_request_by_client'];
	if(isset($card_requestLUp[$d['sv_card_request_by_client']])){
		$card_request = $card_requestLUp[$d['sv_card_request_by_client']];
	}
	$worksheet->write($row++, $col,  $card_request . '/' . $d['2nd_card_credit_limit'],$boldHeader);
 
	addBlackCell($worksheet,$row,0,3,$border);
	$worksheet->write($row, $lblCol,  'SOURCE CODE',$border);
	$worksheet->writeString($row++, $col,  $d['sv_source_code'],$boldHeader);
	
	addBlackCell($worksheet,$row,0,3,$border);
	$worksheet->write($row++, $lblCol,  'NAME OF APPLICANT',$yellowHighlight);
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'LASTNAME',$border);
	$worksheet->write($row++, $col,  $d['c_lastname'],$border);
  
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'FIRSTNAME',$border);
	$worksheet->write($row++, $col,  $d['c_firstname'],$border);
  
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'MIDDLENAME',$border);
	$worksheet->write($row++, $col,  $d['c_middlename'],$border);
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->writeString($row, $lblCol,  'NAME ON FILE',$border);
	$worksheet->write($row++, $col,  $d['pd_name'],$border);
  
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->writeString($row, $lblCol,  'NAME TO APPEAR ON THE CARD',$border);
	$worksheet->write($row++, $col,  $d['c_name_in_card'],$border);
		
	//nationality lookup added @2021-07-10
	$nationality = $d['c_nationality'];
	if(isset($nationalityLU[$nationality])){
		$nationality = $nationalityLU[$nationality];
	}
	
	//->>>>>
	if($d['ag_type'] == 'AG10'){
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol,  'BIRTHDATE',$border);
		$worksheet->write($row++, $col,  date('F d, Y',strtotime($d['c_dob'])),$border);
		
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol,  'PLACE OF BIRTH',$border);
		$worksheet->write($row++, $col,  $d['c_place_of_birth'],$border);
		
		$address = $d['c_present_add1'] . ' ' . $d['c_present_add2'] . ' ' . $d['c_present_add3'];
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol,  'HOME ADDRESS',$border);
		$worksheet->write($row++, $col,  $address,$border);
		
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol,  'SOURCE OF FUND',$border);
		$worksheet->write($row++, $col,  (isset($sofLU[$d['c_sof']]) ? strtoupper($sofLU[$d['c_sof']]) : ''),$border);
		
		addBlackCell($worksheet,$row,0,3,$border);
		$worksheet->write($row, $lblCol,  'EMPLOYMENT DETAILS',$yellowHighlight);
		$worksheet->write($row, 1,  '',$yellowHighlight);
		$worksheet->write($row, 2,  '',$yellowHighlight);
		$worksheet->write($row++, 3,  '',$yellowHighlight);
		
		// 
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol, ' COMPANY NAME',$boldHeader);
		$worksheet->write($row++, $col,  $d['c_company_name'],$border);
		
		// 
		$company_address = $d['c_comp_add1'] . ' ' . $d['c_comp_add2'] . ' ' . $d['c_comp_add3'];
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol, ' OFFICE ADDRESS',$boldHeader);
		$worksheet->write($row++, $col,  $company_address,$border);
		
		// 
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol,  ' POSITION/RANK',$boldHeader);
		$worksheet->write($row++, $col,  $d['c_occupation_pos'],$border);
		
		// 
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol,  'MOBILE NUMBER',$border);
		$worksheet->write($row++, $col,  $d['c_mobileno'],$border);
		
		// 
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol,  'LANDLINE NUMBER',$border);
		$worksheet->write($row++, $col,  $d['c_homeno'],$border);
		
		// 
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol,  'NATIONALITY',$border);
		$worksheet->write($row++, $col,  $nationality,$border);
		
		// 
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->writeString($row, $lblCol,  'ID SUBMITTED',$border);
		$worksheet->write($row++, $col,  '',$border);
		

	}else{
		addBlackCell($worksheet,$row,0,3,$border);
		$worksheet->write($row, $lblCol,  'NATIONALITY',$border);
		$worksheet->write($row++, $col,  $nationality,$border);

		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  'IF US CITIZEN (US TIN )',$border);
		$worksheet->write($row++, $col,  $d['c_us_tin'],$border);

		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  'MOBILE NUMBER ON FILE',$border);
		$worksheet->writeString($row++, $col,  $d['mobileno'],$border);

		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  'LANDLINE NUMBER ON FILE',$border);
		$officeno = $d['officeno'];
		$telno 	  = $d['telno'];
		$landline = $officeno;
		if(!empty($telno) && !empty($officeno)){
			$landline .= '/'; //add slash
		}
		$landline .= $telno;
		
		if(empty($landline)){
			$landline = 'NA';
		}
		
		$worksheet->writeString($row++, $col,  $landline,$border);
		
		/*TIN GSIS SSS*/

		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  'SSS (AS PER TM)',$border);
		$worksheet->write($row++, $col,  $d['c_sss_gsis'],$border); 
		
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  'TIN (AS PER RBSC FILE)',$border);
		$worksheet->writeString($row++, $col,   empty($d['tin']) ? 'NA' : $d['tin'] ,$border);
		
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  'TIN (AS PER TM)',$border);
		$worksheet->write($row++, $col,  $d['c_tin'],$border);

		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  'EMAIL ADDRESS',$border);
		$worksheet->write($row++, $col,  $d['email_addr'],$border);

		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  'E-SOA',$border);
		$worksheet->write($row++, $col,  empty($d['c_esoa']) ? 'NA' : $d['c_esoa'] ,$border);

		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  'WEB SHOPPER',$border);
		$worksheet->write($row++, $col,  isset($yesnoLU[$d['c_is_web_shopper']]) ? strtoupper($yesnoLU[$d['c_is_web_shopper']]) : '',$border);

		addBlackCell($worksheet,$row,0,3,$border);	
		$this->row_not_set_height[$row] = true;
		$worksheet->setRow($row,30);		 //make sure to set $border_txt_wrap
		$worksheet->write($row, $lblCol,  'HAVE YOU STAYED IN THE USA FOR 180 DAYS IN THE LAST 3 YRS (YES/NO)',$border_small_size_txt_wrap);
		$worksheet->write($row++, $col,  isset($yesnoLU[$d['c_stayed_in_us']]) ? strtoupper($yesnoLU[$d['c_stayed_in_us']]) : '',$border);


		/*SUPPLEMENTARY CARDS*/

		if(isset($supple[$d['id']])){
			
			$suppleCtr = 0;
			foreach($supple[$d['id']] as $sup){
				
				//Added by Sir Vince at 2019-03-02 that once SV tagged the supple as with_id = NONE then it will not be included on the SF
				if($sup['with_id'] == '2') continue;

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row++, $lblCol,  'SUPPLEMENTARY CARDS '.($suppleCtr+1),$yellowHighlight);
				
				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'LASTNAME',$border);
				$worksheet->write($row++, $col,  $sup['lastname'],$border);
				
				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'FIRSTNAME',$border);
				$worksheet->write($row++, $col,  $sup['firstname'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'MIDDLENAME',$border);
				$worksheet->write($row++, $col,  $sup['middlename'],$border);
				
				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'DATE OF BIRTH',$border);
				$worksheet->write($row++, $col,  $sup['dob'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'GENDER',$border);
				$worksheet->write($row++, $col,  strtoupper($sup['gender']), $border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'RELATIONSHIP TO THE PRINCIPAL',$border);
				$worksheet->write($row++, $col,  isset($relationship[($sup['relationship'])]) ? $relationship[$sup['relationship']] : '', $border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'PLACE OF BIRTH',$border);
				$worksheet->write($row++, $col,  $sup['place_of_birth'],$border);
				
				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'CIVIL STATUS',$border);
				$worksheet->write($row++, $col,  $sup['civil_status'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'NATIONALITY',$border);
				$worksheet->write($row++, $col,  $sup['nationality'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'HOME PHONE NUMBER',$border);
				$worksheet->write($row++, $col,  $sup['home_no'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'OFFICE PHONE NO.',$border);
				$worksheet->write($row++, $col,  $sup['office_no'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'MOBILE NO.',$border);
				$worksheet->write($row++, $col,  $sup['mobile_no'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  "EMPLYED,GOV'T,RETIRED/UNEMPLOYED",$border_small_size_txt_wrap);
				$worksheet->write($row++, $col,  isset($employment[$sup['employment']]) ? $employment[$sup['employment']] : '',$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  ' COMPANY NAME',$border);
				$worksheet->write($row++, $col,  $sup['comp_name'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'COMPANY ADDRESS',$border);
				$worksheet->write($row++, $col,  $sup['comp_add'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'EMAIL ADDRESS',$border);
				$worksheet->write($row++, $col,  $sup['email_add'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'OCCUPATION/POSITION',$border);
				$worksheet->write($row++, $col,  $sup['occupation_pos'],$border);

				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->write($row, $lblCol,  'ASSIGNED SPEND LIMIT',$border);
				$worksheet->write($row++, $col,  $sup['assigned_spend_limit'],$border);
				
				$suppleCtr++;
			}

		} 		
		
		/**
		* START OF IF WITH PEROSNAL CHANGES
		**/
		
		
			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->setMerge($row, 0, $row, 1);
			$worksheet->write($row, $lblCol,  'CARDHOLDER WAS INFORMED RCBC TO USE EXISTING INFORMATION',$yellowHighlight);
			$worksheet->setMerge($row, 2, $row, 3);
			$worksheet->write($row, 2,  '',$yellowHighlight);
			$worksheet->write($row++, 3,  '',$yellowHighlight);
		
		
			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->write($row, $lblCol,  'WITH PERSONAL INFORMATION CHANGE',$yellowHighlight);
			$worksheet->write($row, $col,  '',$yellowHighlight);
			$worksheet->setMerge($row, 2, $row, 3);
			$worksheet->write($row++, 2,  isset($yesnoneLU[$d['c_with_personal_changes']]) ? strtoupper($yesnoneLU[$d['c_with_personal_changes']]) : '',$center_yellowbg);
			
			if($d['c_with_personal_changes'] == 1){
				
				//THESE fields should be applied by AGENT, and only the fields with VALUE will be DISPLAYED
				//IT SHOULD also collab with c_with_personal_changes because these fields represent those changese
				//AS of we only declared 4 common fields
				$special_fields = array('cc_marital_status'=>'MARITAL STATUS',
										'cc_office_address'=>'OFFICE ADDRESS',
										'cc_address'=>'HOME ADRESS',
										'cc_email'=>'EMAIL ADDRESS',
										'cc_phone_no'=>'PHONE NUMBER'
										);
										
				foreach($special_fields as $field=>$label){
					
					$value = strtoupper($d[$field]);
					if(!empty($value)){
						addBlackCell($worksheet,$row,0,3,$border);	
						$worksheet->write($row, $lblCol,  $label,$border);
						$worksheet->write($row++, $col,  $value,$border);
					}
					
				}
				
			
				addBlackCell($worksheet,$row,0,3,$border);	
				$worksheet->setMerge($row, 0, $row, 3);
				$worksheet->write($row++, $lblCol,  '',$border);
			}
	}
	
	
	/**
	* START OF STATIC WORDINGS 
	**/
		
	
		
		//SHOULD MERGE
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->setMerge($row, 0, $row, 3);
		$worksheet->write($row++, $lblCol,  "On Authority to Disclose:",$center_bold);
		
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->setMerge($row, 0, $row, 1);
		$worksheet->write($row, 2,  'YES',$center_bold);
		$worksheet->write($row++, 3,  'NO',$center_bold);
		
		
		//BACKGROUDN color should gray!
		addBlackCell($worksheet,$row,0,3,$border);	 
		$worksheet->setMerge($row, 0, $row, 3);
		$worksheet->write($row++, 0,  '',$gray_bg);
		
		
		addBlackCell($worksheet,$row,0,3,$border);
		$this->row_not_set_height[$row] = true;
		$worksheet->setRow($row,30);		
		$worksheet->setMerge($row, 0, $row, 1);
		$question = "By providing all relevant information for the processing of your application, do you confirm your agreement to the same even in the absence of your actual signature";
		$worksheet->write($row, 0,  $question,$border_txt_wrap);
		$worksheet->write($row++, 2,  'YES',$center_bold);
		
	

		
		for($rstart=1;$rstart<=$row;$rstart++){
			if(!isset($this->row_not_set_height[$rstart])){
				$worksheet->setRow($rstart,$rowHeight);	
			}
		}

	#$col++;
	
	if(!empty($last_sv_remarks)){
		$row++;		
		addBlackCell($worksheet,$row,0,3,$border);
		$worksheet->setMerge($row, 0, $row+3, 3);
		$worksheet->write($row, 0, $last_sv_remarks,$center_bold_valign);
		addBlackCell($worksheet,$row+1,0,3,$border);
		addBlackCell($worksheet,$row+2,0,3,$border);
		addBlackCell($worksheet,$row+3,0,3,$border);
	}
	
	//launch date march 30, 2019 
	//if($username == 'mon'){
		include_once('back_generated_report_temp_1.php');
	//}
}
// Let's send the file
// sending HTTP headers
#exit();

if(!empty($table_recid)){
	$filename = $full_name;
}else{
	$filename = "SALESFILE {$startDate} to {$endDate}";
}
$workbook->send("{$filename}.xls");
$workbook->close();


/**
* ADD black cell, the 3rd and 4th row can have these value respectively 0 and 3
*/
function addBlackCell($worksheet,$row,$start_cell,$end_cell,$property){
	for($i=$start_cell;$i<=$end_cell;$i++){
		$worksheet->write($row, $i,  '',$property);
	}
}

/**
* Set the small att setting when the long_txt_size exceeded, else set the border_txt_wrap or the default setting
* This function will return the setting that being selected
**/
function setLongTextAttr($str,$border_txt_wrap,$small_size_txt){
	$long_txt_size = 100;
	$cell_attr_obj = $border_txt_wrap;
	if(strlen($str) > $long_txt_size){
		$cell_attr_obj	= $small_size_txt;
	}
	return $cell_attr_obj;
}
?>