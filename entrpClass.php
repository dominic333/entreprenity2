<?php

class entrpClass {

	function reConnectClientClassDB()
	{
		require_once '../../../../include/entrp_config.php';
		$dbm = mysql_connect (ENDB_HOST, ENDB_USER, ENDB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		mysql_select_db (ENDB_NAME) or die("Could not select database \n"); 
	
	}

	//Function to update client info pass
	//By Dominic
	function updateClientInfoPass($vof_clientid, $userPass)
	{
		$pass=md5($userPass);
      $data_q = "UPDATE client_info SET password='$pass' WHERE clientid='$vof_clientid'";
		$data_r = mysql_query ($data_q) or die("Could not Update Ad.\n");
	}

	//Function to update entrp password
	//By Dominic
	function updateEntrpPass($id, $userPass)
	{
		$pass=md5($userPass);
      $data_q = "UPDATE entrp_login SET password='$pass' WHERE clientid='$id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Ad.\n");
	}

	//Function to fetch entrp user details
	//By Dominic
	function fetchEntrpUserInfo($id)
	{
      $data_q = "SELECT EL.username,EL.email,EL.firstname,EL.lastname,EL.voff_staff,EL.vof_clientid FROM entrp_login AS EL WHERE EL.clientid='$id' AND status=1";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}	
	
	//Function to send entreprenity welcome mail
	//By Dominic
	function sendEntrpWelcomeMail($clientid)
	{
		$hostip = WEB_HOSTNAME;
		$send_inv_url = "https://$hostip/vos/cleanstyle/send_entrp_welcome_mail.php?clientid=$clientid";
	
		$r_curl = curl_init();
		curl_setopt ($r_curl, CURLOPT_URL, $send_inv_url);
		curl_exec ($r_curl);
		curl_close ($r_curl);
	}	
	
	
	//Function to set user notification preferences
	//By Dominic
	function addentrpClientNotificationPreferences($id)
	{
		$data_q = "INSERT INTO entrp_user_notification_preferences (clientid) VALUES ('$id')";
		$data_r = mysql_query ($data_q) or die("Could not Insert New relation.\n".mysql_error());
	}	
	
	//Function to add company user relation
	//By Dominic
	function addentrpCompanyUserRelation($cid, $id, $owner)
	{
		$data_q = "INSERT INTO entrp_company_members (companyid, clientid,designation) VALUES ('$cid', '$id', '$owner')";
		$data_r = mysql_query ($data_q) or die("Could not Insert New relation.\n".mysql_error());
	}

	//By Dominic
	function checkCompanyExistorNot($cid)
	{
		$data_r = mysql_query("SELECT * FROM company_profiles where id='$cid'") or die("Location Name LookUp Fail!".mysql_error());
		$fdata = mysql_fetch_array($data_r);
		return $fdata["id"];
	}
	
	//Function to get company id 
	//By Dominic
	function getEntrpCompanyId($companyUsername)
	{
		$data_r = mysql_query("SELECT * FROM company_profiles where company_username='$companyUsername'") or die("Location Name LookUp Fail!".mysql_error());
		$fdata = mysql_fetch_array($data_r);
		return $fdata["id"];
	}

	function searchEntreprenityCompany($search_string)
	{
		$data_q = "SELECT id,company_username,company_name,located_at FROM company_profiles WHERE (company_username = '$search_string' OR company_name LIKE '%$search_string%' )";
		$data_r = mysql_query ($data_q) or die (mysql_error());
		while($results = mysql_fetch_assoc($data_r)) 
		{
			$result_array[] = $results;
		}
		return $result_array;
	}	
	
	function addentrpClient($username, $email, $password, $firstname, $lastname, $voff_staff, $vof_clientid, $status, $user_type)
	{
		$temp = preg_replace('/\s+/', '', $username);
		$username= strtolower($temp);
		
		$data_q = "INSERT INTO entrp_login (username, email, password, firstname, lastname, voff_staff, vof_clientid, status, user_type) VALUES ('$username', '$email', '$password', '$firstname', '$lastname', '$voff_staff', '$vof_clientid', '$status', '$user_type')";
		$data_r = mysql_query ($data_q) or die("Could not Insert New Product.\n".mysql_error());
	
		$data_r = mysql_query("SELECT clientid FROM entrp_login ORDER BY clientid DESC LIMIT 1") or die("Location Name LookUp Fail!".mysql_error());
		$fdata = mysql_fetch_array($data_r);
		return $fdata["clientid"];
	}
	function addentrpClientProfile($clientid, $client_location, $company_name, $date_of_birth, $mobile)
	{
		$joinDate=date('Y-m-d H:i:s');
		$data_q = "INSERT INTO client_profile (clientid, client_location, company_name, date_of_birth, mobile,join_date) VALUES ('$clientid', '$client_location', '$company_name', '$date_of_birth', '$mobile','$joinDate')";
		$data_r = mysql_query ($data_q) or die("Could not Insert New Product.\n".mysql_error());
	}
	function addentrpCompanyProfile($clientid, $company_username, $company_name)
	{
		$temp = preg_replace('/\s+/', '', $company_username);
		$company_username = strtolower($temp);
		
		$data_q = "INSERT INTO company_profiles (clientid, company_username, company_name) VALUES ('$clientid', '$company_username', '$company_name')";
		$data_r = mysql_query ($data_q) or die("Could not Insert New Product.\n".mysql_error());
	}
	
	function checkClientByEmail($email)
	{
	    $data_q = "SELECT clientid FROM entrp_login WHERE email='$email'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff First Name.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	
	
	function updatevoProduct($id, $name, $desc, $price, $location)
	{
        $data_q = "UPDATE vo_products SET product_name='$name', product_desc='$desc', price='$price', location='$location' WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Ad.\n");
	}
	function getvoProductid($name)
	{
		$data_r = mysql_query("SELECT id FROM vo_products WHERE product_name = '$name'") or die("Location Name LookUp Fail!".mysql_error());
		$fdata = mysql_fetch_array($data_r);

		return $fdata["id"];

	}
	
	function addSMSLog($sid, $number, $msg, $clientid, $type)
	{
		$data_q = "INSERT INTO sms_logs (sid, number, msg, clientid, type) VALUES ('$sid', '$number', '$msg', '$clientid', '$type')";
		$data_r = mysql_query ($data_q) or die("Could not Log SMS.\n".mysql_error());
	}
	function getSMS_Log($clientid)
	{
		$data_q = "SELECT * FROM sms_logs WHERE clientid = '$clientid' ORDER BY 'date' ASC";
		$data_r = mysql_query ($data_q) or die(mysql_error());
		return $data_r;
	
	}
	function checkforexistingclientBySMS($number) 
	{
		$data_q = "SELECT * FROM sms_logs WHERE number = '$number'";
		$data_r = mysql_query ($data_q) or die(mysql_error());
		return $data_r;
	
	}
        function checkforexistingclient_infoBySMS($number) 
	{
		$data_q = "SELECT * FROM client_info WHERE pri_contact_no = '$number'";
		$data_r = mysql_query ($data_q) or die(mysql_error());
		return $data_r;
	
	}
	function checkforexistingclientByCoName($coname)
	{
        $data_q = "select clientid from client_info where coname= '$coname'";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;
	}
	function getclient_invoice($inv)
	{
		$data_q = "SELECT * FROM client_booking_log WHERE invoice_id = '$inv'";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;

	}
	function getchecklist_info($date)
	{
		$data_q = "SELECT * FROM checklist_log WHERE date = '$date'";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;

	}
		function getchecklistItemByName($name)
	{
		$data_r = mysql_query("SELECT description FROM checklist WHERE name = '$name'") or die(mysql_error());
		$fdata = mysql_fetch_array($data_r);
		return $fdata["description"];
	}
	function getpri_contact_nobyID($cid)
	{
		$data_r = mysql_query("SELECT pri_contact_no FROM client_info WHERE clientid = '$cid'") or die(mysql_error());
		$fdata = mysql_fetch_array($data_r);
		return $fdata["pri_contact_no"];
	}
	
