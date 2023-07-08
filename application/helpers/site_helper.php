<?
if(!function_exists('testHelper'))
{
  function testHelper($value)
  {
   return htmlentities($value);
  }
}

if(!function_exists('get_err_msgs'))
{
  function get_err_msgs($ident)
  {
	$message = array(0=>'You still have locked record, please tag it first.',
					 'A'=>'You still have locked record, please tag it first.',
					 1=>'This record is locked to other user, please choose different record.',
					 'B'=>'This record is locked to other user, please choose different record.',
					 'C'=>'No more records!',
					 2=>'User already Exist.',
					 3=>"Old password doesn't match",
					 4=>"New password doesn't match",
					 5=>'Minimum length of PW is 5'
					 );
	
	if(isset($message[$ident]))
		return $message[$ident];
	else
		return "Please contact your admin err {$ident}";
   
  }
}


//time log status equivalent
if(!function_exists('time_log_equiv'))
{ 
	function time_log_equiv($timeLog)
	{
		$timeStatus = array(1=>'TIME IN',0=>'TIME OUT');
		return  $timeStatus[$timeLog];
	}
}

if(!function_exists('emailGmail'))
{ 
	function emailGmail($contacts,$attach=''){ 
		$msg = "Please check this error: <br><br>";
		
		foreach($msgs as $err)
			$msg .= $err .'<br><br>';
		
		
		define('GUSER', 'you@gmail.com'); // GMail username
		define('GPWD', 'password'); // GMail password

		$mail = new PHPMailer(true); // create a new object
		
		try {
		
			$mail->IsSMTP(); // enable SMTP
			$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465; // or 587 or 465
			$mail->IsHTML(true);// send as HTML
			$mail->Username = "@gmail.com";
			$mail->Password = "password";
			$mail->Subject = "Test"; 
			$mail->From     = "@gmail.com";
			$mail->FromName = "TEST";
			$mail->SetLanguage("en", 'includes/phpMailer/language/');

	 
			$contacts_arr = explode(',',$contacts);
				
			foreach($contacts_arr as $c){
				$mail->AddAddress($c);
			}
		   
			$mail->IsHTML(true);  // send as HTML

			if(is_array($attach)){
				foreach($attach as $file)
					$mail->addAttachment($file);
			}
			
			
			$mail->Subject  =  'Test email!';
			$mail->Body     =  str_replace("\r\n","<br>",$msg); 
			
			if(!$mail->Send())
				$sys_rep .="Mailer Error: " . $mail->ErrorInfo;
			else
				$sys_rep = "Success!";
				
			echo  $sys_rep ; 
			
		}catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		}catch (Exception $e) {
			echo $e->getMessage(); //Boring error messages from anything else!
		} 
		  
	}
}


if(!function_exists('email'))
{ 
	function email($contacts,$attach=''){ 
			$msg = "";
			
			$mail = new PHPMailer();
			$mail->IsSMTP();                                   // send via SMTP                                 
			$mail->Host     = "172.20.70.2"; // SMTP servers
			$mail->SMTPAuth = false;     // turn on SMTP authentication
			$mail->Username = "";  // SMTP username
			$mail->Password = ""; // SMTP password

			$mail->From     = "no-reply@Test.ph";
			$mail->FromName = "Test Mailer";
			
			$contacts_arr = explode(',',$contacts);
				
			foreach($contacts_arr as $c){
				$mail->AddAddress($c);
			}
		   
			$mail->IsHTML(true);  // send as HTML

			if(is_array($attach)){
				foreach($attach as $file)
					$mail->addAttachment($file);
			}
			
			$mail->Subject  =  'Test email!';
			$mail->Body     =  str_replace("\r\n","<br>",$msg); 
			
			if(!$mail->Send())
				$sys_rep .="Mailer Error: " . $mail->ErrorInfo;
			else
				$sys_rep = "Success!";
			echo  $sys_rep ;  
	}
}


