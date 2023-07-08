<?
$path = '/var/www/html/template/application/third_party/php/pear';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
$_SERVER['DOCUMENT_ROOT'] .= '/template';
include_once($_SERVER['DOCUMENT_ROOT']. '/application/third_party/Spreadsheet/Excel/Writer.php');
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

//CENTER BOLD and wraping is true
$center_bold =& $workbook->addFormat();
$center_bold->setHAlign('center');
$center_bold->setBold();  
$center_bold->setBorder(1);  
$center_bold->setTextWrap(1);
$center_bold->setFontFamily($setFamily);
$center_bold->setSize($setSize);


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
	$worksheet->setPaper(5); //(8 1/2 X 13) 
	
	$worksheet->setColumn(0,1,53);
	$worksheet->setColumn(2,3,12);
	// $worksheet->fitToPages (1,2); //this will the system do the actual paging based on the paper content and size Same as "Fit to:" in Excel
	$worksheet->setPrintScale(78); //Same as "Adjust to:" in Excel
	$worksheet->setZoom(100);
	$worksheet->setMargins_LR(0,0); //no margin for Left and Right
	$worksheet->setMargins_TB('0.5',0); //no margin for Top and Bottom
	$worksheet->setFooter ('Page &P of &N','0.15'); //show paging number
	

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
	
	 
	$worksheet->write($row, $lblCol,  'SOURCE CODE',$border);
	$worksheet->writeString($row++, $col,  $d['sv_source_code'],$boldHeader);
	
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'CARD TYPE REQUESTED BY CLIENT',$border);
	$worksheet->write($row++, $col,  $d['sv_card_request_by_client'],$boldHeader);
 
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
	$worksheet->writeString($row, $lblCol,  'NAME TO APPEAR ON THE CARD',$border);
	$worksheet->write($row++, $col,  $d['c_name_in_card'],$border);
	
	//to add page break make it sure dont use the fittopage
	//$worksheet->setHPageBreaks($row);
	

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'DATE OF BIRTH',$border);
	$worksheet->write($row++, $col,  $d['c_dob'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'GENDER',$border);
	$worksheet->write($row++, $col,  strtoupper($d['c_gender']),$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'CIVIL STATUS',$border);
	$worksheet->write($row++, $col,  $d['c_civil_status'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'NATIONALITY',$border);
	$worksheet->write($row++, $col,  $d['c_nationality'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'IF US CITIZEN (US TIN )',$border);
	$worksheet->write($row++, $col,  $d['c_us_tin'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'PLACE OF BIRTH',$border);
	$worksheet->write($row++, $col,  $d['c_place_of_birth'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'HOME PHONE NUMBER',$border);
	$worksheet->writeString($row++, $col,  $d['c_homeno'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'MOBILE NUMBER',$border);
	$worksheet->writeString($row++, $col,  $d['c_mobileno'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'EMAIL ADDRESS',$border);
	$worksheet->write($row++, $col,  $d['c_email_address'],$border);

	/**MOTHERS'info*/
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'MOTHER\'S FULL MAIDEN NAME',$yellowHighlight);
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'LASTNAME',$border);
	$worksheet->write($row++, $col,  $d['c_mother_lastname'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'FIRSTNAME',$border);
	$worksheet->write($row++, $col,  $d['c_mother_firstname'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'MIDDLE NAME',$border);
	$worksheet->write($row++, $col,  $d['c_mother_middlename'],$border);

	/*PRESENT HOME ADDRESS*/
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'PRESENT HOME ADDRESS',$yellowHighlight);
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,30);		 //make sure to set $border_txt_wrap
	$worksheet->write($row, $lblCol,  'NO. STREET,SUBDIVISION,CITY',$border);
		
	$present_address = ( $d['c_present_add1'] . ' ' . $d['c_present_add2'] . ' ' . $d['c_present_add3'] . ' ' .  $d['c_present_city'] );
	$cell_attr_obj = setLongTextAttr($present_address,$border_txt_wrap,$small_size_txt);	
	$worksheet->write($row++, $col, $present_address ,$cell_attr_obj);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'ZIP CODE',$border);
	$worksheet->write($row++, $col,  $d['c_present_zip'],$border);


	/*HOME OWNERSHIP*/
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'HOME OWNERSHIP',$yellowHighlight);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'YEARS OF STAY',$border);
	$worksheet->write($row++, $col,  $d['c_year_stay'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'MONTH OF STAY',$border);
	$worksheet->write($row++, $col,  $d['c_month_stay'],$border);

	foreach($ownership as $k=>$luDetail){
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  strtoupper($luDetail['lu_desc']),$border);
		$worksheet->write($row++, $col,  ($d['c_home_ownership'] == $luDetail['lu_code']) ? 'X' : '',$border);
	}

	addBlackCell($worksheet,$row,0,3,$border);	
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,30);		 //make sure to set $border_txt_wrap
	$worksheet->write($row, $lblCol,  'HAVE YOU STAYED IN THE USA FOR 180 DAYS IN THE LAST 3 YRS (YES/NO)',$border_small_size_txt_wrap);
	$worksheet->write($row++, $col,  isset($yesnoLU[$d['c_stayed_in_us']]) ? strtoupper($yesnoLU[$d['c_stayed_in_us']]) : '',$border);

	/*DO YOU OWN A CAR*/
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row++, $lblCol,  'DO YOU OWN A CAR',$yellowHighlight);

	foreach($yesno as $k=>$luDetail){
			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->write($row, $lblCol,  strtoupper($luDetail['lu_desc']),$border);
			$worksheet->write($row++, $col,  ($d['c_is_own_car'] == $luDetail['lu_code']) ? 'X' : '',$border);
	}

	addBlackCell($worksheet,$row,0,3,$border);	
	$how_many_cars = (empty($d['c_how_many_car'])) ? 0 : $d['c_how_many_car'];
	$worksheet->write($row, $lblCol,  'HOW MANY',$border);
	$worksheet->write($row++, $col,  $how_many_cars,$border);

	foreach($car_ownership as $k=>$luDetail){
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->write($row, $lblCol,  strtoupper($luDetail['lu_desc']),$border);
		$worksheet->write($row++, $col,  ($d['c_car_ownership'] == $luDetail['lu_code']) ? 'X' : '',$border);
	}

	//08242014
	addBlackCell($worksheet,$row,0,3,$border);	
	$no_of_dep = (empty($d['c_no_of_dep'])) ? 0 : $d['c_no_of_dep'];
	$worksheet->write($row, $lblCol,  'NO.OF DEPENDENT',$border);
	$worksheet->write($row++, $col,  $no_of_dep,$border);
	
	
	/*TIN GSIS SSS*/
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'TIN',$border);
	$worksheet->write($row++, $col,  $d['c_tin'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'SSS/GSIS',$border);
	$worksheet->write($row++, $col,  $d['c_sss_gsis'],$border); 

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'EDUCATIONAL ATTAINMENT',$border);
	$educAttain = '';
	foreach($education_attainment as $k=>$luDetail){
		if($d['c_education_attain'] == $luDetail['lu_code']){
			$educAttain =  $luDetail['lu_desc'];
			break;
		}
		
	}
	$worksheet->write($row++, $col,  $educAttain,$border);
	

	/*PERMANENT HOME ADDRESS*/
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'PERMANENT HOME ADDRESS',$yellowHighlight);
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,30);		 //make sure to set $border_txt_wrap
	$worksheet->write($row, $lblCol,  'NO. STREET,SUBDIVISION,CITY',$border);
	
	$perma_address = $d['c_perma_add1'] . ' ' . $d['c_perma_add2'] . ' ' . $d['c_perma_add3'] . ' ' .  $d['c_perma_city'];
	$cell_attr_obj = setLongTextAttr($perma_address,$border_txt_wrap,$small_size_txt);	
	$worksheet->write($row++, $col,  $perma_address ,$cell_attr_obj);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'ZIP CODE',$border);
	$worksheet->write($row++, $col,  $d['c_perma_zip'],$border);

	/*FINANCIAL STATUS*/
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'FINANCIAL STATUS',$yellowHighlight);
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'EMPLOYMENT',$border);
	$worksheet->write($row++, $col,  strtoupper($d['c_employment']),$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'NATURE OF BUSINESS',$border);
	$worksheet->write($row++, $col,  $d['c_comp_nature_bus'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'COMPANY NAME',$border);
	$worksheet->write($row++, $col,  $d['c_company_name'],$border);

	/*COMPANY ADDRESS*/
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'COMPANY ADDRESS',$yellowHighlight);
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,30);		 //make sure to set $border_txt_wrap
	$worksheet->write($row, $lblCol,  'DEPT.,FLR.,BLDG., NO.,STREET.,SUBD.,CITY',$border);
	
	$comp_address = $d['c_comp_add1'] . ' ' . $d['c_comp_add2'] . ' ' . $d['c_comp_add3'] . ' ' .  $d['c_comp_city'];
	$cell_attr_obj = setLongTextAttr($comp_address,$border_txt_wrap,$small_size_txt);	
	$worksheet->write($row++, $col,  $comp_address ,$cell_attr_obj );

	
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'ZIP CODE',$border);
	$worksheet->write($row++, $col,  $d['c_comp_zip'],$border);

	/*TOTAL NO. OF YEARS*/
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'TOTAL NO. OF YEARS',$yellowHighlight);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'YEARS OF STAY',$border);
	$worksheet->write($row++, $col,  $d['c_comp_year_stay'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'MONTHS OF STAY',$border);
	$worksheet->write($row++, $col,  $d['c_comp_month_stay'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'OFFICE PHONE NUMBER',$border);
	$worksheet->write($row++, $col,  $d['c_comp_phone'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'FAX NUMBER',$border);
	$worksheet->write($row++, $col,  $d['c_comp_fax'],$border);
  
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'OCCUPATION/POSITION',$border);
	$worksheet->write($row++, $col,  $d['c_occupation_pos'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'TOTAL GROSS ANNUAL INCOME/SOURCE OF FUND (PER ANNUM)',$border_small_size_txt_wrap);
	$worksheet->write($row++, $col,  $d['c_tgai_souce_fund'],$border);

	/*ADD PAGE BREAK HERE*/
	$worksheet->setVPageBreaks(array('E'));
	$worksheet->setHPageBreaks(array($row));
	
	/*CARD DETAILS*/
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'CARD DETAILS',$yellowHighlight);
		
	$worksheet->write($row, $lblCol,  'ISSUER',$yellowHighlight);
	$worksheet->write($row, $lblCol+1,  'CARD NO.',$yellowHighlight);
	$worksheet->write($row, $lblCol+2,  'CREDIT LIMIT',$yellowHighlight);
	$worksheet->writestring($row++, $lblCol+3,  'ISSUE DATE',$yellowHighlight);
	
	
	
	$cardCtr = 0;
	//RCBC CARD  only
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'RCBC BANKARD CREDIT CARDS',$border);
	
	if(isset($cards[$d['id']]['RCBC'])){
		foreach($cards[$d['id']]['RCBC'] as $issuer=>$card){

			$worksheet->write($row,$lblCol,$card['issuer'],$border);
			$worksheet->write($row, $col,  $card['card_no'],$border);
			$worksheet->write($row, $col+1,  $card['credit_limit'],$border);
			$worksheet->writestring($row++, $col+2,  $card['issue_date'],$border);

		}
		unset($cards[$d['id']]['RCBC']);
	}else{
		
		addBlackCell($worksheet,$row,0,3,$border);	
		
	}
		
	$row++;
	//OTHER than RCBC	
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'ISSUER',$border);
	
	addBlackCell($worksheet,$row,1,3,$border);	
	$worksheet->write($row++, $lblCol,  'OTHER CARDS',$border);
	
	$card_added = 0;
	if(isset($cards[$d['id']])){
		foreach($cards[$d['id']] as $issuers){
			
			foreach($issuers as $issuer=>$card){
				
				$worksheet->write($row,$lblCol,$card['issuer'],$border);
				$worksheet->write($row, $col,  $card['card_no'],$border);
				$worksheet->write($row, $col+1,  $card['credit_limit'],$border);
				$worksheet->writestring($row++, $col+2,  $card['issue_date'],$border);
				$card_added++;

			}
		}
	} 
	
	//ADD blank ROWS if added card is less than to 2 
	for($card_ctr=$card_added;$card_ctr<2;$card_ctr++){
		addBlackCell($worksheet,$row++,0,3,$border);	
	}
	
	// addBlackCell($worksheet,$row++,0,3,$border);	
	// addBlackCell($worksheet,$row++,0,3,$border);	
	
	/*ADD PAGE BREAK HERE*/
	// $worksheet->setVPageBreaks(array('E'));
	// $worksheet->setHPageBreaks(array($row));
	
	/*PREFERRED BILLING ADDRESS*/
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row++, $lblCol,  'PREFERRED BILLING ADDRESS',$yellowHighlight);

	foreach($billingaddress as  $k=>$luDetail){
				addBlackCell($worksheet,$row,0,3,$border);	
        $worksheet->write($row, $lblCol,  ($luDetail['lu_desc']),$border);
        $worksheet->write($row++, $col,  ($d['c_bill_add'] == $luDetail['lu_code']) ? 'X' : '',$border);
    }
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'LOCATION LANDMARK');
	$worksheet->write($row++, $col,  $d['c_landmark']);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'FULLNAME OF AUTORIZED REPRESENTATIVE(LAST,FIRST,MIDDLE)',$border_small_size_txt_wrap);
	$worksheet->write($row++, $col,  $d['c_auth_firstname'] . ' ' . $d['c_auth_middlename'] . ' ' . $d['c_auth_lastname'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'CONTACT NO.',$border);
	$worksheet->write($row++, $col,  $d['c_auth_contact_no'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'MOBILE NO.',$border);
	$worksheet->write($row++, $col,  $d['c_auth_mob_no'],$border);

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'E-STATEMENT VIA EMAIL',$border);
	$worksheet->write($row++, $col,   isset($yesnoLU[$d['c_is_e_statement']]) ? strtoupper($yesnoLU[$d['c_is_e_statement']]) : '',$border );

	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'EMAIL ADDRESS',$border);
	$worksheet->write($row++, $col,  $d['c_email_address'],$border);
	
	addBlackCell($worksheet,$row,0,3,$border);	
	$worksheet->write($row, $lblCol,  'WEB SHOPPER',$border);
	$worksheet->write($row++, $col,  isset($yesnoLU[$d['c_is_web_shopper']]) ? strtoupper($yesnoLU[$d['c_is_web_shopper']]) : '',$border);
 

	/*SUPPLEMENTARY CARDS*/

	if(isset($supple[$d['id']])){
		
		$suppleCtr = 0;
		foreach($supple[$d['id']] as $sup){
 

			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->write($row++, $lblCol,  'SUPPLEMENTARY CARDS '.($suppleCtr+1),$yellowHighlight);
			
			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->write($row, $lblCol,  'LAST',$border);
			$worksheet->write($row++, $col,  $sup['lastname'],$border);
			
			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->write($row, $lblCol,  'FIRST',$border);
			$worksheet->write($row++, $col,  $sup['middlename'],$border);

			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->write($row, $lblCol,  'MIDDLE NAME',$border);
			$worksheet->write($row++, $col,  $sup['firstname'],$border);
			
			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->write($row, $lblCol,  'DATE OF BIRTH',$border);
			$worksheet->write($row++, $col,  $sup['dob'],$border);

			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->write($row, $lblCol,  'GENDER',$border);
			$worksheet->write($row++, $col,  strtoupper($sup['gender']));

			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->write($row, $lblCol,  'RELATIONSHIP TO THE PRINCIPAL',$border);
			$worksheet->write($row++, $col,  isset($relationship[($sup['relationship'])]) ? $relationship[$sup['relationship']] : '');

			addBlackCell($worksheet,$row,0,3,$border);	
			$worksheet->write($row, $lblCol,  'PLACE OF BIRTH',$border);
			$worksheet->write($row++, $col,  $sup['place_of_birth'],$border);

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
			$worksheet->write($row, $lblCol,  'EMPLOYMENT (PRIVATE,SELF-EMPLYED,GOV\'T,RETIRED/UNEMPLOYED)',$border_small_size_txt_wrap);
			$worksheet->write($row++, $col,  isset($employment[$sup['employment']]) ? $employment[$sup['employment']] : '');

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
	* START OF STATIC WORDINGS 
	**/
		if($crm_id != 7){ //crm = 7 [lenddo]
		
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->setMerge($row, 0, $row, 3);
		$worksheet->write($row++, $lblCol,  '',$border);
		
		// Merge cells from row 0, col 0 to row 2, col 2
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->setMerge($row, 0, $row, 3);
		$worksheet->write($row++, $lblCol,  'On the Authority to Verify Information',$center_bold);
		
		
		
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->setMerge($row, 0, $row, 1);
		$worksheet->write($row, 2,  'YES',$center_bold);
		$worksheet->write($row++, 3,  'NO',$center_bold);
		
		
		addBlackCell($worksheet,$row,0,3,$border);	 
		$worksheet->setMerge($row, 0, $row, 1);
		$question = "Do you certify that the data and information provided in this call are true and correct and updated?";
		$worksheet->write($row, 	0,  $question,$border);
		$worksheet->write($row++, 	2,  'YES',$center_bold);
		
		addBlackCell($worksheet,$row,0,3,$border);
		$this->row_not_set_height[$row] = true;	
		$worksheet->setRow($row,30);		
		$worksheet->setMerge($row, 0, $row, 1);
		$question = "Do you grant authority for RCBC and RCBC Bankard to verify and inquire the information from whatever source in may consider appropriate?";
		$worksheet->write($row, 	0,  $question,$border_txt_wrap);
		$worksheet->write($row++, 	2,  'YES',$center_bold);
		
		addBlackCell($worksheet,$row,0,3,$border);	
		$this->row_not_set_height[$row] = true;
		$worksheet->setRow($row,30);		
		$worksheet->setMerge($row, 0, $row, 1);
		$question = "Do you authorize RCBC and RCBC Bankard to have access and receive information on your behalf from the credit bureaus and other financial institutions?";
		$worksheet->write($row, 0,  $question,$border_txt_wrap);
		$worksheet->write($row++, 2,  'YES',$center_bold);
		
		//SHOULD MERGE
		addBlackCell($worksheet,$row,0,3,$border);	
		$worksheet->setMerge($row, 0, $row, 3);
		$worksheet->write($row++, $lblCol,  "On Authority to Disclose:",$center_bold);
		
		
		addBlackCell($worksheet,$row,0,3,$border);
		$this->row_not_set_height[$row] = true;
		$worksheet->setRow($row,30);		
		$worksheet->setMerge($row, 0, $row, 1);
		$question = "Do you consent to the disclosure and transfer of information between and among credit institutions for any purpose under or as may be required by laws, regulations, courts or any legal processes?";
		$worksheet->write($row, 0,  $question,$border_txt_wrap);
		$worksheet->write($row++, 2,  'YES',$center_bold);
		
		addBlackCell($worksheet,$row,0,3,$border);	
		$this->row_not_set_height[$row] = true;
		$worksheet->setRow($row,63);	
		$worksheet->setMerge($row, 0, $row, 1);
		$question = "To enable us to process your application, do you give your consent for RCBC Bankard to disclose or transfer any information relating to you, including information obtained from third parties between and among RCBC branches, subsidiaries, affiliates, agents and representatives for purposes of data processing, customer surveys, products, anti money laundering monitoring and FATCA reporting, among others?";
		$worksheet->write($row, 0,  $question,$border_txt_wrap);
		$worksheet->write($row++, 2,  'YES',$center_bold);
		
		//BACKGROUDN color should gray!
		addBlackCell($worksheet,$row,0,3,$border);	 
		$worksheet->setMerge($row, 0, $row, 3);
		$worksheet->write($row++, 0,  '',$gray_bg);
		
		
		addBlackCell($worksheet,$row,0,3,$border);
		$this->row_not_set_height[$row] = true;
		$worksheet->setRow($row,30);		
		$worksheet->setMerge($row, 0, $row, 1);
		$question = "By your acceptance of the offer through this recorded call  and providing all relavant information for the processing of your application, do you confirm your agreement to the same even in the absence of your actual signature";
		$worksheet->write($row, 0,  $question,$border_txt_wrap);
		$worksheet->write($row++, 2,  'YES',$center_bold);
		
	}

		
		for($rstart=1;$rstart<=$row;$rstart++){
			if(!isset($this->row_not_set_height[$rstart])){
				$worksheet->setRow($rstart,$rowHeight);	
			}
		}
	#$col++;
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
	if(strlen($str) >= $long_txt_size){
		$cell_attr_obj	= $small_size_txt;
	}
	return $cell_attr_obj;
}
?>