		function getproductID()
	{
		$data_q = "SELECT * FROM products WHERE location_id IN (1,2,17,37,43,45) AND (product_name='VirtualOfficeSilver' or product_name='VirtualOfficeGold' or product_name='VirtualOfficeGoldPlus' or product_name='VirtualOfficePlatinum' or product_name='PreCallForwarding' or product_name='BasicCallAnswering' or product_name='AdvanceCallAnswering' or product_name='Call_Forwarding' or product_name='Transfer Call' or product_name='24/7 Call Answering Service' or product_name='24/7 Extended Call Forwarding' or product_name='Swift - Call Answering' or product_name='JustMailingBasic' or product_name='JustMailingAdvance' or product_name='CompleteVirtualOffice' or product_name='EnterpriseVirtualOffice' or product_name='PreBasicMailingAddress' or product_name='PreMailingPlusReceptionist' or product_name='PreDedicatedNumber' or product_name='PreMailingPlusReceptionist' or product_name='PreCallForwarding' or product_name='PreMiscUpgrade')";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;
	}
	function getAllclientwithplans()
	{
		$data_q = "SELECT * FROM client_invoices_17 WHERE invoice_status='2' AND date_paid BETWEEN '2015-01-01' AND '2015-12-31'";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;

	}
	function getclientplant($plan , $cid)
	{	
		$data_r = mysql_query("SELECT amount_due,amount_paid,description FROM client_invoices_17 WHERE client_plans_id='$plan' AND client_id='$cid' AND invoice_status='2' AND date_paid BETWEEN '2015-01-01' AND '2015-12-31'") or die(mysql_error());
		$fdata = mysql_fetch_array($data_r);
		return $fdata;

	}
	function getclientplantsolo($plan , $cid)
	{
		$data_r = mysql_query("SELECT product_id,billing_cycle FROM client_plans WHERE cp_id = '$plan' AND client_id='$cid'") or die("Location Name LookUp Fail!".mysql_error());
		$fdata = mysql_fetch_array($data_r);
		return $fdata;

	}
	function checkAllclientwithplans($planid)
	{
		$data_q = "SELECT product_id,start_date,billing_cycle FROM client_plans WHERE cp_id = $planid";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;

	}
	function getProductinfoofClient($id)
	{
		$data_r = mysql_query("SELECT product_name FROM products WHERE product_id = '$id'") or die(mysql_error());
		$fdata = mysql_fetch_array($data_r);
		return $fdata['product_name'];
	}
	function getAllchecklist_items()
	{
		$data_q = "SELECT * FROM checklist";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;

	}
		function getAllchecklist_infoByID($id)
	{
		$data_q = "SELECT * FROM checklist_log WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;

	}
		function getAllPHBranches()
	{
		$data_q = "SELECT id FROM location_info WHERE country_code='63'";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;

	}
	function getSpaceyBookings($loc)
	{
		if($loc == "" || $loc=="null"){
        $data_q = "select * from client_booking_log where status='4'";}
		else{
		$data_q = "select * from client_booking_log where status='4' AND vo_id='$loc'";}
		$data_r = mysql_query ($data_q) or die(mysql_error());
		return $data_r;
	}
	function updatevoProductImage($id, $img)
	{
        $data_q = "UPDATE vo_products SET product_img_name='$img' WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Location.\n");

	}
	function updateSpaceyBookings($id, $row, $value)
	{
        $data_q = "UPDATE client_booking_log SET `$row`='$value' WHERE book_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Bookings.\n".mysql_error());

	}
	function getColumnfacilities_booking()
	{
		
$data_q = "SELECT `COLUMN_NAME` 
		FROM `INFORMATION_SCHEMA`.`COLUMNS` 
		WHERE `TABLE_SCHEMA`='myvoff_vos' 
			AND `TABLE_NAME`='facilities_booking'
			";
	
        //$data_q = "SELECT COLUMN_NAME FROM facilities_booking WHERE book_date='' AND facility_id='' AND vo_id='' AND facilities_type='1'";
		$data_r = mysql_query ($data_q) or die("Could not get COLUMN_NAME.\n".mysql_error());
		return $data_r;
	}
	function getfacilities_booking($col, $date, $fid, $void)
	{
        $data_r = mysql_query("SELECT `$col` FROM facilities_booking WHERE book_date='$date' AND facility_id='$fid' AND location_id='$void' AND facilities_type='1'") or die("Location Name LookUp Fail!".mysql_error());
		$fdata = mysql_fetch_array($data_r);
		return $fdata;
	}
	function updateSpacey_facilities_booking($col,$colval, $date, $fid, $void)
	{
        $data_q = "UPDATE facilities_booking SET `$col`='$colval' WHERE book_date='$date' AND facility_id='$fid' AND location_id='$void' AND facilities_type='1'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Location.\n");

	}
	function getvoProduct()
	{
        $data_q = "SELECT * FROM vo_products";
		$data_r = mysql_query ($data_q) or die("Could not get item details.\n".mysql_error());
		return $data_r;
	}
	function getvoProductvRush()
	{
        $data_q = "SELECT * FROM vo_products WHERE location='vrush'";
		$data_r = mysql_query ($data_q) or die("Could not get item details.\n".mysql_error());
		return $data_r;
	}
	function getvoChangelogs()
	{
        $data_q = "SELECT * FROM announcements ORDER BY aid DESC";
		$data_r = mysql_query ($data_q) or die("Could not get announcements.\n".mysql_error());
		return $data_r;
	}
	function getvoVersion()
	{
		$data_r = mysql_query("SELECT version FROM announcements ORDER BY version DESC LIMIT 1") or die("Location Name LookUp Fail!".mysql_error());
		$fdata = mysql_fetch_array($data_r);
		return $fdata["version"];

	}
	function addvoChangelogs($version, $title, $content, $posted_by)
	{
        $data_q = "INSERT INTO announcements (version, title, content, posted_by) VALUES ('$version', '$title', '$content', '$posted_by')";
		$data_r = mysql_query ($data_q) or die("Could not Insert New Product.\n".mysql_error());
	}
	function getvoProductbyID($id)
	{
        $data_q = "SELECT * FROM vo_products WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any items.\n".mysql_error());
		return $data_r;
	}
	function deletevoProduct($id)
	{
        $data_q = "DELETE FROM vo_products WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not Update items.\n");

	}
	function addEntitlement($title, $url, $desc)
	{
        $data_q = "INSERT INTO vo_card_entitlements (entitlement_title,entitlement_url,entitlement_instructions) VALUES ('$title','$url','$desc')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into entitlements.\n".mysql_error());
	}
	function addAttStaff($staff, $base)
	{
        $data_q = "INSERT INTO attendance_operators_info (staff_id,base_void) VALUES ('$staff','$base')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into entitlements.\n".mysql_error());
	}
	function addOnlineActivation_vo($scid, $clientid, $coname, $packname, $paymethod, $package_p, $vat_p, $sdeposit, $total_amount, $currency, $vo_id, $ef_date, $en_date, $vwork_contract, $ids, $proof_of_payment_1, $proof_of_payment_2, $account_commissioning, $staff_id)
	{
        $data_q = "INSERT INTO online_activations_vo (scid, clientid, coname, packname, paymethod, package_p, vat_p, sdeposit, total_amount, currency, vo_id, ef_date, en_date, vwork_contract, ids, proof_of_payment_1, proof_of_payment_2, account_commissioning, staff_id) VALUES ('$scid', '$clientid', '$coname', '$packname', '$paymethod', '$package_p', '$vat_p', '$sdeposit', '$total_amount', '$currency', '$vo_id', '$ef_date', '$en_date', '$vwork_contract', '$ids', '$proof_of_payment_1', '$proof_of_payment_2', '$account_commissioning', '$staff_id')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into entitlements.\n".mysql_error());
	}
	function updatOnlineActivation_vo($scid, $clientid, $coname, $packname, $paymethod, $package_p, $vat_p, $sdeposit, $total_amount, $currency, $vo_id, $ef_date, $en_date, $vwork_contract, $ids, $proof_of_payment_1, $proof_of_payment_2, $account_commissioning, $staff_id)
	{
        $data_q = "UPDATE online_activations_vo SET clientid = '$clientid', coname = '$coname', packname = '$packname', paymethod = '$paymethod', package_p = '$package_p', vat_p = '$vat_p', sdeposit = '$sdeposit', total_amount = '$total_amount', currency = '$currency', vo_id = '$vo_id', ef_date = '$ef_date', en_date = '$en_date', vwork_contract = '$vwork_contract', ids = '$ids', proof_of_payment_1 = '$proof_of_payment_1', proof_of_payment_2 = '$proof_of_payment_2', account_commissioning = '$account_commissioning', staff_id = '$staff_id' WHERE scid = '$scid'";
		$data_r = mysql_query ($data_q) or die("Could not enter data into currency_rates.\n".mysql_error());
	}
	function addOnlineActivation_ci($scid, $clientid, $coname, $packname, $paymethod, $total_pro_fee, $pro_paid, $est_gov_fee, $gov_fee_paid, $package_p, $sdeposit, $total_amount, $currency, $vo_id, $free_gift, $ef_date, $en_date, $vwork_contract, $ids, $proof_of_payment1, $proof_of_payment2, $account_commissioning, $staff_id)
	{
        $data_q = "INSERT INTO online_activations_ci (scid, clientid, coname, packname, paymethod, total_pro_fee, pro_paid, est_gov_fee, gov_fee_paid, package_p, sdeposit, total_amount, currency, vo_id, free_gift, ef_date, en_date, vwork_contract, ids, proof_of_payment_1, proof_of_payment_2, account_commissioning, staff_id) VALUES ('$scid', '$clientid', '$coname', '$packname', '$paymethod', '$total_pro_fee', '$pro_paid', '$est_gov_fee', '$gov_fee_paid', '$package_p', '$sdeposit', '$total_amount', '$currency', '$vo_id', '$free_gift', '$ef_date', '$en_date', '$vwork_contract', '$ids', '$proof_of_payment1', '$proof_of_payment2', '$account_commissioning', '$staff_id')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into entitlements.\n".mysql_error());
	}
	function updatOnlineActivation_ci($scid, $clientid, $coname, $packname, $paymethod, $total_pro_fee, $pro_paid, $est_gov_fee, $gov_fee_paid, $package_p, $sdeposit, $total_amount, $currency, $vo_id, $free_gift, $ef_date, $en_date, $vwork_contract, $ids, $proof_of_payment_1, $proof_of_payment_2, $account_commissioning, $staff_id)
	{
        $data_q = "UPDATE online_activations_ci SET clientid = '$clientid', coname = '$coname', packname = '$packname', paymethod = '$paymethod', total_pro_fee = '$total_pro_fee', pro_paid = '$pro_paid', est_gov_fee = '$est_gov_fee', gov_fee_paid = '$gov_fee_paid', package_p = '$package_p', sdeposit = '$sdeposit', total_amount = '$total_amount', currency = '$currency', vo_id = '$vo_id',free_gift =  '$free_gift', ef_date = '$ef_date', en_date = '$en_date', vwork_contract = '$vwork_contract', ids = '$ids', proof_of_payment_1 = '$proof_of_payment_1', proof_of_payment_2 = '$proof_of_payment_2', account_commissioning = '$account_commissioning', staff_id = '$staff_id' WHERE scid = '$scid'";
		$data_r = mysql_query ($data_q) or die("Could not enter data into currency_rates.\n".mysql_error());
	}
	
	function addOnlineActivation_vo_mini($scid, $vwork_contract, $ids, $proof_of_payment_1, $proof_of_payment_2, $account_commissioning, $staff_id)
	{
        $data_q = "INSERT INTO online_activations_vo (scid, vwork_contract, ids, proof_of_payment_1, proof_of_payment_2, account_commissioning, staff_id) VALUES ('$scid', '$vwork_contract', '$ids', '$proof_of_payment_1', '$proof_of_payment_2', '$account_commissioning', '$staff_id')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into online_activations_vo.\n".mysql_error());
	}
	function updatOnlineActivation_vo_mini($scid, $vwork_contract, $ids, $proof_of_payment_1, $proof_of_payment_2, $account_commissioning, $staff_id)
	{
        $data_q = "UPDATE online_activations_vo SET vwork_contract = '$vwork_contract', ids = '$ids', proof_of_payment_1 = '$proof_of_payment_1', proof_of_payment_2 = '$proof_of_payment_2', account_commissioning = '$account_commissioning', staff_id = '$staff_id' WHERE scid = '$scid'";
		$data_r = mysql_query ($data_q) or die("Could not enter data into updatOnlineActivation_vo_mini.\n".mysql_error());
	}
	function addOnlineActivation_ci_mini($scid, $vwork_contract, $ids, $proof_of_payment1, $proof_of_payment2, $account_commissioning, $staff_id)
	{
        $data_q = "INSERT INTO online_activations_ci (scid, vwork_contract, ids, proof_of_payment_1, proof_of_payment_2, account_commissioning, staff_id) VALUES ('$scid', '$vwork_contract', '$ids', '$proof_of_payment1', '$proof_of_payment2', '$account_commissioning', '$staff_id')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into addOnlineActivation_ci_mini.\n".mysql_error());
	}
	function updatOnlineActivation_ci_mini($scid, $vwork_contract, $ids, $proof_of_payment_1, $proof_of_payment_2, $account_commissioning, $staff_id)
	{
        $data_q = "UPDATE online_activations_ci SET vwork_contract = '$vwork_contract', ids = '$ids', proof_of_payment_1 = '$proof_of_payment_1', proof_of_payment_2 = '$proof_of_payment_2', account_commissioning = '$account_commissioning', staff_id = '$staff_id' WHERE scid = '$scid'";
		$data_r = mysql_query ($data_q) or die("Could not enter data into updatOnlineActivation_ci_mini.\n".mysql_error());
	}
	
	
	function addcurrency_onetime($curr, $rate, $date)
	{
        $data_q = "INSERT INTO currency_rates (currency, rate, bank_update_date) VALUES ('$curr','$rate', $date)";
		$data_r = mysql_query ($data_q) or die("Could not enter data into currency_rates.\n".mysql_error());
	}
	function updatecurrency_rates($curr, $rate, $date)
	{
        $data_q = "UPDATE currency_rates SET rate = '$rate', bank_update_date = '$date' WHERE currency = '$curr'";
		$data_r = mysql_query ($data_q) or die("Could not enter data into currency_rates.\n".mysql_error());
	}
	function addAttStaff_v2($staff, $base ,$work_days,$start_time,$end_time,$timezone,$track_attendance)
	{
        $data_q = "INSERT INTO attendance_operators_info (staff_id,base_void,work_days,start_time,end_time,timezone,track_attendance) VALUES ('$staff','$base','$work_days','$start_time','$end_time','$timezone','$track_attendance')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into entitlements.\n".mysql_error());
	}
		function generateClientPwd($length = 14) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
	return $randomString;
}
   	function portal_getUserToken($id)
       	{
               	$data_r = mysql_query("SELECT token FROM client_info WHERE clientid = '$id'") or die("Get Token Failed!");
               	$fdata = mysql_fetch_array($data_r);
               	return $fdata["token"];
       	}
	 function get_onlineactivationuploads($id)
       	{
        $data_q = "SELECT * FROM online_activations_vo WHERE scid='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any sales.\n");
		return $data_r;
       	}
	function get_onmailinglistclients($voids)
       	{
        $data_q = "SELECT * FROM client_info WHERE location IN ($voids) AND on_mail_list = '1'";
		$data_r = mysql_query ($data_q) or die("Could not find any clients.\n");
		return $data_r;
       	}	
	function get_onmailinglistclientscid($cids)
       	{
        $data_q = "SELECT * FROM client_info WHERE clientid IN ($cids) AND on_mail_list = '1'";
		$data_r = mysql_query ($data_q) or die("Could not find any clients.\n");
		return $data_r;
       	}
	function get_staffemailblast($loc)
       	{
        $data_q = "SELECT * FROM operator_acl WHERE vo_id='$loc' AND send_mass_email='111'";
		$data_r = mysql_query ($data_q) or die("Could not find any sales.\n");
		return $data_r;
       	}
	function get_onlineactivationuploads_ci($id)
       	{
        $data_q = "SELECT * FROM online_activations_ci WHERE scid='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any sales.\n");
		return $data_r;
       	}		
	function portal_updateClientToken($cid, $token)
        {
               	$update_stmt = "UPDATE client_info SET token='$token' WHERE clientid = '$cid'";
               	$update_result = mysql_query ($update_stmt) or die("Could not update Client Field \n");
       	}
	function addSubBarcode($clientid, $refid, $barcode, $type)
	{
        $data_q = "INSERT INTO client_sub_barcode (client_id,ref_id,barcode,type) VALUES ('$clientid','$refid','$barcode','$type')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into client subbarcode.\n".mysql_error());
	}
	
	function addConfirmedSignup($ref_id, $sign_id, $type, $amount, $total, $currency)
	{
        $data_q = "INSERT INTO referral_accounts (ref_id,signup_id,type,sign_amount,total_amount,currency) VALUES ('$ref_id', '$sign_id', '$type', '$amount', '$total', '$currency')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into referral_accounts.\n".mysql_error());
	}
	function getProductNameByID($id)
	{
		
        $data_q = "SELECT product_name FROM products WHERE product_id='$id' ";
		$data_r = mysql_query ($data_q) or die("Could not get Staff VOID Access.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	
	function GetStaffAtdc($id,$datefrom,$dateto)
	{
        	$data_q = "SELECT * FROM attendance_log WHERE staff_id =$id AND log_date BETWEEN '$datefrom' AND '$dateto' AND check_in_type='in'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}

	function getStaffAttendanceLogout($id,$date)
        {
                $data_q = "SELECT log_time FROM attendance_log WHERE staff_id=$id AND log_date='$date' AND check_in_type='Out'";
               	$data_r = mysql_query ($data_q) or die("Could not find any Staff Logout.\n");
               	$fdata =  mysql_fetch_assoc($data_r);
		return $fdata["log_time"];
        }

	function getAttndceSet($id)
	{
        	$data_q = "SELECT * FROM attendance_operators_info WHERE staff_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}

	function getStaffAttByID($id)
	{
		
        $data_q = "SELECT staff_id FROM attendance_operators_info WHERE staff_id='$id' ";
		$data_r = mysql_query ($data_q) or die("Could not get Staff VOID Access.\n");
		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
		function getClientIDfromclient_contacts($id)
	{
		
        $data_q = "SELECT cp_id FROM client_contacts WHERE id='$id' ";
		$data_r = mysql_query ($data_q) or die("Could not get client id.\n");
		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function updateAttStaff($id,$loc)
	{
        $data_q = "UPDATE attendance_operators_info SET base_void='$loc' WHERE staff_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Location.\n");
	}
	function getClientPlanStatusByID($id)
	{
		
        $data_q = "SELECT status_desc FROM client_plans_status WHERE status_id='$id' ";
		$data_r = mysql_query ($data_q) or die("Could not get Staff VOID Access.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function getLocationVoPlan($name,$loc)
	{
		
        $data_q = "SELECT * FROM products WHERE $name AND location_id='$loc'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function getLocationVoPlanGroupedByName($loc)
	{
		
        $data_q = "SELECT * FROM products WHERE location_id='$loc' GROUP BY product_name";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function getLocationVoPlanByproductName($name)
	{
        $data_q = "SELECT * FROM products WHERE product_name = '$name'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function testfunc($loc)
	{
        $data_q = "SELECT * FROM products WHERE location_id='$loc' AND (product_name='VirtualOfficePlatinum')";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function testfunc2($id)
	{
        $data_q = "SELECT * FROM client_plans WHERE product_id IN ($id) AND status = '1' ORDER BY vo_id";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function testfunc3($id)
	{
        $data_q = "SELECT price FROM products WHERE product_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function testfunc4($id,$price)
	{
        $data_q = "UPDATE client_plans SET price='$price' WHERE cp_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not update Contacts Core table.\n");
	}
	function getClientsWithVoPlan($plan,$loc,$cdate)
	{
		
        $data_q = "SELECT * FROM client_plans WHERE product_id='$plan' AND vo_id='$loc' AND status='1' AND '$cdate' BETWEEN last_renew_date AND next_renew_date";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function getActiveClients($plan,$loc)
	{
		
        $data_q = "SELECT * FROM client_plans WHERE product_id='$plan' AND vo_id='$loc' AND status='1'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
		function SearchClientByProduct($id)
	{
        $data_q = "SELECT * FROM client_plans WHERE product_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	
		function SearchPlansByClient($id)
	{
        $data_q = "SELECT * FROM client_plans WHERE client_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
		function SearchContactsByCP($id)
	{
        $data_q = "SELECT * FROM client_contacts WHERE cp_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
		function SearchPlansByCP($id)
	{
        $data_q = "SELECT * FROM client_plans WHERE cp_id='$id' AND status='1'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
		function GetAllfromclient_plans()
	{
        $data_q = "SELECT * FROM client_plans GROUP BY client_id";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	
		function GetAllfromclient_contacts_coreby_ID($id)
	{
        $data_q = "SELECT * FROM client_contacts_core WHERE client_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;  
	}
		function addContactsDetails($cid, $max_contacts, $used_contacts){
		$data_q = "INSERT INTO client_contacts_core (client_id, max_contacts, used_contacts)VALUES ('$cid', '$max_contacts', '$used_contacts')";
		$data_r = mysql_query ($data_q) or die("Could not insert Contact.\n". mysql_error());
	}
		function updateContactsDetails_Core($cid,$used_contacts)
	{
        $data_q = "UPDATE client_contacts_core SET used_contacts='$used_contacts' WHERE client_id='$cid'";
		$data_r = mysql_query ($data_q) or die("Could not update Contacts Core table.\n");
	}
		function updateContactsDetails_Core_Maxcontacts($cid,$max_contacts)
	{
        $data_q = "UPDATE client_contacts_core SET max_contacts='$max_contacts' WHERE client_id='$cid'";
		$data_r = mysql_query ($data_q) or die("Could not update Contacts Core table.\n");
	}
		function updateContactsDetails_Core_flag($cid,$flag)
	{
        $data_q = "UPDATE client_contacts_core SET flag='$flag' WHERE client_id='$cid'";
		$data_r = mysql_query ($data_q) or die("Could not update Contacts Core table.\n");
	}
	
		function addErrorReport($url, $reporter){
		$data_q = "INSERT INTO error_reports (link, reporter)VALUES ('$url', '$reporter')";
		$data_r = mysql_query ($data_q) or die("Could not add report.\n". mysql_error());
	}
		function addstaffattendance($id, $location, $start, $end){
		$data_q = "INSERT INTO error_reports (link, reporter)VALUES ('$url', '$reporter')";
		$data_r = mysql_query ($data_q) or die("Could not add report.\n". mysql_error());
	}
	function getAllRefferals()
	{
        $data_q = "SELECT * FROM root_referral GROUP BY ref_id";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function getClientMailCollectors($id)
	{
        $data_q = "SELECT * FROM mail_collection_personnel WHERE client_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
		function getClientMailinfo($date1,$date2)
	{
        $data_q = "SELECT * FROM mail_log_v2 WHERE collect_date between '$date1' and '$date2' GROUP BY client_id";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function getClientCallInfo($date1,$date2)
	{
        $data_q = "SELECT * FROM phone_log WHERE log_date between '$date1' and '$date2' GROUP BY clientid";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function client_calls_total_dates($cid,$date1,$date2)
	{
        $data_q = "SELECT COUNT(*) AS id_count FROM phone_log WHERE clientid='$cid' AND log_date BETWEEN '$date1' AND '$date2' ORDER BY id_count ASC";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function CountClientMailcollectiontimes($id,$date1,$date2)
	{
        $data_q = "SELECT COUNT(*) AS id FROM mail_log_v2 WHERE client_id = '$id' AND collect_date between '$date1' and '$date2' ORDER BY client_id ASC";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function getAllClientsPlansWithContacts($next_renew_date)
	{
        $data_q = "SELECT * FROM client_plans WHERE max_contacts!='' AND max_contacts!='0' AND status='1' AND client_id!='0' AND client_id!='5' AND next_renew_date>'$next_renew_date'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet plans.\n");
		return $data_r;
	}
	function getClientPlansWithContacts($id,$next_renew_date)
	{
        $data_q = "SELECT * FROM client_plans WHERE client_id='$id' AND max_contacts!='' AND max_contacts!='0' AND status='1' AND client_id!='0' AND client_id!='5' AND next_renew_date>'$next_renew_date'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet plans.\n");
		return $data_r;
	}
	function getAllMailCollectors()
	{
        $data_q = "SELECT * FROM mail_collection_personnel";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	
	
		function getAllClient_plan_price($next_renew_date,$loc)
	{
        $data_q = "SELECT * FROM client_plans WHERE next_renew_date<'$next_renew_date' AND status = '1' AND vo_id='$loc' AND product_id!='' AND client_id!='11758'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
		function updateAllClient_plan_price($cp_id,$price)
	{
        $data_q = "UPDATE client_plans SET price='$price' WHERE cp_id='$cp_id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Location.\n");
	}
	function getLatestWebInv()
	{
        $data_q = "SELECT id FROM website_invoices ORDER BY id DESC LIMIT 1";
		$data_r = mysql_query ($data_q) or die("Could not get latest invoice.\n");
		return $data_r;
	}
	function getAllWebInvoices()
	{
        $data_q = "SELECT * FROM website_invoices";
		$data_r = mysql_query ($data_q) or die("Could not get all invoice.\n");
		return $data_r;
	}
	function getAllReferralSignups($status)
	{
        $data_q = "SELECT * FROM referral_signups where ref_id!='0' AND status='$status' AND paytype!='wtdr'";
		$data_r = mysql_query ($data_q) or die("Could not get all invoice.\n");
		return $data_r;
	}
	function getAllReferralWithdraws($status)
	{
        $data_q = "SELECT * FROM referral_signups where ref_id!='0' AND paytype='wtdr' AND status='$status'";
		$data_r = mysql_query ($data_q) or die("Could not get all invoice.\n");
		return $data_r;
	}
	function getReferralSignupsByID($id)
	{
        $data_q = "SELECT * FROM referral_signups where id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get all invoice.\n");
		return $data_r;
	}
	function getLatestReferralAccountByID($id)
	{
        $data_q = "SELECT * FROM referral_accounts where ref_id='$id' ORDER BY id DESC LIMIT 1";
		$data_r = mysql_query ($data_q) or die("Could not get signup.\n");
		return $data_r;
	}
	function updateReferralSignups($id, $status)
	{
        $data_q = "UPDATE referral_signups SET status='$status' WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get all invoice.\n");
		return $data_r;
	}
		function updateReferralSignupsDeny($id, $status,$comment)
	{
        $data_q = "UPDATE referral_signups SET status='$status', comment='$comment' WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get all invoice.\n");
		return $data_r;
	}
	function addWebsiteInv($website, $amount, $inv_number)
	{
        $data_q = "INSERT INTO website_invoices (website,amount,inv_number) VALUES ('$website', '$amount', '$inv_number')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into entitlements.\n".mysql_error());
	}
	function getAllCurrency()
	{
		$data_q = "SELECT currency FROM products GROUP BY currency";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function getAllLocationCurrency()
	{
		$data_q = "SELECT currency FROM location_info GROUP BY currency";
		$data_r = mysql_query ($data_q) or die("Could not find any currency.\n");
		return $data_r;
	}
	function getAllCycles()
	{
		$data_q = "SELECT billing_cycle FROM products GROUP BY billing_cycle order by cast(billing_cycle as decimal(20,6))";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function getFacilityByID($vo_id)
	{
		
		$data_q = "SELECT * FROM location_facilities_v2 WHERE vo_id = '$vo_id'";
		$data_r = mysql_query ($data_q) or die("Could not find any records.\n");
		return $data_r;
		
	}
	function getLocationByVoSite($voSite) 
	{
		
		$data_q = "SELECT * FROM location_info WHERE voffice_site_loc = '$voSite'";
		$data_r = mysql_query ($data_q) or die("Could not find any records.\n");
		return $data_r;
		
	}	
	function getLocationByCurr($curr) 
	{
		
		$data_q = "SELECT * FROM location_info WHERE currency = '$curr'";
		$data_r = mysql_query ($data_q) or die("Could not find any records.\n");
		return $data_r;
		
	}
	function getLocationByDesc($Desc) 
	{
		$data_r = mysql_query("SELECT id FROM location_info WHERE location_desc = '$Desc'") or die("Location Name LookUp Fail!");
		$fdata = mysql_fetch_array($data_r);

		return $fdata["id"];
		
		
	}
	
	function getAllRefferalsOnly()
	{
        $data_q = "SELECT * FROM referral_log GROUP BY code";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		return $data_r;
	}
	function getAllStaffRights($id)
	{
	        $data_q = "SELECT * FROM operator_acl WHERE op_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any staff.\n");
		return $data_r;
	}
	function getAllPlanRequests($stat)
	{
        $data_q = "SELECT * FROM products_req WHERE request_stat='$stat'";
		$data_r = mysql_query ($data_q) or die("Could not find any staff.\n");
		return $data_r;
	}
	function getAllPlans()
	{
        $data_q = "SELECT * FROM products";
		$data_r = mysql_query ($data_q) or die("Could not find any product.\n");
		return $data_r;
	}
	function getStaffPlanRequests($staff_id)
	{
        $data_q = "SELECT * FROM products_req WHERE req_by='$staff_id'";
		$data_r = mysql_query ($data_q) or die("Could not find any staff.\n");
		return $data_r;
	}
	function getAllPlanRequestsByID($id)
	{
        $data_q = "SELECT * FROM products_req WHERE product_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find requests.\n");
		return $data_r;
	}
	function getAllStaffRightsByLocation($id,$loc)
	{
        $data_q = "SELECT * FROM operator_acl WHERE op_id='$id' AND vo_id='$loc'";
		$data_r = mysql_query ($data_q) or die("Could not find any staff.\n");
		return $data_r;
	}
	function getAllPermissions()
	{
        $data_q = "SELECT * FROM operator_acl";
		$data_r = mysql_query ($data_q) or die("Could not find any staff.\n");
		return $data_r;
	}
	function getClientEntitlements($cardid)
	{
        $data_q = "SELECT * FROM client_card_entitlements WHERE card_id='$cardid' AND entitlement_status='1'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
	function ADDRequestPlan($product_name, $currency, $billing_cycle, $setup_fee, $price, $location_id, $default_max_contacts, $status, $meeting_room_hours, $day_office_hours, $hot_desking_hours, $flexi_office_hours, $x_dest_location_id, $welcome_mail_template, $discussion_room_hours, $location_facilities_id, $price_2, $req_by)
	{
	$data_q = "INSERT INTO products_req(product_name, currency, billing_cycle, setup_fee, price, location_id, default_max_contacts, status, meeting_room_hours, day_office_hours, hot_desking_hours, flexi_office_hours, x_dest_location_id, welcome_mail_template, discussion_room_hours, location_facilities_id, price_2, req_by) VALUES ( '$product_name', '$currency', '$billing_cycle', '$setup_fee', '$price', '$location_id', '$default_max_contacts', '$status', '$meeting_room_hours', '$day_office_hours', '$hot_desking_hours', '$flexi_office_hours', '$x_dest_location_id', '$welcome_mail_template', '$discussion_room_hours', '$location_facilities_id', '$price_2', '$req_by')";
		$data_r = mysql_query ($data_q) or die("Could not insert request.\n". mysql_error());
	}
	function ADDPlan($product_name, $currency, $billing_cycle, $setup_fee, $price, $location_id, $default_max_contacts, $status, $meeting_room_hours, $day_office_hours, $hot_desking_hours, $flexi_office_hours, $x_dest_location_id, $welcome_mail_template, $discussion_room_hours, $location_facilities_id, $price_2)
	{
	$data_q = "INSERT INTO products(product_name, currency, billing_cycle, setup_fee, price, location_id, default_max_contacts, status, meeting_room_hours, day_office_hours, hot_desking_hours, flexi_office_hours, x_dest_location_id, welcome_mail_template, discussion_room_hours, location_facilities_id, price_2) VALUES ( '$product_name', '$currency', '$billing_cycle', '$setup_fee', '$price', '$location_id', '$default_max_contacts', '$status', '$meeting_room_hours', '$day_office_hours', '$hot_desking_hours', '$flexi_office_hours', '$x_dest_location_id', '$welcome_mail_template', '$discussion_room_hours', '$location_facilities_id', '$price_2')";
		$data_r = mysql_query ($data_q) or die("Could not insert request.\n". mysql_error());
	}
	function DeActIndEntitlement($cardid,$enid)
	{
		
        $data_q = "UPDATE client_card_entitlements SET entitlement_status = '2',redeem_date=now() WHERE card_id='$cardid' AND entitlement_id='$enid'";
		$data_r = mysql_query ($data_q) or die("Could not delete any records.\n");

		return $data_r;
	}
	function DeActBatEntitlement($cardid,$enid)
	{
		
        $data_q = "UPDATE root_referral SET entitlement_status='2',redeem_date=now() WHERE ref_id='$cardid' AND entitlement_id='$enid'";
		$data_r = mysql_query ($data_q) or die("Could not delete any records.\n");

		return $data_r;
	}
	function DelBatEntitlement($cardid,$enid)
	{
		 

        $data_q = "DELETE FROM client_card_entitlements WHERE card_id='$cardid' AND entitlement_id='$enid' AND batch_promo='YES'";
		$data_r = mysql_query ($data_q) or die("Could not delete any records.\n");

		return $data_r;
	}
	function getAllEntitlements()
	{
        $data_q = "SELECT * FROM vo_card_entitlements";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function getEntitlementsByID($id)
	{
        $data_q = "SELECT * FROM vo_card_entitlements where id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
	function getBatchEntitlements($batch)
	{
        $data_q = "SELECT * FROM root_referral where ref_id = '$batch' AND entitlement_status='1'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
	function checkIndEntitlements($card_id,$id)
	{
        $data_q = "SELECT * FROM client_card_entitlements where card_id = '$card_id' AND entitlement_status='1' AND entitlement_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
	function checkBatchEntitlements($batch,$id)
	{
        $data_q = "SELECT * FROM root_referral where ref_id = '$batch' AND entitlement_status='1' AND entitlement_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
	function asignEntitlement($card, $entit)
	{
        $data_q = "INSERT INTO client_card_entitlements (card_id,entitlement_id,entitlement_status,batch_promo) VALUES ('$card','$entit','1','YES')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into entitlements.\n".mysql_error());
	}
	function asignIndEntitlement($card, $entit)
	{
        $data_q = "INSERT INTO client_card_entitlements (card_id,entitlement_id,entitlement_status,batch_promo) VALUES ('$card','$entit','1','NO')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into entitlements.\n".mysql_error());
	}
	function asignBatchEntitlement($ref, $entit)
	{
        $data_q = "INSERT INTO root_referral (ref_id,entitlement_id) VALUES ('$ref','$entit')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into entitlements.\n".mysql_error());
	}
	function addCSCase($cid, $casetype, $callernum, $staffid)
	{
		$data_q = "INSERT INTO cs_case (co_name_filter_id, case_type_id, contact_number, staff_id) VALUES ('$cid', '$casetype','$callernum', '$staffid' )";
		$data_r = mysql_query ($data_q) or die("Could not Insert New CS Case.\n");
		
	}

	function checkifDupCSCase($cid, $callernum, $staffid) 
	{
        $data_q = "SELECT * FROM cs_case WHERE co_name_filter_id='$cid' AND staff_id='$staffid' AND contact_number='$callernum' ORDER BY id DESC LIMIT 5";
		$data_r = mysql_query ($data_q) or die("Could not get Last 5 CS Case Record.\n");
		$check_dup = mysql_num_rows($data_r);
		return $check_dup;
	}

	function checkifTaggedCoName($filtercid)
	{
        $data_q = "SELECT * FROM cs_case_coname_tag WHERE client_id ='$filtercid'";
		$data_r = mysql_query ($data_q) or die("Could not get VO Country Code.\n");
		return $data_r;
	}
	function checkforexistingclient($email)
	{
        $data_q = "select clientid from client_info where email= '$email'";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;
	}
	function getSalesLeads($id)
	{
        $data_q = "select id,staff_id from sales_lead where client_id= '$id' AND sales_stage in(1 , 2 , 6)";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;
	}
	function updateSalesLeads($stage,$cid)
	{
		$update_stmt = "UPDATE sales_lead SET sales_stage='$stage' WHERE client_id = '$cid'";
        $update_result = mysql_query ($update_stmt) or die("Cannot Update Leads".mysql_error());
	}
	function getTaggedListCallType()
	{
        $data_q = "SELECT * FROM cs_case_type";
		$data_r = mysql_query ($data_q) or die("Could not get Tagged List Call Type.\n");

		return $data_r;
	}
		function SearchClientBarcode($id,$fname,$lname,$contact,$email)
	{
        $data_q = "SELECT * FROM client_barcode where clientid='$id' or email='$email' or fname='$fname' or lname='$lname' or contact='$contact'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function SearchClientinfoByID($id)
	{
        $data_q = "SELECT * FROM client_info where clientid='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
	
	function SearchClientByLocation($location)
	{
        $data_q = "SELECT * FROM client_info where location=$location AND Status = '1'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
	function SearchClientByLocationCompleteProfile($location)
	{
        $data_q = "SELECT * FROM client_info where location=$location AND complete_profile = '1'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function GetALLClients()
	{
        $data_q = "SELECT * FROM client_info";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function GetALLReferralsCurrency()
	{
        $data_q = "SELECT * FROM referral_currency";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function GetALLReferralsCurrencyByID($id)
	{
        $data_q = "SELECT * FROM referral_currency WHERE clientid = '$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function addPercents($clientid, $currency, $amount)
	{
		$data_q = "INSERT INTO referral_currency (clientid, currency, percent) VALUES ('$clientid', '$currency', '$amount')";
		$data_r = mysql_query ($data_q) or die("Could not insert number.\n". mysql_error());
	}
		function addchecklist($ch1, $ch2, $ch3, $ch4, $ch5, $ch6, $ch7, $ch8, $ch9, $ch10, $ch11, $ch12, $ch13, $ch14, $ch15, $ch16, $ch17, $ch18, $ch19, $ch20, $ch21, $ch22, $ch23, $ch24, $ch25, $ch26, $ch27, $ch28, $ch29, $staff, $loc, $images, $date ,$comments)
	{
		$data_q = "INSERT INTO checklist_log (ch1, ch2, ch3, ch4, ch5, ch6, ch7, ch8, ch9, ch10, ch11, ch12, ch13, ch14, ch15, ch16, ch17, ch18, ch19, ch20, ch21, ch22, ch23, ch24, ch25, ch26, ch27, ch28, ch29, staffid, location, images, date, comments) VALUES ('$ch1', '$ch2', '$ch3', '$ch4', '$ch5', '$ch6', '$ch7', '$ch8', '$ch9', '$ch10', '$ch11', '$ch12', '$ch13', '$ch14', '$ch15', '$ch16', '$ch17', '$ch18', '$ch19', '$ch20', '$ch21', '$ch22', '$ch23', '$ch24', '$ch25', '$ch26', '$ch27', '$ch28', '$ch29', '$staff', '$loc', '$images', '$date', '$comments')";
		$data_r = mysql_query ($data_q) or die("Could not insert checklist.\n". mysql_error());
	}
	   	function getchecklis_itemdesc()
	{
        $data_r = mysql_query("SELECT desc FROM checklist WHERE name='ch1'");
		$fdata = mysql_fetch_assoc($data_r);
		return $fdata["desc"];
    }
		function getBarcodeInfo($id)
	{
        $data_q = "SELECT * FROM location_info where id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}

        function logStaffAttendance($staffid, $clocktype, $jpgpath, $tzone)
        {
		//date_default_timezone_set($tzone);

		$logdate = date("Y-m-d",time());
		$logtime = date("H:i:s",time());
               	$data_q = "INSERT INTO attendance_log (staff_id, check_in_type, file_name, log_date, log_time) VALUES ('$staffid', '$clocktype', '$jpgpath','$logdate','$logtime')";
               	$data_r = mysql_query ($data_q) or die("Could not Log Attendance.\n". mysql_error());
	}

	function updateAttendanceField($id, $data, $field)
        {
                $fdata = mysql_real_escape_string($data);

                $update_stmt = "UPDATE attendance_log SET $field='$fdata' WHERE id = '$id'";
                $update_result = mysql_query ($update_stmt) or die("Cannot Update Attendance");
        }

	function getStaffTZ($id)
	{
               	$data_r = mysql_query("SELECT attendance_operators_info.timezone as timezone FROM operators, attendance_operators_info WHERE operators.id = attendance_operators_info.staff_id  AND operators.id = '$id'") or die("Time Zone LookUp Fail!");
                $fdata = mysql_fetch_array($data_r);

               	return $fdata["timezone"];
        }

	function checkifStaffLate($id, $checkin, $indate, $intime)
        {
                $data_r = mysql_query("SELECT id FROM attendance_log WHERE staff_id = '$id' AND check_in_type ='$checkin' AND log_date = '$indate' AND log_time <= '$intime' ORDER BY id ASC");
                $fdata = mysql_num_rows($data_r);

                return $fdata;
        }
	
	function updateAttendanceUnderTime($id,$checkin,$undertime,$log_date)
	{
	    	$data_q = "UPDATE attendance_log SET under_time='$undertime' WHERE staff_id='$id' AND check_in_type ='$checkin' AND log_date='$log_date'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Location.\n");
	}

	function getNotifyAttendancePersonnel($void)
        {
                $data_r = mysql_query("SELECT operators.email as email FROM operator_acl, operators WHERE operator_acl.op_id = operators.id AND operator_acl.attendance_notify = '111' AND operator_acl.vo_id ='$void'");
                return $data_r;
        }

	function getVObyTZ($tz)
        {
                $data_r = mysql_query("SELECT id FROM location_info WHERE time_zone = '$tz'");
                return $data_r;
        }

   	function getStaffCheckinTime($staffid, $checkdate, $checkintype)
	{
                $data_r = mysql_query("SELECT log_time FROM attendance_log WHERE log_date='$checkdate' AND staff_id = '$staffid' AND check_in_type ='$checkintype'");
                $fdata = mysql_fetch_assoc($data_r);
		
		return $fdata["log_time"];
        }

		function getLocationName($id) 
	{
		$data_r = mysql_query("SELECT location_desc FROM location_info WHERE id = '$id'") or die("Location Name LookUp Fail!");
		$fdata = mysql_fetch_array($data_r);

		return $fdata["location_desc"];
	}
		function getSalesLeadID($id)
	{
		$data_r = mysql_query("SELECT staff_id FROM sales_lead WHERE client_id = '$id'") or die("Sales Lead Name LookUp Fail!");
		$fdata = mysql_fetch_array($data_r);
		return $fdata["staff_id"];
	}
		function getAllLocationInfo()
	{
        $data_q = "SELECT * FROM location_info ORDER BY currency DESC";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function getAllMailTemplateInfo()
	{
        $data_q = "SELECT * FROM vo_welcome_template";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function getAllLocationInfoGrouped()
	{
        $data_q = "SELECT * FROM location_info group by country_code";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function getLocationByCountryCode($code)
	{
        $data_q = "SELECT * FROM location_info WHERE country_code = '$code' AND id != '34'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}


	function getCountryByCode($country_code)
	{
        $data_q = "SELECT country FROM vo_country_list WHERE country_code = '$country_code'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");
		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function RegisterBarcode($fname,$lname,$email,$contact,$location,$barcode,$clientid,$coname)
	{
        $data_q = "INSERT INTO client_barcode (fname,lname,email,contact,location,barcode,clientid,coname) VALUES ('$fname','$lname','$email','$contact','$location','$barcode','$clientid','$coname')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into barcode.\n".mysql_error());
	}
		function addReferral($id,$num,$num2)
	{
        $data_q = "INSERT INTO referral_log (code,num_of_cards,total_num_of_cards) VALUES ('$id','$num','$num2')";
		$data_r = mysql_query ($data_q) or die("Could not enter data into barcode.\n".mysql_error());
	}
		function getAllReferrals()
	{
        $data_q = "SELECT * FROM referral_log";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function getReferral($id)
	{
        $data_q = "SELECT * FROM referral_log WHERE id = '$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
	
		function getLastReferral()
	{
        $data_q = "SELECT * FROM client_barcode ORDER BY number DESC LIMIT 1";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function getBarcode($id)
	{
        $data_q = "SELECT * FROM client_barcode where clientid='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function getLastSubBarcode($id)
	{
        $data_q = "SELECT * FROM client_sub_barcode where client_id='$id' ORDER BY id DESC LIMIT 1";
		$data_r = mysql_query ($data_q) or die("Could not find any barcodes.\n");

		return $data_r;
	}
		function findexistingref($id)
	{
        $data_q = "SELECT * FROM client_sub_barcode where ref_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any barcodes.\n");

		return $data_r;
	}
		function getMailCollectorsBarcode($code)
	{
        $data_q = "SELECT * FROM client_sub_barcode where barcode='$code'";
		$data_r = mysql_query ($data_q) or die("Could not find any barcodes.\n");
		return $data_r;
	}
		function getAllSuiteDetails($branch)
	{
        $data_q = "SELECT * FROM suite_availability where branch='$branch'";
		$data_r = mysql_query ($data_q) or die("Could not find any barcodes.\n");
		return $data_r;
	}
		function getAllSuiteDetailsByID($id)
	{
        $data_q = "SELECT * FROM suite_availability where id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not find any barcodes.\n");
		return $data_r;
	}
		function updateSuiteInfo($id,$number,$date,$sprice,$cprice,$lprice)
	{
	    $data_q = "UPDATE suite_availability SET suite_number='$number', availability_date='$date', standard_price='$sprice', current_price='$cprice', low_price='$lprice' WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Location.\n");
	}
	function getBarcodeByLoc($loc)
	{
        $data_q = "SELECT * FROM client_barcode where location='$loc'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function getBarcodeByNumber($number)
	{
        $data_q = "SELECT * FROM client_barcode where number='$number'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function SearchByBarcode($barcode)
	{
        $data_q = "SELECT * FROM client_barcode where barcode='$barcode'";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}
		function GetBarcodeBooking($id)
	{
        $data_q = "SELECT * FROM client_booking_log where client_id='$id' ORDER BY book_id DESC LIMIT 1";
		$data_r = mysql_query ($data_q) or die("Could not find any clinet.\n");

		return $data_r;
	}

	function getVOCountryCode($voloc)
	{
        $data_q = "SELECT country_code FROM location_info WHERE id='$voloc'";
		$data_r = mysql_query ($data_q) or die("Could not get VO Country Code.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}

	function getVOinfoTemplate($voloc)
	{
        $data_q = "SELECT * FROM location_info WHERE id='$voloc'";
		$data_r = mysql_query ($data_q) or die("Could not get VO Info.\n");

		$fdata = mysql_fetch_array($data_r);
		return $fdata;
	}

	
	function getProcessMailStatus($statusid)
	{
        $data_q = "SELECT status FROM mail_process_status WHERE id='$statusid'";
		$data_r = mysql_query ($data_q) or die("Could not get Mail Process Status.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function addNewStaff($loginname, $password, $access_level, $email)
	{
		$data_q = "INSERT INTO operators (loginname, password, access_level, status, email) VALUES ('$loginname', '$password', '$access_level', '1', '$email')";
		$data_r = mysql_query ($data_q) or die("Could not insert number.\n". mysql_error());
	}
	function addSuiteInfo( $snumber, $date, $sprice, $cprice, $lprice, $currency, $pax, $country, $branch)
	{
		$data_q = "INSERT INTO suite_availability (suite_number, availability_date, standard_price, current_price, low_price, currency, pax, country, branch) VALUES ('$snumber', '$date', '$sprice', '$cprice', '$lprice', '$currency', '$pax', '$country', '$branch')";
		$data_r = mysql_query ($data_q) or die("Could not insert number.\n". mysql_error());
	}
	function setPermissionsStaff($op_id, $vo_id, $core, $addclient, $manageclient, $invoicing, $suspendaccount, $updateprofile, $changeClientPassword, $editfacilitieshours, $editplansettings, $updateclientstatus, $editmiscsettings, $editreceipt, $changesalesleadownership, $callqa, $number_cancel_auth, $send_mass_email, $vworld, $barcode)
	{
		$data_q = "INSERT INTO operator_acl 
		(op_id, vo_id, core, addclient, manageclient, invoicing, suspendaccount, updateprofile, changeClientPassword, editfacilitieshours, editplansettings, updateclientstatus, editmiscsettings, editreceipt, changesalesleadownership, callqa, number_cancel_auth, send_mass_email, vworld, barcode) 
		VALUES 
		('$op_id', '$vo_id', '$core', '$addclient', '$manageclient', '$invoicing', '$suspendaccount', '$updateprofile', '$changeClientPassword', '$editfacilitieshours', '$editplansettings', '$updateclientstatus', '$editmiscsettings', '$editreceipt', '$changesalesleadownership', '$callqa', '$number_cancel_auth', '$send_mass_email', '$vworld', '$barcode')";
		
		$data_r = mysql_query ($data_q) or die("Could not insert number.\n". mysql_error());
	}
	function addStaffLocationPerms($op_id, $vo_id, $core, $addclient, $manageclient, $invoicing, $suspendaccount, $updateprofile, $changeClientPassword, $editfacilitieshours, $editplansettings, $updateclientstatus, $editmiscsettings, $editreceipt, $changesalesleadownership, $callqa, $number_cancel_auth, $send_mass_email, $admin, $vworld, $barcode, $attendance_notify){
		$data_q = "INSERT INTO operator_acl (op_id, vo_id, core, addclient, manageclient, invoicing, suspendaccount, updateprofile, changeClientPassword, editfacilitieshours, editplansettings, updateclientstatus, editmiscsettings, editreceipt, changesalesleadownership, callqa, number_cancel_auth, send_mass_email, admin, vworld, barcode, attendance_notify)VALUES ('$op_id', '$vo_id', '$core', '$addclient', '$manageclient', '$invoicing', '$suspendaccount', '$updateprofile', '$changeClientPassword', '$editfacilitieshours', '$editplansettings', '$updateclientstatus', '$editmiscsettings', '$editreceipt', '$changesalesleadownership', '$callqa', '$number_cancel_auth', '$send_mass_email', '$admin', '$vworld', '$barcode', '$attendance_notify')";
		$data_r = mysql_query ($data_q) or die("Could not insert number.\n". mysql_error());
	}
		
		function updateStaffPermissions($id)
	{
	    $data_q = "UPDATE operator_acl SET vo_id='$void' WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Location.\n");
	}
		function updatePlanRequests($id,$stat,$reason)
	{
	    $data_q = "UPDATE products_req SET request_stat='$stat', reason='$reason' WHERE product_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Plan Requests.\n");
	}
		function updatePlanPrice($id,$price)
	{
	    $data_q = "UPDATE products SET price='$price' WHERE product_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Plan Requests.\n");
	}
		function updateAllStaffPermissions($op_id, $vo_id, $core, $addclient, $manageclient, $invoicing, $suspendaccount, $updateprofile, $changeClientPassword, $editfacilitieshours, $editplansettings, $updateclientstatus, $editmiscsettings, $editreceipt, $changesalesleadownership, $callqa, $number_cancel_auth, $send_mass_email, $vworld, $barcode)
	{
	    $data_q = "UPDATE operator_acl SET  core = '$core', addclient='$addclient', manageclient='$manageclient', invoicing='$invoicing', suspendaccount='$suspendaccount', updateprofile='$updateprofile', changeClientPassword='$changeClientPassword', editfacilitieshours='$editfacilitieshours' , editplansettings='$editplansettings', updateclientstatus='$updateclientstatus', editmiscsettings='$editmiscsettings' , editreceipt='$editreceipt', changesalesleadownership='$changesalesleadownership', callqa='$callqa', number_cancel_auth='$number_cancel_auth', send_mass_email='$send_mass_email', vworld='$vworld', barcode='$barcode' WHERE op_id='$op_id' AND vo_id='$vo_id'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Location.\n");
	}
	
	function getStaffAttendance($staff_id, $f_from_date, $f_to_date)
	{
		$data_q = "SELECT attendance_log.id as id, attendance_operators_info.staff_id as staffid, attendance_operators_info.work_days as work_days, attendance_operators_info.start_time as start_time, attendance_operators_info.end_time as end_time, attendance_log.log_time as log_time, attendance_log.file_name as file_name, attendance_log.under_time as under_time, attendance_log.log_date as log_date FROM attendance_log, attendance_operators_info WHERE attendance_log.staff_id = attendance_operators_info.staff_id  AND attendance_log.check_in_type = 'in' AND attendance_operators_info.track_attendance = '1' AND attendance_operators_info.staff_id = '$staff_id' AND attendance_log.log_date BETWEEN '$f_from_date' AND '$f_to_date'";

               	$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");

               	return $data_r;
	}

	function getStaffAttendanceTimeOut($staffid, $in_date)
        {
               	$data_q = "SELECT log_time FROM attendance_log WHERE staff_id='$staffid' AND log_date='$in_date' AND check_in_type='Out'";

               	$data_r = mysql_query ($data_q) or die("Could not get Staff Attendance Time Out.\n");

		$o_data = mysql_fetch_assoc($data_r);
               	return $o_data["log_time"];
       	}

	function getStaffAttendancebyDate($staff_id, $f_from_date)
	{
		$data_q = "SELECT attendance_log.id as qid, attendance_log.comments as comments, attendance_log.comments_timestamp as cstamp, attendance_operators_info.staff_id as staffid, attendance_operators_info.work_days as work_days, attendance_operators_info.start_time as start_time, attendance_operators_info.end_time as end_time, attendance_log.log_time as log_time, attendance_log.file_name as file_name, attendance_log.under_time as under_time, attendance_log.log_date as log_date FROM attendance_log, attendance_operators_info WHERE attendance_log.staff_id = attendance_operators_info.staff_id  AND attendance_log.check_in_type = 'in' AND attendance_operators_info.track_attendance = '1' AND attendance_operators_info.staff_id = '$staff_id' AND attendance_log.log_date='$f_from_date' ORDER BY id ASC";

               	$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");

               	return $data_r;
	}

	function getAllStaffAttendanceList($in_loc)
       	{
		$data_q = "SELECT staff_id FROM attendance_operators_info WHERE base_void='$in_loc' AND track_attendance = '1'";
                $data_r = mysql_query ($data_q) or die("Could not get Staff Attendance List.\n");

                return $data_r;
	}

   	function getStaffAttendanceInfo($staff_id)
        {
                $data_q = "SELECT * FROM attendance_operators_info WHERE staff_id='$staff_id'";
                $data_r = mysql_query ($data_q) or die("Could not get Staff Attendance Info.\n");

                return $data_r;
        }

	function getStaffName($staffid)
	{
	        $data_q = "SELECT loginname FROM operators WHERE id='$staffid'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function getStaffFirstName($staffid)
	{
	    $data_q = "SELECT firstname FROM operators WHERE id='$staffid'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff First Name.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function getClientFirstName($id)
	{
	    $data_q = "SELECT firstname FROM client_info WHERE clientid='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff First Name.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function getClientLastName($id)
	{
	    $data_q = "SELECT lastname FROM client_info WHERE clientid='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff First Name.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function getClientEmail($id)
	{
	    $data_q = "SELECT email FROM client_info WHERE clientid='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff First Name.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function getClientContact($id)
	{
	    $data_q = "SELECT pri_contact_no,sec_contact_no FROM client_info WHERE clientid='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff First Name.\n");

		$fdata = mysql_fetch_row($data_r);
		$data = $fdata[0];
		if($fdata[1]!="")
		$data .= " / ".$fdata[1];
		return $data;
	}
	function getStaffstatus($staffid)
	{
	        $data_q = "SELECT status FROM operators WHERE id='$staffid'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}	
	function getAllStaffs()
	{
        $data_q = "SELECT * FROM operators ORDER BY loginname ASC";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");
		return $data_r;
	}
	
		function getAllActiveStaffOrderedByLoc()
	{
        $data_q = "SELECT * FROM operators WHERE status='1' AND vo_id!='0' ORDER BY vo_id ASC";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");
		return $data_r;
	}
	
		function getAllActiveStaffs()
	{
        $data_q = "SELECT * FROM operators WHERE status='1' ORDER BY loginname ASC";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");
		return $data_r;
	}
		function getFacilitiesName($id)
	{
        $data_q = "SELECT facilities_type FROM facilities_type WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get Facility Name.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
		function getStatusName($id)
	{
        $data_q = "SELECT status_desc FROM client_status WHERE id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get Status.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	
	function getStaffProfile($staffid)
	{
        $data_q = "SELECT * FROM operators WHERE id='$staffid'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");

		$fdata = mysql_fetch_assoc($data_r);
		return $fdata;
	}
	function getStaffIDSimple($staffid)
	{
        $data_q = "SELECT * FROM operators WHERE id='$staffid'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff ID.\n");
		return $data_r;
	}
		function getAllStaffProfile()
	{
        $data_q = "SELECT * FROM operators WHERE status = '1'";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;
	}
		function filterStaffProfile($loc)
	{
        $data_q = "SELECT * FROM operators WHERE vo_id='$loc' AND status = '1'";
		$data_r = mysql_query ($data_q) or die();
		return $data_r;
	}
	
	function getStaffEmail($staffid)
	{
        $data_q = "SELECT email FROM operators WHERE id='$staffid'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");

		$fdata = mysql_fetch_assoc($data_r);
		return $fdata["email"];
	}
	
	function getMessengerTypeName($type_id)
	{
        $data_q = "SELECT messenger_type_name FROM vo_messenger_type WHERE messenger_type_id='$type_id'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Messenger Type Name.\n");

		$fdata = mysql_fetch_assoc($data_r);
		return $fdata["messenger_type_name"];
	}

	function getMessengerTypeList()
	{
        $data_q = "SELECT * FROM vo_messenger_type";
		$data_r = mysql_query ($data_q) or die("Could not get Messenger Type Name List.\n");

		return $data_r;
		
	}

	function updateStaffLocation($cid, $void)
	{
        $data_q = "UPDATE operators SET vo_id='$void' WHERE id='$cid'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Location.\n");

	}
	function updateStaffMessengerType($cid, $mtype)
	{
        $data_q = "UPDATE operators SET messenger_type='$mtype' WHERE id='$cid'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Messenger Type.\n");

	}

	function updateStaffAvatar($cid, $imagename)
	{
        $data_q = "UPDATE operators SET profile_photo_path='$imagename' WHERE id='$cid'";
		$data_r = mysql_query ($data_q) or die("Could not Update Staff Photo.\n");

	}


	function updateStaffProfileField($id, $data, $field)
	{	
		$fdata = mysql_real_escape_string($data);

		$update_stmt = "UPDATE operators SET $field='$fdata' WHERE id = '$id'";
		$update_result = mysql_query ($update_stmt) or die("Cannot Update Staff Profile");
	}

	function getStaffDashBoard($staffid)
	{
        $data_q = "SELECT dashboard_file FROM operator_group WHERE operator_id='$staffid'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Dash Board.\n");

		$fdata = mysql_fetch_assoc($data_r);
		return $fdata["dashboard_file"];
	}

	function getVOLocationwithPBX_acl($void)
	{
		//include "../include/config.php";

		$this->reConnectClientClassDB();

        $loc_check = "SELECT * FROM location_info WHERE id='$void'";
		$loc_out = mysql_query ($loc_check) or die (mysql_error());
		$loc_reult = mysql_fetch_array($loc_out);
		return $loc_reult;
	}

	function getNumberofVOLocation()
	{
        $data_q = "SELECT id FROM location_info";
		$data_r = mysql_query($data_q) or die("Could not get Number of VO Location.\n");

		$fdata = mysql_num_rows($data_r);
		return $fdata;
	}

	function getPBXHost($void)
	{
        $data_q = "SELECT pbx_host FROM location_info WHERE id='$void'";
		$data_r = mysql_query($data_q) or @die("Could not get PBX Host .\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
		
	}

	function getPBXUsername($void)
	{
        $data_q = "SELECT pbx_user FROM location_info WHERE id='$void'";
		$data_r = mysql_query($data_q) or die("Could not get PBX Username.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}

	function getPBXPassword($void)
	{
        $data_q = "SELECT pbx_pass FROM location_info WHERE id='$void'";
		$data_r = mysql_query($data_q) or die("Could not get PBX Password.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	
	function getPBXDB($void)
	{
        $data_q = "SELECT pbx_db FROM location_info WHERE id='$void'";
		$data_r = mysql_query($data_q) or die("Could not get PBX DB.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	
	function getVOName($void)
	{
        $data_q = "SELECT location_desc FROM location_info WHERE id='$void'";
		//$data_r = mysql_query($data_q) or die("Could not get VO Location Name.\n");
		$data_r = mysql_query($data_q);

		$fdata = mysql_fetch_array($data_r);
		return $fdata["location_desc"];
	}
		function getFacilName($id)
	{
        $data_q = "SELECT description FROM location_facilities_v2 WHERE id='$id'";
		$data_r = mysql_query($data_q);
		$fdata = mysql_fetch_array($data_r);
		return $fdata["description"];
	}
	
	function getVOAlias($void)
	{
        $data_q = "SELECT vo_alias FROM location_info WHERE id='$void'";
		//$data_r = mysql_query($data_q) or die("Could not get VO Location Name.\n");
		$data_r = mysql_query($data_q);

		$fdata = mysql_fetch_array($data_r);
		return $fdata["vo_alias"];
	}


	function getPBXIncomingCalls($void)
	{
		//$get_tz = $this->getTimeZone("Asia/Kuala_Lumpur");		
		//date_default_timezone_set($get_tz);

		$r_pbxhost = $this->getPBXHost($void);
		$r_pbxdb = $this->getPBXDB($void);
		$r_pbxusername = $this->getPBXUsername($void);
		$r_pbxpw = $this->getPBXPassword($void);

		//echo $r_pbxhost;
		if ($r_pbxhost != "")
		{
			//echo date("Y-m-d G:i:s", time());
			
			$pdbm = mysql_connect ($r_pbxhost, $r_pbxusername, $r_pbxpw) or die ('I cannot connect to the database because: ' . mysql_error());
			mysql_select_db ($r_pbxdb, $pdbm) or die("Could not select database \n"); 
	
			$data_q = "SELECT * FROM incominglog WHERE logtime > now( ) - INTERVAL 100 MINUTE GROUP BY logtime ORDER BY id DESC";
			$data_r = mysql_query ($data_q, $pdbm) or die("Could not get Call Log.\n");
		
			return $data_r;
		}
	}

	function getPBXDisplayDate($indate)
	{
		
		$ps_date = strtotime($indate);
		$done_date = date("d-m-Y H:i:s", $ps_date);
		
		return $done_date;
	}
	
	function getStaffExtension($staffid)
	{
        $data_q = "SELECT extension FROM operators WHERE id='$staffid'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function getCreatedStaff($name)
	{
        $data_q = "SELECT * FROM operators WHERE loginname='$name'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");
		return $data_r;
	}


	function checkStaffAccess($staffid, $void, $section, $rights )
	{
		
        $data_q = "SELECT id FROM operator_acl WHERE op_id='$staffid' AND vo_id='$void' AND $section='$rights'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff Name.\n");

		$fdata = mysql_num_rows($data_r);

		return $fdata;
	}

	function getCallLoggingStatus()
	{
        $status_r = "SELECT * FROM call_logging_status";
		$status_info = mysql_query ($status_r) or die (mysql_error());

		return $status_info;
	}

	function getTimeZone($void)
	{
		$tz_q = "SELECT time_zone FROM location_info WHERE id='$void'";
		$tz_r = mysql_query ($tz_q) or die("Could not get Time Zone.\n");
		$fdata = mysql_fetch_row($tz_r);

		$g_tz = $fdata[0];

        //$data_q = "SET time_zone = $g_tz";
		//$data_r = mysql_query ($data_q) or die("Could not set TimeZone.\n");

		return $g_tz;
	}
		function getTimeZonebycode($code)
	{
		$tz_q = "SELECT time_zone FROM location_info WHERE country_code='$code'";
		$tz_r = mysql_query ($tz_q) or die("Could not get Time Zone.\n");
		$fdata = mysql_fetch_row($tz_r);

		$g_tz = $fdata[0];

        //$data_q = "SET time_zone = $g_tz";
		//$data_r = mysql_query ($data_q) or die("Could not set TimeZone.\n");

		return $g_tz;
	}
	function getSuspendDays($vo_id)
	{
        $data_q = "SELECT suspend_day FROM location_info WHERE id='$vo_id'";
		$data_r = mysql_query ($data_q) or die("Could not get Suspend Day.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}


	function getStaffACL($vo_id, $staffid, $section)
	{
        $data_q = "SELECT $section FROM operator_acl WHERE op_id='$staffid' AND vo_id= '$vo_id'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff ACL.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	
	function getStaffVOACLList($staffid, $section)
	{
        $data_q = "SELECT vo_id FROM operator_acl WHERE op_id='$staffid' AND $section='111'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff ACL VO List.\n");

		return $data_r;
	}
		function getOperatorFromPhoneLog($refid)
	{
        $data_q = "SELECT operator FROM phone_log WHERE id='$refid'";
		$data_r = mysql_query ($data_q) or die("Could not get operator FROM phone_log.\n");
		return $data_r;
	}


	function getVOAccess($staffid)
	{
        $data_q = "SELECT vo_id FROM operator_acl WHERE op_id='$staffid'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff ACL.\n");

		//$fdata = mysql_fetch_row($data_r);
		return $data_r;
	}
	
	function getVOAccessv2($staffid, $main_section)
	{
        $data_q = "SELECT vo_id FROM operator_acl WHERE op_id='$staffid' AND $main_section='111'";
		$data_r = mysql_query ($data_q) or die("Could not get Staff ACL.\n");

		//$fdata = mysql_fetch_row($data_r);
		return $data_r;
	}

	function getAutoFollowupDay($void)
	{
        $data_q = "SELECT followup_auto_day FROM location_info WHERE id ='$void'";
		$data_r = mysql_query ($data_q) or die("Could not get followup day.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function getActivationMail($void)
	{
        $data_q = "SELECT activation_mail FROM location_info WHERE id ='$void'";
		$data_r = mysql_query ($data_q) or die("Could not get followup day.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function getSalesMail($void)
	{
        $data_q = "SELECT sales_mail FROM location_info WHERE id ='$void'";
		$data_r = mysql_query ($data_q) or die("Could not get followup day.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function checkVOAccess($staffid, $volist)
	{
		
        $data_q = "SELECT vo_id FROM operator_acl WHERE op_id='$staffid' AND vo_id ='$volist' ";
		$data_r = mysql_query ($data_q) or die("Could not get Staff VOID Access.\n");

		$fdata = mysql_fetch_row($data_r);
		return $fdata[0];
	}
	function checkVOAccessv2($staffid, $volist)
	{
		$data_q = "SELECT * FROM operator_acl WHERE op_id='$staffid' AND vo_id ='$volist'";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;

	}


	function setupvMailclientPanel($void, $cid, $staffid)
	{	
		//$fdata = mysql_real_escape_string($data);
		$update_stmt = "UPDATE vmail_client_panel SET client_id='$cid', staff_id='$staffid' WHERE vo_id = '$void'";
		$update_result = mysql_query ($update_stmt) or die("Could not update vMail Client Panel \n");
	}

	function logPhoneMsg($fid, $femail,$mt_date, $mt_time, $fmsg, $oid, $mtype, $mcallername, $mcallerconame, $mcallerno)
	{
		$data_q = "INSERT INTO phone_log (clientid, notify_via, log_date, log_time,  msg, operator, caller_no, caller_name, notify_type, caller_coname) VALUES ('$fid', '$femail','$mt_date', '$mt_time','$fmsg', '$oid', '$mcallerno', '$mcallername', '$mtype', '$mcallerconame' )";
		$data_r = mysql_query ($data_q) or die("Could not get Insert Pone Message.\n");
		
		$id = mysql_insert_id(); 
		return $id;
	}


	function adminClass ()
	{
		require_once dirname(__FILE__) . '/../../include/config.php';
		$vdbm = mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		mysql_select_db (DB_NAME) or die("Could not select database \n"); 


	}
	function getNumbers()
	{
		
        $data_q = "SELECT * FROM ipadnums where reserv_stat = '0'";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
		
	}
	function getName($email)
	{
		
        $data_q = "SELECT * FROM operators WHERE email = '$email'";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());

		//$return = mysql_fetch_assoc($data_r);
		return $data_r;
	}
	function checkNum($number)
	{
		
        $data_q = "SELECT * FROM ipadnums WHERE number = '$number' ORDER BY number ASC";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
	function updateIPadNumas($number, $sales_mail, $date, $reserv_stat, $type, $compname)
	{	
		$update_stmt = "UPDATE ipadnums SET sales_mail='$sales_mail', date='$date', reserv_stat = '$reserv_stat', type = '$type', compname = '$compname' WHERE number = '$number'";
		$update_result = mysql_query ($update_stmt) or die("Could not insert number. \n".mysql_error());
	}
	
		function showNumbers()
	{
        $data_q = "SELECT * FROM ipadnums ORDER BY number ASC";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
		function selectNumbers($type)
	{
        $data_q = "SELECT * FROM ipadnums WHERE number_type='$type' AND reserv_stat='0' ORDER BY number ASC LIMIT 20";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
	
		function getNumber($number)
	{
        $data_q = "SELECT * FROM ipadnums where number= '$number' ORDER BY number ASC";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
		function getNumberbyType($type) 
	{
        $data_q = "SELECT * FROM ipadnums where number_type='$type' ORDER BY number ASC";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
		function getNumberbyLocation($location,$type) 
	{
        $data_q = "SELECT * FROM ipadnums where location='$location' and number_type='$type' and reserv_stat = '0' ORDER BY number ASC LIMIT 20";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
		function getNumberbyLocation2($location) 
	{
        $data_q = "SELECT * FROM ipadnums where location='$location' ORDER BY number ASC";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
		function getNumberbyStat($stat) 
	{
        $data_q = "SELECT * FROM ipadnums where reserv_stat='$stat' ORDER BY number ASC";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
		function getAllNumbers() 
	{
        $data_q = "SELECT * FROM ipadnums";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
		function getCancelationAuth($id) 
	{
        $data_q = "SELECT number_cancel_auth FROM operator_acl where op_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
		function getMassMailAuth($id) 
	{
        $data_q = "SELECT send_mass_email FROM operator_acl where op_id='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
		function getAllSurvey() 
	{
        $data_q = "SELECT * FROM vo_survey";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnum table.\n". mysql_error());
		return $data_r;
	}
		function insertNumbers($number, $type,$location)
	{
		$data_q = "INSERT INTO ipadnums (number, number_type, location) VALUES ('$number', '$type', '$location')";
		$data_r = mysql_query ($data_q) or die("Could not insert number.\n". mysql_error());
	}
		function updateStatus($number,$status)
	{
		$update_stmt = "UPDATE ipadnums SET reserv_stat='$status' where number= '$number'";
		$update_result = mysql_query ($update_stmt) or die("Could not get data from ipadnum table \n".mysql_error());
	}
		function updateStatusvoffice($number,$status)
	{
		$update_stmt = "UPDATE ipadnums SET reserv_stat='$status' where number= '$number'";
		$update_result = mysql_query ($update_stmt) or die("Could not get data from ipadnum table \n".mysql_error());
	}
		function unreserveNumber($number,$date)
	{
		$update_stmt = "UPDATE ipadnums SET reserv_stat='0',date='$date' where number= '$number'";
		$update_result = mysql_query ($update_stmt) or die("Could not get data from ipadnum table \n".mysql_error());
	}
		function resrveNumber($number,$date) 
	{
		$update_stmt = "UPDATE ipadnums SET reserv_stat='1',date='$date' where number= '$number'";
		$update_result = mysql_query ($update_stmt) or die("Could not get data from ipadnum table \n".mysql_error());
	}
		function paidNumber($number,$date) 
	{
		$update_stmt = "UPDATE ipadnums SET reserv_stat='1',payment_stat='1' where number= '$number'";
		$update_result = mysql_query ($update_stmt) or die("Could not get data from ipadnum table \n".mysql_error());
	}
		function unpaidNumber($number) 
	{
		$update_stmt = "UPDATE ipadnums SET payment_stat='0' where number= '$number'";
		$update_result = mysql_query ($update_stmt) or die("Could not get data from ipadnum table \n".mysql_error());
	}
		function updateStatusType($number,$type,$status)
	{
		$update_stmt = "UPDATE ipadnums SET reserv_stat='$status', type='$type', datefield=NOW() where number= '$number'";
		$update_result = mysql_query ($update_stmt) or die("Could not get data from ipadnum table \n".mysql_error());
	}
		function deleteNumber($number)
	{
		$update_stmt = "DELETE FROM ipadnums WHERE number = '$number'";
		$update_result = mysql_query ($update_stmt) or die("Could not delete data from ipadnum table \n".mysql_error());
	}
		function deleteNull()
	{
		$update_stmt = "DELETE FROM ipadnums WHERE number = ''";
		$update_result = mysql_query ($update_stmt) or die("Could not delete data from ipadnum table \n".mysql_error());
	}
		function refreshStatusType($type,$status) 
	{
		$update_stmt = "UPDATE ipadnums SET reserv_stat='$status', type='$type' WHERE datefield<=(NOW() - INTERVAL 5 MINUTE)";
		$update_result = mysql_query ($update_stmt) or die("Could not update data with regards to timestamp from ipadnum table \n".mysql_error());
	}
		function updateAllNumbers($number,$number2,$type, $sales_mail,$compname,$number_type,$location)
	{
		$update_stmt = "UPDATE ipadnums SET number='$number2', type='$type', sales_mail='$sales_mail', compname='$compname', number_type='$number_type', location='$location' WHERE number='$number'";
		$update_result = mysql_query ($update_stmt) or die("Could not get data from ipadnum table \n".mysql_error());
	}
	
	
		function updateLanguage($clientid, $lang)
	{	
		$update_stmt = "UPDATE client_info SET greet_lang = '$lang' WHERE clientid = '$clientid'";
		$update_result = mysql_query ($update_stmt) or die("Could not update language. \n".mysql_error());
	}
		function getGreetLang($id) 
	{
        $data_q = "SELECT greet_lang FROM client_info where clientid='$id'";
		$data_r = mysql_query ($data_q) or die("Could not get data from client_info.\n". mysql_error());
		return $data_r;
	}
		function updateOldIpadNums($number) 
	{
        $data_q = "UPDATE ipadnums SET reserv_stat='0', payment_stat='0' WHERE number='$number'";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnums.\n". mysql_error());
		return $data_r;
	}
		function getOldIpadNums() 
	{
        $data_q = "SELECT * from ipadnums WHERE date<DATE_SUB(curdate(), INTERVAL 2 DAY) AND reserv_stat='1' AND payment_stat='0'";
		$data_r = mysql_query ($data_q) or die("Could not get data from ipadnums.\n". mysql_error());
		return $data_r;
	}
	
	
}

?>
