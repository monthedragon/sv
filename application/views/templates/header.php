<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title ?> <?=(isset($sub_title) ? ' : '. $sub_title : '')?> </title>
	<link rel='stylesheet'   type="text/css"  href='<?=base_url()?>assets/css/default.css'> 
	<link rel='stylesheet'   type="text/css"  href='<?=base_url()?>assets/css/jquery-ui.css'> 
	<link rel='stylesheet'   type="text/css"  href='<?=base_url()?>assets/css/basic.css'> 
	<link rel='stylesheet'   type="text/css"  href='<?=base_url()?>assets/css/dropdown.css'> 
	<link rel='stylesheet'   type="text/css"  href='<?=base_url()?>assets/css/dropdown.linear.css'> 
	<link rel='stylesheet'   type="text/css"  href='<?=base_url()?>assets/css/default.ultimate.css'> 
	<link rel='stylesheet'   type="text/css"  href='<?=base_url()?>assets/css/simplePagination.css'> 
	
	
	
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-ui.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-validate.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.inline.confirm.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.simplemodal.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.maskedinput.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.simplePagination.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.alphanumeric.pack.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/utils.js"></script>
	
	
</head>

<body>

<table width="100%" height="100%" align="center" bgcolor="#FFFFFF">
<tr>
<td  align="center" valign='top'>
 
<div class="content">

    <div class='div-top' style="text-align:left;">

		<?if(isset($is_logged)){?>
            <div class='div-logo'  >
                <?=img('assets/images/logo.PNG',FALSE,150)?>

            </div>
			<div class='div-top-userinfo'><?=strtoupper("$user_name ($user_type)")?></div>
		<?}?>
	
		<div class="toplink" style='clear:both'>
			<?if(isset($sub_title)){?>
				<span id='span-sub-title'><?=$sub_title?></span>
			<?}?>
			<span id='span-nav'>

				<ul id="nav" class="dropdown">
					<!--li><a href="<?=base_url()?>/security/cp/" class='a_link'><img src="./assets/images/check.bmp" alt="Change Password"></a>
						<ul>
							<li><span id='spn-time cursor-pointer'>TIME IN</span></li>
							<li><span id='spn-time cursor-pointer'>TIME OUT</span></li>
						</ul>
					</li-->
					<?php if(isset($is_logged)):?>
						<li><a   style='color:red;' href="<?php echo  base_url() . 'main/';?>"> HOME </a></li>

						<li>
							<a > Users</a>
							<ul>
								<?if(isset($privs[179])){?>
									<li><span class='cursor-pointer '><a href="<?php echo  base_url() . 'users/';?>"> user list</a>  </span></li>
								<?}?>
								<?if(isset($privs[180])){?>
									<li><span class='cursor-pointer '><a href='<?=base_url()?>users/add' id='a-user-add' class='a-modal'>add user</a></span></li>
								<?}?>
								<li><a href="<?php echo  base_url() . 'security/cp';?>"  class='a-modal'>change password</a>  </li>
							</ul>

						</li>
						<li>
							<a > MANAGE </a>
							<ul>
								
								<?if(isset($privs[184])){?>
									<li><a href='<?=base_url()?>manage/calldate' >calldate</a></li>
								<?}?>
							</ul>
						</li>
						
						<li>
							<a > REPORTS </a>
							<ul>
								
									<li><a href='<?=base_url()?>reports/sales' >SPH</a></li>
									<li><a href='<?=base_url()?>reports/recurring_leads' >RECURRING LEADS</a></li>
									<li><a href='<?=base_url()?>reports/conversion_rate' >CONVERTION RATE</a></li>
									<li><a href='<?=base_url()?>reports/outbound_report' >OUTBOUND REPORT</a></li>
									<?if($user_type == ADMIN_CODE){?>
									<li><a href='<?=base_url()?>reports/verify' >VPH</a></li>
									<?}?>
									<li><a href='<?=base_url()?>reports/los' >LOS</a></li>
							</ul>
						</li>

						<!--<li><a  href='<?=base_url()?>reports/'>Reports</a></li>-->
						<li><a href="<?php echo  base_url() . 'security/logout';?>">Logout</a> </li>
					<?php endif;?>

				</ul>

			</span>
		</div>
	</div>
	<?if(isset($privs[197])){?>
		<div style='float:right'><a id='a-faqs'>faqs</a></div>
	<?}?>
<div id='div-add-user' class='hidden'></div>
<div id='div-messages'></div>
<div id='div-edit-contact'></div>


	<script>
		$(document).ready(function(){
		
		$('#a-faqs').click(function(){
			window.open('<?=base_url()?>manage/view_faqs');
		})
		
		$('.a-manage').click(function(){
			var url = $(this).prop('href');
			$.ajax({
				url:url,
				success:function(data){
					$("#div-contact-list").html('');
					$('#div-manage').html(data);
				}
			})
			return false;
		})
		
		 $("#a-add").click(function(){
				var url = $(this).prop('href');
				
				$.ajax({
					url:'<?=base_url()?>main/add',
					success:function(data){
						$("#div-edit-contact").modal({
							containerCss: { height:350,width: 550},
							onOpen:function(dialog){  
									dialog.overlay.fadeIn('fast', function () {
										dialog.container.slideDown('slow', function () {
											dialog.data.fadeIn('slow');
										});
									});
								},
								onClose: function(dialog){
									$("#div-edit-contact").html(''); 
									$("#frm-search-contact").submit();
									dialog.container.slideUp('slow', function () { 
										$.modal.close(); // must call this! 
									}); 
							}
						});
						$("#div-edit-contact").html(data);
					}
					
				})
				
				return false;
			}) 
			
			$(".a-modal").click(function(){
				var url = $(this).prop('href');
				
				$.ajax({
					url:url,
					success:function(data){
						$("#div-add-user").html(data);
						$("#div-add-user").modal({
							containerCss: { height:300,width: 300},
							onOpen:function(dialog){  
									dialog.overlay.fadeIn('fast', function () {
										dialog.container.slideDown('slow', function () {
											dialog.data.fadeIn('slow');
										});
									});
								},
								onClose: function(dialog){
									$("#div-add-user").html(''); 
									dialog.container.slideUp('slow', function () { 
										$.modal.close(); // must call this! 
									}); 
							}
						});
					}
					
				})
				
				return false;
			})
	
			
		})
	</script>