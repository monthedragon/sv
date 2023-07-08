<?
	//$worksheet->setHPageBreaks(array($row));
	
	
	//CENTER BOLD for header 1 only
	$center_bold_h1 =& $workbook->addFormat();
	$center_bold_h1->setHAlign('center');
	$center_bold_h1->setBold();  
	$center_bold_h1->setFontFamily($setFamily);
	$center_bold_h1->setSize('20');
	$center_bold_h1->setTop(1);
	$center_bold_h1->setLeft(1);
	
	
	//CENTER BOLD for header 2 only
	$center_bold_h2 =& $workbook->addFormat();
	$center_bold_h2->setHAlign('center');
	$center_bold_h2->setBold();  
	$center_bold_h2->setTextWrap(1);
	$center_bold_h2->setFontFamily($setFamily);
	$center_bold_h2->setSize('16');
	$center_bold_h2->setBottom(1);
	$center_bold_h2->setLeft(1);
	
	//BOLD big_font
	$border_bfont =& $workbook->addFormat();
	$border_bfont->setBold();  
	$border_bfont->setSize('13');
	$border_bfont->setBorder(1);  
	
	//BOLD  big_font in center
	$border_bfont_ctr =& $workbook->addFormat();
	$border_bfont_ctr->setBold();  
	$border_bfont_ctr->setSize('13');
	$border_bfont_ctr->setBorder(1);  
	$border_bfont_ctr->setHAlign('center');
	
	
	//middle align plain
	$mid_align_bold =& $workbook->addFormat();
	$mid_align_bold->setAlign('vcenter');
	$mid_align_bold->setBold();  
	
	//middle align plain left bottom border
	$mid_align_bold_bl =& $workbook->addFormat();
	$mid_align_bold_bl->setAlign('vcenter');
	$mid_align_bold_bl->setBold();  
	$mid_align_bold_bl->setLeft(1);  
	$mid_align_bold_bl->setBottom(1);  
	
	
	//middle align plain left border
	$mid_align_bold_lb =& $workbook->addFormat();
	$mid_align_bold_lb->setAlign('vcenter');
	$mid_align_bold_lb->setBold();  
	$mid_align_bold_lb->setLeft(1);  
	
	//middle align plain right border
	$mid_align_bold_r =& $workbook->addFormat();
	$mid_align_bold_r->setAlign('vcenter');
	$mid_align_bold_r->setBold();  
	$mid_align_bold_r->setRight(1);  
	$mid_align_bold_r->setTextWrap(1);
	
	//middle align plain right and top border
	$mid_align_bold_rt =& $workbook->addFormat();
	$mid_align_bold_rt->setAlign('vcenter');
	$mid_align_bold_rt->setBold();  
	$mid_align_bold_rt->setRight(1);  
	$mid_align_bold_rt->setTop(1);  
	
	//middle align w underline
	$mid_align_underline =& $workbook->addFormat();
	$mid_align_underline->setAlign('vcenter');
	$mid_align_underline->setBold(); 
	$mid_align_underline->setUnderline(1);
	
	//middle align w underline and right border
	$mid_align_underline_r =& $workbook->addFormat();
	$mid_align_underline_r->setAlign('vcenter');
	$mid_align_underline_r->setBold(); 
	$mid_align_underline_r->setUnderline(1);
	$mid_align_underline_r->setRight(1);
	
	//middle align w underline and LEFTt border
	$mid_align_underline_l =& $workbook->addFormat();
	$mid_align_underline_l->setAlign('vcenter');
	$mid_align_underline_l->setBold(); 
	$mid_align_underline_l->setUnderline(1);
	$mid_align_underline_l->setLeft(1);
	
	//middle align with bottom border
	$mid_align_b_border =& $workbook->addFormat();
	$mid_align_b_border->setAlign('vcenter');
	$mid_align_b_border->setHAlign('center');
	$mid_align_b_border->setBold(1);  
	$mid_align_b_border->setBottom(1);  
	
	//middle align with bottom and right border
	$mid_align_br_border =& $workbook->addFormat();
	$mid_align_br_border->setAlign('vcenter');
	$mid_align_br_border->setHAlign('center');
	$mid_align_br_border->setBold(1);  
	$mid_align_br_border->setBottom(1);  
	$mid_align_br_border->setRight(1);  
	
	//middle align for horizontal and vertical 
	$center_vh =& $workbook->addFormat();
	$center_vh->setAlign('vcenter');
	$center_vh->setHAlign('center');
	$center_vh->setBold();  
	$center_vh->setBorder(1);  
	
	
	$border_lb =& $workbook->addFormat();
	$border_lb->setLeft(1);  
	$border_lb->setBottom(1);  
	$border_lb->setFontFamily($setFamily);
	 	
	$border_b =& $workbook->addFormat();
	$border_b->setBottom(1);  
	$border_b->setFontFamily($setFamily);
	
	$border_l =& $workbook->addFormat();
	$border_l->setLeft(1);  
	$border_l->setFontFamily($setFamily);
	
	$center =& $workbook->addFormat();
	$center->setHAlign('center');
	$center->setBorder(1);  
	$center->setTextWrap(1);
	$center->setFontFamily($setFamily);
	$center->setSize($setSize);
	
	//SET gray BG
	$gray_bg2=& $workbook->addFormat();
	$gray_bg2->setFgColor('gray');
	$gray_bg2->setBorder(1);
	$gray_bg2->setBold(1);
	
	
	//SET gray BG
	$gray_bg_mid_align=& $workbook->addFormat();
	$gray_bg_mid_align->setFgColor('gray');
	$gray_bg_mid_align->setBorder(1);
	$gray_bg_mid_align->setBold(1);
	$gray_bg_mid_align->setAlign('vcenter');
	$gray_bg_mid_align->setHAlign('center');
	
	//BORDER left right
	$border_lr =& $workbook->addFormat();
	$border_lr->setHAlign('left');
	$border_lr->setLeft(1);  
	$border_lr->setRight(1);  

	//BORDER right
	$border_r =& $workbook->addFormat();
	$border_r->setRight(1);  

	//BORDER top left right
	$border_tlr =& $workbook->addFormat();
	$border_tlr->setTop(1);  
	$border_tlr->setLeft(1);  
	$border_tlr->setRight(1);  
	
	//BORDER bottom left right
	$border_blr =& $workbook->addFormat();
	$border_blr->setBottom(1);  
	$border_blr->setLeft(1);  
	$border_blr->setRight(1);  



	$worksheet =& $workbook->addWorksheet('BACK PAGE');
	$worksheet->hideScreenGridlines ();
	$worksheet->setInputEncoding('utf-8'); 
	$worksheet->setPaper(5); //LONG (LEGAL)
	$worksheet->setPrintScale(78); //Same as "Adjust to:" in Excel
	$worksheet->setZoom(100);
	$worksheet->setMargins_LR('0.0',0); //no margin for Left and Right
	$worksheet->setMargins_TB('0.5',0); //no margin for Top and Bottom
	
	$worksheet->setColumn(0,0,'6'); //A 
	$worksheet->setColumn(1,1,'15.71'); //B
	$worksheet->setColumn(2,2,'4.38'); //C
	$worksheet->setColumn(3,3,'11.13'); //D
	$worksheet->setColumn(4,4,'21.38'); //E
	$worksheet->setColumn(5,5,'16.88'); //F
	$worksheet->setColumn(6,6,'18.25'); //G
	$worksheet->setColumn(7,7,'32.75'); //H
	
	
	$row =0;
	####ROW#1####
	addBlackCell($worksheet,$row,0,7,$border_tlr);
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'26.25');		
	$worksheet->setMerge($row, 0, $row, 7);
	$worksheet->write($row++, 0,  "RCBC BANKARD",$center_bold_h1);
	
	####ROW#2####
	addBlackCell($worksheet,$row,0,7,$border_blr);
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'20.25');		
	$worksheet->setMerge($row, 0, $row, 7);
	$worksheet->write($row++, 0,  "2ND CARD REQUEST FORM",$center_bold_h2);
	
	####ROW#3####
	//ADD border on BOTTOM for the whole ROW
	//addBlackCell($worksheet,$row,0,7,$border);
	
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'21');		
	$worksheet->setMerge($row, 0, $row, 1);
	$worksheet->write($row, 0,  "Date of Request:",$border_lb);
	
	$worksheet->setMerge($row, 2, $row, 3);
	
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'21');		
	
	$worksheet->setMerge($row, 4, $row, 7);
	$worksheet->write($row, 4,  "REQUEST REFERENCE NO:",$border);
	$worksheet->write($row, 7,  "",$border);
	$row++;
	
	####ROW#4####
	//ADD border on BOTTOM for the whole ROW
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 3);
	$row++;
	
	####ROW#5####
	addBlackCell($worksheet,$row,0,7,$border_b);
	$worksheet->setMerge($row, 0, $row+2, 1);
	$worksheet->write($row, 0,  "DOCUMENTS SUBMITTED:",$center_bold_valign);
		
	$worksheet->setMerge($row, 2, $row, 4, $border);	
	$worksheet->write($row, 2,  "[   ]  TM INFORMATION SHEET",$border);

	$worksheet->setMerge($row, 5, $row, 6, $border);	
	$worksheet->write($row, 5,  "[   ]  ASCCEND print screens	",$border);

	$worksheet->setMerge($row, 7, $row, 7, $border);	
	$worksheet->write($row, 7,  "[  ] TU WEB RESULT",$border);

	$row++;
	
	####ROW#6####
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 2, $row, 4, $border);	
	$worksheet->write($row, 2,  "",$border);
	$worksheet->setMerge($row, 5, $row, 6, $border);	
	$worksheet->write($row, 5,  "",$border);
	$worksheet->setMerge($row, 7, $row, 7, $border);	
	$worksheet->write($row, 7,  "",$border);
	
	$row++;
	
	####ROW#7####
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 2, $row, 7, $border);	
	$worksheet->write($row, 2,  "OTHERS:",$border);
	$worksheet->write($row, 7,  "",$border);
	
	$row++;
	
	####ROW#8####
	//BACKGROUDN color should gray!
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 7, $border);	
	$worksheet->write($row, 0,  '',$gray_bg2);
	
	$row++;
	
	####ROW#9####
	addBlackCell($worksheet,$row,0,7,$border_b);
	$worksheet->setMerge($row, 0, $row, 7, $border);	
	$worksheet->write($row, 0,  'APPLICANT/CARDHOLDER INFO:',$center_bold);
	$worksheet->write($row, 7,  "",$border);
	
	$row++;
	
	####ROW#10####
	addBlackCell($worksheet,$row,0,7,$border_b);
	$worksheet->setMerge($row, 0, $row, 2, $border);	
	$worksheet->write($row, 0,  'NAME OF CARDHOLDER',$border);
	
	$full_name = $d['c_firstname'] . ' ' . $d['c_middlename'] . ' ' . $d['c_lastname'];
	$worksheet->setMerge($row, 3, $row, 7, $border);	
	$worksheet->write($row, 3,  $full_name,$center_bold);
	$worksheet->write($row, 7,  "",$border);
	
	$row++;
	
	####ROW#11####
	addBlackCell($worksheet,$row,0,7,$border_b);
	$worksheet->setMerge($row, 0, $row, 2, $border);	
	$worksheet->write($row, 0,  'OPEN DATE',$border);
	
	$worksheet->setMerge($row, 3, $row, 7, $border);	
	$worksheet->write($row, 3,  '',$center_bold);
	$worksheet->write($row, 7,  "",$border);
	
	$row++;
	
	####ROW#12####
	addBlackCell($worksheet,$row,0,7,$border_b);
	$worksheet->setMerge($row, 0, $row, 2, $border);	
	$worksheet->write($row, 0,  'BEHAVIORAL SCORE',$border);
	
	$worksheet->setMerge($row, 3, $row, 7, $border);	
	$worksheet->write($row, 3,  '',$center_bold);
	$worksheet->write($row, 7,  "",$border);
	
	$row++;
	
	####ROW#13####
	addBlackCell($worksheet,$row,0,7,$border_b);
	$worksheet->setMerge($row, 0, $row, 2, $border);	
	$worksheet->write($row, 0,  'TIN/UMID',$border);
	
	//if TIN is NA use SSS as PER TM
	$tin = $d['tin']; //'TIN (AS PER RBSC FILE)'	
	if($tin == '' || $tin == 'NA') $tin = $d['c_tin']; // 'TIN (AS PER TM)'
	if($tin == '' || $tin == 'NA') $tin = $d['c_sss_gsis']; // 'SSS (AS PER TM)'
	
	$worksheet->setMerge($row, 3, $row, 7, $border);	
	$worksheet->write($row, 3,  $tin,$center_bold);
	$worksheet->write($row, 7,  "",$border);
	
	$row++;
	
	####ROW#14####
	addBlackCell($worksheet,$row,0,7,$border_b);
	$worksheet->setMerge($row, 0, $row, 7, $border);	
	$worksheet->write($row, 0,  'BANKARD CARDS:',$boldHeader);
	$worksheet->write($row, 7,  "",$border);
	
	$row++;
	
	####ROW#15####
	addBlackCell($worksheet,$row,0,7,$border_b);
	$worksheet->write($row, 0,  '',$boldHeader);
	$worksheet->setMerge($row, 1, $row, 3, $border);	
	$worksheet->write($row, 1,  'Cardnumber',$center);
	$worksheet->write($row, 4,  'Opened Date/Expiry',$center);
	$worksheet->write($row, 5,  'Credit Limit',$center);
	$worksheet->write($row, 6,  'OB',$center);
	$worksheet->write($row, 7,  'PROFILE',$center);
	
	$row++;
	
	####ROW#16~19####
	for($i = 1; $i <= 4; $i++){
	
		addBlackCell($worksheet,$row,0,7,$border_b);
		$worksheet->write($row, 0,  $i,$center);
		$worksheet->setMerge($row, 1, $row, 3, $border);	
		$worksheet->write($row, 1,  '',$border);
		$worksheet->write($row, 4,  '',$border);
		$worksheet->write($row, 5,  '',$border);
		$worksheet->write($row, 6,  '',$border);
		$worksheet->write($row, 7,  '',$border);	
		$row++;
	}	
	
	####ROW#20####
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 7, $border);	
	$worksheet->write($row, 0,  'NON-BANKARD CARDS:',$boldHeader);
	
	$row++;
	
	
	####ROW#21~23####
	for($i = 1; $i <= 3; $i++){
	
		addBlackCell($worksheet,$row,0,7,$border_b);
		$worksheet->write($row, 0,  $i,$center);
		$worksheet->setMerge($row, 1, $row, 3, $border);	
		$worksheet->write($row, 1,  '',$border);
		$worksheet->write($row, 4,  '',$border);
		$worksheet->write($row, 5,  '',$border);
		$worksheet->write($row, 6,  '',$border);
		$worksheet->write($row, 7,  '',$border);	
		$row++;
	}	
	
	####ROW#24####
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 7, $border);	
	$worksheet->write($row, 0,  '',$center_bold);
	
	$row++;
	
	####ROW#25####
	//BACKGROUDN color should gray!
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 7, $border);	
	$worksheet->write($row, 0,  'RECOMMENDATION:',$gray_bg2);
	$row++;
	
	
	####ROW#26####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'33');	
	$worksheet->setMerge($row, 0, $row, 3);	
	$worksheet->write($row, 0,  'Card Brand based on list',$mid_align_bold_lb);
	$worksheet->write($row, 4,  $d['sv_card_request_by_client'],$mid_align_b_border);
	$worksheet->setMerge($row, 5, $row, 6);	
	$worksheet->write($row, 5,  'Credit Limit based on list', $mid_align_bold);
	$worksheet->write($row, 7,  number_format($d['2nd_card_credit_limit'],0),$mid_align_br_border);
	
	$row++;
	
	####ROW#27####
	//ADD black ROW
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'24.75');	
	addBlackCell($worksheet,$row,0,7,$border_lr);
	$worksheet->setMerge($row, 0, $row, 7);
	$row++;
	
	####ROW#28####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'24.75');	
	addBlackCell($worksheet,$row,0,7);
	$worksheet->setMerge($row, 0, $row, 7);
	$worksheet->write($row, 0,  'TOTAL AGGREGATE CL :  __________',$mid_align_bold_lb);
	$worksheet->write($row, 7,  '',$border_r);
	$row++;
	
	####ROW#29####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'24.75');	
	addBlackCell($worksheet,$row,0,7);
	
	
	
	$worksheet->setMerge($row, 0, $row, 1);
	$worksheet->write($row, 0,  'OTHER REMARKS: ',$mid_align_bold_lb);
	// $worksheet->write($row, 7,  '',$border_r);
	
	
	$worksheet->setMerge($row, 2, $row+2, 7);
	$worksheet->write($row, 2,  $last_sv_remarks,$mid_align_bold_r);
	$worksheet->write($row, 7,  '',$border_r);
	
	$row++;
	
	####ROW#30####
	//ADD black ROW
	$worksheet->write($row, 7,  '',$border_r);
	$this->row_not_set_height[$row] = true;
	// $worksheet->setRow($row,'24.75');	
	// addBlackCell($worksheet,$row,0,7,$border_lr);
	// $worksheet->setMerge($row, 0, $row, 7);
	$row++;
		
	####ROW#31####
	//ADD black ROW
	$worksheet->write($row, 7,  '',$border_r);
	$this->row_not_set_height[$row] = true;
	// $worksheet->setRow($row,'24.75');	
	// addBlackCell($worksheet,$row,0,7,$border_lr);
	// $worksheet->setMerge($row, 0, $row, 7);
	$row++;
	
	####ROW#32####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'24.75');	
	addBlackCell($worksheet,$row,0,7);
	$worksheet->setMerge($row, 0, $row, 7);
	$worksheet->write($row, 0,  'PROCESSED BY: ___________________',$mid_align_bold_lb);
	$worksheet->write($row, 7,  '',$border_r);
	$row++;
	
	####ROW#33####
	//BACKGROUDN color should gray!
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 7, $border);	
	$worksheet->write($row, 0,  '',$gray_bg2);
	$row++;
	
	
	####ROW#34####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'19.50');	
	addBlackCell($worksheet,$row,0,7);
	$worksheet->setMerge($row, 0, $row, 7);
	$worksheet->write($row, 0,  'ENDORSED BY:   ', $mid_align_bold_lb);
	$worksheet->write($row, 7,  '',$border_r);
	$row++;
	
	####ROW#35####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'31.50');	
	$worksheet->write($row, 0,  '',$border_l);
	$worksheet->setMerge($row, 6, $row, 7);
	$worksheet->write($row, 6,  '', $mid_align_underline_r);
	$worksheet->write($row, 7,  '',$border_r);
	$row++;
	
	####ROW#36####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'34.50');	
	$worksheet->write($row, 0,  '', $mid_align_underline_l);
	$worksheet->write($row, 6,  '', $mid_align_underline);
	$worksheet->write($row, 7,  '',$border_r);
	$row++;
	
	####ROW#37####
	
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'43.50');	
	$worksheet->write($row, 0,  '', $mid_align_underline_l);
	$worksheet->write($row, 6,  '', $mid_align_underline);
	$worksheet->write($row, 7,  '',$border_r);
	$row++;
		
	####ROW#38####
	//BACKGROUDN color should gray!
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 7, $border);	
	$worksheet->write($row, 0,  '',$gray_bg2);
	$row++;
	
	####ROW#39####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'15.75');	
	addBlackCell($worksheet,$row,0,7);
	$worksheet->setMerge($row, 0, $row, 7);
	$worksheet->write($row, 0,  'APPROVED BY:   ', $mid_align_bold_lb);
	$worksheet->write($row, 7,  '',$border_r);
	$row++;
	
	####ROW#40####
	//ADD black ROW
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'20.25');	
	addBlackCell($worksheet,$row,0,7,$mid_align_bold_lb);
	$worksheet->setMerge($row, 0, $row, 7);
	$worksheet->write($row, 7,  '',$border_r);
	$row++;
		
	####ROW#41####
	addBlackCell($worksheet,$row,0,7,$border);
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'20.25');		
	
	$worksheet->setMerge($row, 0, $row, 1);
	$worksheet->write($row, 0,  "CARD TYPE",$center_vh);
	$worksheet->setMerge($row, 2, $row, 3);
	$worksheet->write($row, 2,  "CREDIT LIMIT",$center_vh);
	$worksheet->write($row, 4,  "APPROVER CODE",$center_vh);
	$worksheet->setMerge($row, 5, $row, 7);
	$worksheet->write($row, 5,  "",$center_vh);
	$row++;
	
	####ROW#42####
	addBlackCell($worksheet,$row,0,7,$border);
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'20.25');		
	
	$worksheet->setMerge($row, 0, $row, 1);
	$worksheet->write($row, 0,  "");
	$worksheet->setMerge($row, 2, $row, 3);
	$worksheet->write($row, 2,  "");
	$worksheet->write($row, 4,  "");
	$worksheet->setMerge($row, 5, $row, 7);
	$worksheet->write($row, 5,  "");
	$row++;
	
	####ROW#43####
	//ADD black ROW
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 7);
	$row++;
	
	####ROW#44####
	//BACKGROUDN color should gray!
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 7, $border);	
	$worksheet->write($row, 0,  '',$gray_bg2);
	$row++;
	
	####ROW#45####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'24.75');	
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 7);
	$worksheet->write($row, 0,  'ENCODING:', $mid_align_bold_bl);
	//$worksheet->write($row, 7,  '',$border_r);
	$row++;
	
	####ROW#46####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'24.75');	
	
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 1);
	$worksheet->write($row, 0,  'CAS REFERENCE #:', $mid_align_bold_bl);
	$worksheet->write($row, 7,  '',$border_r);
	
	$worksheet->setMerge($row, 2, $row, 7);
	$worksheet->write($row, 2,  '',$border);
	$row++;
	
	####ROW#47####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'24.75');	
	
	addBlackCell($worksheet,$row,0,7);
	$worksheet->setMerge($row, 0, $row, 1);
	$worksheet->write($row, 0,  'ENCODED BY/ DATE:', $mid_align_bold_lb);
	$worksheet->write($row, 7,  '',$border_r);
	
	$worksheet->setMerge($row, 2, $row, 6);
	$worksheet->write($row, 2,  '');
	
	$worksheet->write($row, 7,  'VERIFIED BY/DATE:',$mid_align_bold_rt);
	$row++;
	
	####ROW#48####
	//BACKGROUDN color should gray!
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 7, $border);	
	$worksheet->write($row, 0,  'FORM REVEISED AS OF MAY 21, 2018-BASED ON 2ND CARD IMPLEMENTING GUIDELINES 2018',$gray_bg_mid_align);
	$row++;
	
	####ROW#49####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'36');	
	
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 4);
	$worksheet->write($row, 0,  'ACCOUNT CREDIT LIMIT(PER RECORD)', $border_bfont);
	$worksheet->setMerge($row, 5, $row, 7);
	$worksheet->write($row, 5,  number_format($d['ava_cred_limit'],0),$border_bfont_ctr);
	$row++;
	
	####ROW#50####
	$this->row_not_set_height[$row] = true;
	$worksheet->setRow($row,'36');	
	
	addBlackCell($worksheet,$row,0,7,$border);
	$worksheet->setMerge($row, 0, $row, 4);
	$worksheet->write($row, 0,  'CUSTOMER CREDIT LIMIT(PER RECORD)', $border_bfont);
	$worksheet->setMerge($row, 5, $row, 7);
	$worksheet->write($row, 5,  number_format($d['credit_limit'],0),$border_bfont_ctr);
	$row++;
	
	
	// if(!empty($last_sv_remarks)){
		// $row++;		
		// addBlackCell($worksheet,$row,0,7,$border);
		// $worksheet->setMerge($row, 0, $row, 7);
		// $worksheet->write($row, 0, $last_sv_remarks,$no_border_txt_wrap);
	// }
	
	
?>