//time log status equivalent
if(!function_exists('rpt_header'))
{ 
	function rpt_header($headers,$rpt_presentation)
	{
		$months = array(1=>'January');
		
		echo "<tr>";
		echo "<td></td>";
			foreach($headers as $d){
				if($rpt_presentation == 'DAY_R') //if daily
					$val = date('Md',strtotime($d . ' 00:00:00'));
				 
				if($rpt_presentation == 'MONTH_R')
				{ //if monthly
					$monthNum = $d;
					$val = date("F", mktime(0, 0, 0, $monthNum, 10));
				}
				
				if($rpt_presentation == 'YEAR_R')
					$val = $d;
					
				echo "<td>{$val}</td>";
			}
		echo "</tr>";
	}
}


//SELECT OBJECT
if(!function_exists('select'))
{ 
	function select($id,$name,$class,$value='',$def=null,$misc=null,$options=null,$with_empty=0,$view_only=0)
	{
        $selectedValue ='';
		$select =  "<select id='{$id}' name='{$name}' class='$class' {$misc}>";
		$select.= "<option value=''>--select--</option>";
        if($with_empty){
            $select.= "<option value='".EMPTY_TAG."'>".EMPTY_TAG."</option>";
        }
			
			foreach($options as $code=>$desc){

				if($value == '') //use the def
					$selected= (($def==$code) ? 'selected' : '');
				else
					$selected= (($value==$code) ? 'selected' : '');

                if($selected == 'selected')
                    $selectedValue = $desc;

				$select.="<option value='{$code}' {$selected}>{$desc}</option>";
			}
			
		$select.="</select>";
        if($view_only)
            $select = $selectedValue;

        return $select;
	}
}


//input OBJECT
if(!function_exists('input'))
{ 
	function input($id,$name,$type,$class='',$value='',$def='',$misc='',$size=20,$maxlen=50,$view_only=0)
	{
		if(!empty($value))
			$val = $value;
		else
			$val = $def;
	    if($view_only==1)
            $input = $val;
        else
		    $input = "<input type='{$type}'  id='{$id}' name='{$name}' value='{$val}' class='{$class}' {$misc} size=$size maxlength=$maxlen>";
		
		return $input;
	}
}


//textarea OBJECT
if(!function_exists('textarea'))
{ 
	function textarea($id,$name,$class='',$value='',$def='',$misc='',$rows=5,$cols=90)
	{
		if($value != '')
			$val = $value;
		else
			$val = $def;
			
		$textarea = "<textarea name='$name' id='$id' class='{$class}' rows=$rows cols=$cols {$misc}>{$val}</textarea>";

        return $textarea;
	}
}


if ( ! function_exists('img'))
{
    function img($src = '', $index_page = FALSE,$width='')
    {
        if ( ! is_array($src) )
        {
            $src = array('src' => $src);
        }

        // If there is no alt attribute defined, set it to an empty string
        if ( ! isset($src['alt']))
        {
            $src['alt'] = '';
        }

        $img = '<img';

        foreach ($src as $k=>$v)
        {

            if ($k == 'src' AND strpos($v, '://') === FALSE)
            {
                $CI =& get_instance();

                if ($index_page === TRUE)
                {
                    $img .= ' src="'.$CI->config->site_url($v).'"';
                }
                else
                {
                    $img .= ' src="'.$CI->config->slash_item('base_url').$v.'"';
                }
            }
            else
            {
                $img .= " $k=\"$v\"";
            }
        }

        if(!empty($width))
            $img .= " width={$width}px";

        $img .= '/>';

        return $img;
    }
}

if(!function_exists('render_date'))
{
	function render_date($date,$format=''){
		if(empty($date)) return $date;
		
		$dateStr = $date;
		
		if($format==1)
			$dateStr = date('M d H:i',strtotime($date));
		elseif($format==2)
			$dateStr = date('M d ',strtotime($date));
        elseif($format==3)
            $dateStr = date('M d, Y ',strtotime($date));
        elseif($format==4)
            $dateStr = date('F d, Y ',strtotime($date));
        elseif($format==5)
            $dateStr = date('F d H:i ',strtotime($date));
		else
			$dateStr = date('Y-m-d',strtotime($date));
			
		return $dateStr;
	}
}

if(!function_exists('has_access'))
{ 
	function has_access($rightID)
	{ 
		$session = new CI_Session();
		$userdata = $session->all_userdata();

		if(isset($userdata['user_type'])){
			if(isset($userdata['privs'][$rightID]) || $userdata['user_type']=='admin')
				return 1;
			else
				return 0;
		}
				
	}
}

function alert_html($alerts,$crms,$lu){
	$html = '';
	foreach($alerts as $alertKey=>$aDetails){
		$ctr=1;
		$html .= "<div style='float:right;padding-right:10px;' class='div_activator' div_list_target='div_list_{$alertKey}' >";
		$html .= "<div class='div_alert_ctr div_alert_".$alertKey."'>".count($aDetails) . ' ' . $lu['ol_alert'][$alertKey] ."  </div>";
		$html .= "<div class='div_list_hid div_list_".$alertKey."'>";
		$html .= '<table>';
		foreach($aDetails as $details){
			$aCalldate = substr($details['calldate'],0,10);
			$html .= "<tr class='tr-alert-list' calldate='{$aCalldate}' crm_id='{$crms[$details['crm_code']]['id']}'>";
			$html .= '<td>'.$ctr++.'</td>';
			$html .= '<td>'.render_date($aCalldate,4).'</td>';
			$html .= "<td>{$details['firstname']} {$details['lastname']}</td>";
			$html .= "<td>{$details['sv_code']}</td>";
			$html .= "<td>{$crms[$details['crm_code']]['name']}</td>";
			$html .= '</tr>';
		}
		$html .= '</table>';
		$html .= '</div>';
		$html .= '</div>';
		
	}

	return $html;
}

########## new functions for reporting 2020-03-14 [COVID]

	#dmc rate = dmc / contacts
	function calc_dmc_rate($dmc,$contact){
		$dmc_rate = 0;
		if($dmc){
			$dmc_rate = number_format(($dmc/$contact)*100,2);
		}
		return $dmc_rate;
	}
	
	#contact rate = contacts/total contacts (dmc + non-DMC)
	function calc_contact_rate($contact,$total_contact){
		$c_rate = 0;
		if($total_contact){
			$c_rate = number_format(($contact/$total_contact)*100,2);
		}
		return $c_rate;
	}
	
	#draw simply rate calculation  (X/Y*100)
	function draw_rate_html($column_arr, $dividen_arr, $divisor_arr){
		foreach($column_arr as $ch_campaign){
			$dividen = $dividen_arr[$ch_campaign];
			$divisor = $divisor_arr[$ch_campaign];
			if($dividen){
				$rate = calc_dmc_rate($dividen,$divisor);
			}
			echo "<td>{$rate}</td>";
		}
		
		$total_rate = calc_dmc_rate($dividen_arr['TOTAL'],$divisor_arr['TOTAL']);
		
		echo "<td>{$total_rate}</td>";
	}
	
	#draw simply TALLY per campaign
	#$target_data_arr is a callback
	function draw_total($column_arr, &$target_data_arr, $dec = 0){
		$total_per_dispo = 0;
		foreach($column_arr as $ch_campaign){
			$ctr = $target_data_arr[$ch_campaign];
			$total_per_dispo+=$ctr; 
			$target_data_arr['TOTAL'] += $ctr;
			echo '<td>'.number_format($ctr,$dec). '</td>';
		}
		
		echo '<td>'.number_format($total_per_dispo,$dec). '</td>';
	}
?>
