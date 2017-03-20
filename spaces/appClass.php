<?php	
class appClass {	
	function checkUserLogin($email,$pwd)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT clientid FROM client_info WHERE email='$email' AND password=md5('$pwd')") or die("Checking User Login Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata["clientid"];
		mysqli_close($bdbm);	
	}
	
	function CheckPlanReloadStat($cp_id,$cid)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT product_id FROM client_plans WHERE cp_id='$cp_id' AND client_id='$cid'") or die("Checking User Login Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		$product_id = $fdata["product_id"];
		$data_r = mysqli_query($bdbm,"SELECT reload_hours FROM products WHERE product_id='$product_id'") or die("Checking User Login Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata["reload_hours"];
		mysqli_close($bdbm);	
	}

	function checkUseremail($email)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT clientid FROM client_info WHERE email='$email'") or die("Checking User Login Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata["clientid"];
		mysqli_close($bdbm);	
	}
	
	function updateClientToken($id, $token)
	{
		include "db_conn.php";		
		$data_q = "UPDATE client_info SET token='$token' WHERE clientid='$id'";
		$data_r = mysqli_query ($bdbm,$data_q) or die("Update Token Failed. Error:\n". mysqli_error($bdbm));
		mysqli_close($bdbm);
	}
	
	function getClientToken($id, $token)
	{
		include "db_conn.php";		
		$data_r = mysqli_query($bdbm,"SELECT clientid FROM client_info WHERE clientid='$id' AND token='$token'") or die("Checking User Details Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata["clientid"];
		mysqli_close($bdbm);	
	}
	
	
	function checkforclientbookings($date,$loc,$ftype)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"select * from facilities_booking where book_date='$date' AND location_id='$loc' AND facility_id='$ftype'") or die("Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}

	
	function updatefacilities_booking($facilitytype, $location_id, $facility_id, $book_date)
	{
		include "db_conn.php";
		$data_q = "INSERT INTO facilities_booking (facilities_type, location_id, facility_id, book_date) VALUES ('$facilitytype', '$location_id', '$facility_id', '$book_date')"; 
		$data_r = mysqli_query ($bdbm,$data_q) or die("Update facilities_bookings Failed.\n" . mysqli_error($bdbm));
		mysqli_close($bdbm);
	}
	
	
	function updateclient_booking_log($client_id, $book_date, $facilities_type, $book_pax, $book_drink, $book_addon, $book_start_time, $book_hours_slots, $status, $vo_id, $facility_id, $invoice_id, $staff_id, $booking_info)
	{
		include "db_conn.php";
		$data_q = "INSERT INTO client_booking_log (client_id, book_date, facilities_type, book_pax, book_drink, book_addon, book_start_time, book_hours_slots, status, vo_id, facility_id, invoice_id, staff_id, booking_info) VALUES ('$client_id', '$book_date', '$facilities_type', '$book_pax', '$book_drink', '$book_addon', '$book_start_time', '$book_hours_slots', '$status', '$vo_id', '$facility_id', '$invoice_id', '$staff_id', '$booking_info')"; 
		$data_r = mysqli_query ($bdbm,$data_q) or die("Update client_booking_log Failed.\n");
		return mysqli_insert_id($bdbm);
		mysqli_close($bdbm);

	}
	
	function updateSpaceyFacilitiesBooking($id,$timeslot,$facil_type, $location_id,$facility_id,$book_date)
	{
		include "db_conn.php";
	    $data_q = "UPDATE facilities_booking SET `$timeslot`='$id' WHERE facilities_type='$facil_type' AND location_id='$location_id' AND book_date='$book_date' AND facility_id='$facility_id'";
		$data_r = mysqli_query($bdbm,$data_q) or die("Could not Update Facilities Booking.\n".mysqli_error($bdbm));
		mysqli_close($bdbm);
	}
	
	function updateClientFacilityCore($cid, $cp_id, $time, $book_id, $column, $changetype, $book_hours_slots)
	{
		include "db_conn.php";
		$data_q = "UPDATE client_facilities_core SET `$column`='$time' WHERE cp_id='$cp_id' AND client_id='$cid'";
		$data_r = mysqli_query ($bdbm,$data_q) or die("Update client_facilities_core Failed. Error:\n". mysqli_error($bdbm));
		
		$data_q = "INSERT INTO client_hours_log (client_id, book_id, cp_id, facility_col_name, change_type, change_hours) VALUES ('$cid', '$book_id', '$cp_id', '$column', '$changetype', '$book_hours_slots')"; 
		$data_r = mysqli_query ($bdbm,$data_q) or die("Update client_hours_log Failed.\n". mysqli_error($bdbm));
		
		mysqli_close($bdbm);
	}
	
	function updateClientFacilityCoreLimit($cid, $cp_id, $time, $book_id, $column, $column_limit, $changetype, $book_hours_slots)
	{
		include "db_conn.php";		
		$data_q = "UPDATE client_facilities_core SET `$column`='$time',`$column_limit`='$time' WHERE cp_id='$cp_id' AND client_id='$cid'";
		$data_r = mysqli_query ($bdbm,$data_q) or die("Update client_facilities_core Failed. Error:\n". mysqli_error($bdbm));
		
		$data_q = "INSERT INTO client_hours_log (client_id, book_id, cp_id, facility_col_name, change_type, change_hours) VALUES ('$cid', '$book_id', '$cp_id', '$column', '$changetype', '$book_hours_slots')"; 
		$data_r = mysqli_query ($bdbm,$data_q) or die("Update client_hours_log Failed.\n". mysqli_error($bdbm));
		mysqli_close($bdbm);
	}
	
	function CheckforClientBookingLogs($cid)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"select * from client_booking_log where client_id='$cid' AND status ='1' ORDER BY book_date DESC") or die("Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}
	
	function GetClientBookingLogs($bid)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"select * from client_booking_log where book_id='$bid' AND status ='1'") or die("Grab Main Page Co Name Fail!".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata;
		mysqli_close($bdbm);

	}
	
	function GetClientBookingRefundDetails($cid, $bid, $type)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"select * from client_hours_log where book_id='$bid' AND client_id ='$cid' AND change_type='$type'") or die("Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);

	}
	function updateClientBookingStatus($bid)
	{
		include "db_conn.php";		
		$data_q = "UPDATE client_booking_log SET status='2' WHERE book_id='$bid'";
		$data_r = mysqli_query ($bdbm,$data_q) or die("Update Token Failed. Error:\n". mysqli_error($bdbm));
		mysqli_close($bdbm);
	}

	function getClientPlan($cid)
	{
		include "db_conn.php";		
		$data_r = mysqli_query($bdbm,"SELECT cp_id,meeting_room_hours_left FROM client_facilities_core WHERE client_id='$cid' AND status='1' ORDER BY id DESC LIMIT 1") or die("Checking User Details Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata;
		mysqli_close($bdbm);	
	}


	function updateClientPassword($id, $token, $pwd)
	{
		include "db_conn.php";		
		$data_q = "UPDATE client_info SET password=md5('$pwd') WHERE clientid='$id' AND token='$token'";
		$data_r = mysqli_query ($bdbm,$data_q) or die("Update Password Failed. Error:\n". mysqli_error($bdbm));
		mysqli_close($bdbm);
	}
	function getClientLastCalls($id)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT * FROM phone_log WHERE clientid = '$id' ORDER BY ID") or die("Last 10 Calls LookUp Failed. Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}
	function searchClientLastCalls($id,$search)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT * FROM phone_log WHERE clientid = '$id' AND (caller_name LIKE '%$search%' OR caller_coname LIKE '%$search%' OR caller_no LIKE '%$search%' OR log_date LIKE '%$search%') ORDER BY ID") or die("search Calls Failed. Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}
	function getClientLastInvoices($vo_id, $cid)
	{
		include "db_conn.php";	
		$Inv_table_prefix = "client_invoices_";
		$active_inv_table = $Inv_table_prefix.$vo_id;
		$data_r = mysqli_query($bdbm,"SELECT * FROM $active_inv_table WHERE client_id='$cid' AND invoice_status !='3' ORDER BY invoice_id") or die("Last 10 Invoices LookUp Failed. Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}
	function getClientInvoice($vo_id, $inv_id)
	{
		include "db_conn.php";	
		$Inv_table_prefix = "client_invoices_";
		$active_inv_table = $Inv_table_prefix.$vo_id;
		$data_r = mysqli_query($bdbm,"SELECT * FROM $active_inv_table WHERE vo_id = '$vo_id' AND invoice_id='$inv_id' AND invoice_status !='3'") or die("Invoices LookUp Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_assoc($data_r);
		return $fdata;
		mysqli_close($bdbm);
	}
	function getInvoiceStatus($status)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT status FROM invoice_status WHERE status_id = '$status'") or die("Checking User Details Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_assoc($data_r);
		return $fdata["status"];
		mysqli_close($bdbm);	
	}
	function getClientVOID($cid)
	{
		include "db_conn.php";		
		$data_r = mysqli_query($bdbm,"SELECT location FROM client_info WHERE clientid = '$cid'") or die("Checking User Details Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata["location"];
		mysqli_close($bdbm);	
	}
	function getClientplans($cid)
	{ 
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT * FROM client_plans WHERE client_id = '$cid' AND status ='1'") or die("Client Plans LookUp Failed. Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}
	function getClientContacts($pid)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT * FROM client_contacts WHERE cp_id IN (" . implode(',', array_map('intval', $pid)) . ")") or die("Client Plans LookUp Failed. Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}
	function portal_main_page_getClientCompanyInfo($id)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT * FROM client_info WHERE clientid = '$id'") or die("Grab Main Page Co Name Fail!".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata;
		mysqli_close($bdbm);

	}
	function portal_main_page_getClientFacilityInfo($id)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT * FROM client_facilities_core WHERE client_id = '$id' AND status = '1'") or die("Last 10 Calls LookUp Failed. Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);

	}
	function CheckPlanReload($cp_id,$cid)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT product_id FROM client_plans WHERE cp_id='$cp_id' AND client_id='$cid'") or die("Checking User Login Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata["product_id"];
		mysqli_close($bdbm);

	}
	function CheckPlanReloadMeeting_hours($cp_id,$cid,$column)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT `$column` FROM client_facilities_core WHERE cp_id='$cp_id' AND client_id='$cid'") or die("Checking User Login Failed. Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata[0];
		mysqli_close($bdbm);

	}
		
	function sort_clinetPlansonReload($id)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT * FROM products WHERE client_id = '$id' AND status = '1'") or die("Last 10 Calls LookUp Failed. Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);

	}
	function getclient_planid_via_invoice($vo_id, $fid)
	{
		include "db_conn.php";	
		$Inv_table_prefix = "client_invoices_";
		$active_inv_table = $Inv_table_prefix.$vo_id;
	
		$data_r = mysqli_query($bdbm,"SELECT client_plans_id FROM $active_inv_table WHERE invoice_id='$fid' AND invoice_status !='3'") or die("User Invoice LookUp Fail!".mysqli_error($bdbm));
		$fdata = mysqli_fetch_row($data_r);
		return $fdata[0];
		mysqli_close($bdbm);
	}
	function getVOInvoiceTemplate($void)
	{
		include "db_conn.php";	
        $data_q = "SELECT * FROM invoice_template WHERE vo_id = '$void'";
		$data_r = mysqli_query ($bdbm,$data_q) or die("Could not get VO Invoice Template.\n".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata;
		mysqli_close($bdbm);
	}
	function getVOLocationInfo($void)
	{
		include "db_conn.php";	
        $data_q = "SELECT * FROM location_info WHERE id = '$void'";
		$data_r = mysqli_query ($bdbm,$data_q) or die("Could not get VO Info.\n".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata;
		mysqli_close($bdbm);
	}
		function Insert_inv_mapping($client_plan_id,$void,$finv)
	{
		include "db_conn.php";	
		$addmapinvstmt = "INSERT INTO sales_lead_map_client_plan_id
        	(client_plan_id, vo_id, invoice_id, staff_id, assisted_sales)
            	VALUES
            ('$client_plan_id','$void', '$finv', '0', '2')";

		$resultaddmapinvstmt = mysqli_query ($bdbm,$addmapinvstmt) or die("Could not get map invoice.\n".mysqli_error($bdbm));
		mysqli_close($bdbm);
	}
	function applyPayment($void, $ref_id, $raw_total, $invoices2)
	{

		include "db_conn.php";

		$inv_table = "client_invoices_".$void;				
		$datepaid = date ("Y-m-d", time());
		$update_inv_stmt = "UPDATE ".$inv_table." SET date_paid='$datepaid',paid_reference='$ref_id', amount_paid='$raw_total', invoice_status='2' WHERE invoice_id = '$invoices2'";
		$update_inv_result = mysqli_query ($bdbm,$update_inv_stmt) or die("Could not update Invoice \n".mysqli_error($bdbm));
		$rcpt_table = "client_receipt_".$void;				
		$addreceiptstmt = "INSERT INTO ".$rcpt_table."	
							(vo_id, invoice_id, amount_paid, receipt_date, staff) 
							VALUES 
							('$void', '$invoices2', '$raw_total', '$datepaid','0')";
					
		$resultaddreceiptstmt = mysqli_query ($bdbm,$addreceiptstmt) or die ("Could not insert reciept \n".mysqli_error($bdbm));
		$optype = 2; 
		$addinvstmt = "INSERT INTO invoice_transaction_log 	
							(vo_id, invoice_id, optype, staff) 
							VALUES 
							('$void', '$invoices2', '$optype', '0')";
					
		$resultaddinvstmt = mysqli_query ($bdbm,$addinvstmt) or die ("Could not insert invoice log \n".mysqli_error($bdbm));
		mysqli_close($bdbm);
	}
	function getInvoiceAmount($void, $invid, $clientid)
	{
		include "db_conn.php";
		$inv_table = "client_invoices_".$void;
        $inv_check = "SELECT currency, setup_fee, amount_due FROM ".$inv_table." WHERE invoice_id='$invid' AND client_id='$clientid'";
		$bill_cycle_result = mysqli_query ($bdbm,$inv_check) or die ("Could not get invoice amount \n".mysqli_error($bdbm));
		return $bill_cycle_result;
		mysqli_close($bdbm);
	}
	function addContact($id, $name, $name, $email, $number, $msg, $stat)
	{
		include "db_conn.php";
		$data_q = "INSERT INTO client_contacts (cp_id, contact_name, contact_alias, contact_email, contact_number, additional_message, privacy) VALUES ('$id', '$name', '$name', '$email', '$number', '$msg', '$stat')";
		$data_r = mysqli_query ($bdbm,$data_q) or die ("Could not insert contact \n".mysqli_error($bdbm));
		mysqli_close($bdbm);
	}
	
	function addReferral($clientid, $ref_name, $ref_email, $ref_contact, $ref_coname, $ref_pax, $ref_loc, $ref_notes, $stage)
	{
		include "db_conn.php";
		$data_q = "INSERT INTO referral_v2 (clientid, ref_name, ref_email, ref_contact, ref_coname, ref_pax, ref_loc, ref_notes, stage) VALUES ('$clientid', '$ref_name', '$ref_email', '$ref_contact', '$ref_coname', '$ref_pax', '$ref_loc', '$ref_notes', '$stage')";
		$data_r = mysqli_query ($bdbm,$data_q) or die ("Could not insert contact \n".mysqli_error($bdbm));
		$ref_id =  mysqli_insert_id($bdbm);
		return $ref_id;
		mysqli_close($bdbm);
	}
	
	function getClientReferrals($clientid)
	{
		include "db_conn.php";
		$data_r = mysqli_query($bdbm,"SELECT * FROM referral_v2 WHERE clientid='$clientid' AND stage!='4' ORDER BY id DESC") or die(mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}
	function getClientSuccessReferrals($clientid)
	{
		include "db_conn.php";
		$data_r = mysqli_query($bdbm,"SELECT * FROM referral_v2 WHERE clientid='$clientid' AND stage='4' ORDER BY id DESC") or die(mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}
	
	function getClientContact($id)
	{
		include "db_conn.php";	
        $data_q = "SELECT * FROM client_contacts WHERE id='$id'";
		$data_r = mysqli_query ($bdbm,$data_q) or die("Could not get VO Info.\n".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata;
		mysqli_close($bdbm);
	}
	function updateClientContact($id, $cid, $name, $name, $email, $number, $msg, $stat)
	{
		include "db_conn.php";		
		$data_q = "UPDATE client_contacts SET contact_name='$name', contact_alias='$name', contact_email='$email', contact_number='$number', additional_message='$msg', privacy='$stat'  WHERE cp_id='$cid' AND id='$id'";
		$data_r = mysqli_query ($bdbm,$data_q) or die("Update contact Failed. Error:\n". mysqli_error($bdbm));
		mysqli_close($bdbm);
	}	
	
	//invoicing funcs
	function getFacilityPrice($base_void, $facility_id)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT product_id, product_name, currency, price FROM products WHERE location_id='$base_void' AND location_facilities_id ='$facility_id'") or die("Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata;
		mysqli_close($bdbm);

	}
	function getLocationCurrency($vid)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT currency FROM location_info WHERE id='$vid'") or die("Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_row($data_r);
		return $fdata[0];
		mysqli_close($bdbm);
	}
	
	function getVOName($pid)
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT location_desc FROM location_info WHERE id='$pid'") or die("Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_row($data_r);
		return $fdata[0];
		mysqli_close($bdbm);
	}
	
	function get_Facilities_Type($id)
	{	
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT facilities_type FROM facilities_type WHERE id = '$id'") or die("Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata[0];
		mysqli_close($bdbm);
	}
	
	function logInvoiceTransaction($void, $inv_id, $optype, $staff)
	{
		include "db_conn.php";
		$data_q = "INSERT INTO invoice_transaction_log (vo_id, invoice_id, optype, staff) VALUES ('$void', '$inv_id', '$optype', '$staff')"; 
		$data_r = mysqli_query ($bdbm,$data_q) or die("Error:\n" . mysqli_error($bdbm));
		mysqli_close($bdbm);
	}
	
	function create_client_facilities_invoice_v2($cid, $void, $facility_type, $num_hours, $book_date, $rm_location, $unitprice)
	{
		include "db_conn.php";
		
		$inv_table = "client_invoices_".$void;
		
		// set custom invoice plan id as 0;
		$plan_id = 0;
		
		$incurrency = $this->getLocationCurrency($void);
		
		$meeting_rm_loc = $this->getVOName($rm_location);

		$facilities_name = $this->get_Facilities_Type($facility_type);
		$product_name = $meeting_rm_loc." ".$facilities_name." ".$num_hours ." Additional Hour(s) Rental.";
		
		$comments = $num_hours ." Hour(s) Rental.";

		$price = $unitprice;
	
		$f_total = $price * $num_hours;
		
		$addinvstmt = "INSERT INTO ".$inv_table."	
					(vo_id, client_id, client_plans_id, description, date_generated, date_due, currency, amount_due, invoice_status, additional_comments, period_from, period_to) 
					VALUES 
					('$void', '$cid', '$plan_id', '$product_name','$book_date', '$book_date', '$incurrency', '$f_total', '1', '$comments', '$book_date', '$book_date')";
			
		$resultaddinvstmt = mysqli_query ($bdbm,$addinvstmt) or die("Error:\n" . mysqli_error($bdbm));
		
		$inv_id =  mysqli_insert_id($bdbm);

		$optype = 1; // Create New Invoice
		$this->logInvoiceTransaction($void, $inv_id, $optype, "0");
		
		$f_inv_id = $void."-".$inv_id;
		return $f_inv_id;
		mysqli_close($bdbm);
		
	}
	
	function getVOLocationListAll()
	{
		include "db_conn.php";	
		$data_r = mysqli_query($bdbm,"SELECT * FROM location_info") or die("Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);

	}
	
	function get_total_client_invoice_history($void, $cid)
	{
		include "db_conn.php";
		$invoice_db = "client_invoices_".$void;	
		$data_r = mysqli_query($bdbm,"SELECT * FROM $invoice_db WHERE client_id='$cid' AND invoice_status != '3' Order By date_generated DESC") or die("Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}
	
	function get_client_invoice_history($void, $cid, $from, $perpage)
	{
		include "db_conn.php";
		$invoice_db = "client_invoices_".$void;
		$data_r = mysqli_query($bdbm,"SELECT * FROM $invoice_db WHERE client_id='$cid' AND invoice_status != '3' ORDER BY invoice_id DESC LIMIT $from, $perpage") or die("Error:\n ".mysqli_error($bdbm));
		return $data_r;
		mysqli_close($bdbm);
	}
	
	function portal_main_page_getClientInvoice($vo_id, $inv_id)
	{
		include "db_conn.php";	
		$Inv_table_prefix = "client_invoices_";
		$active_inv_table = $Inv_table_prefix.$vo_id;
		$data_r = mysqli_query($bdbm,"SELECT * FROM $active_inv_table WHERE vo_id = '$vo_id' AND invoice_id='$inv_id' AND invoice_status !='3'") or die("Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata;
		mysqli_close($bdbm);
	}
	
	function portal_getInvoiceStatus($status)
	{	
		include "db_conn.php";	
		$Inv_table_prefix = "client_invoices_";
		$data_r = mysqli_query($bdbm,"SELECT status FROM invoice_status WHERE status_id = '$status'") or die("Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata['status'];
		mysqli_close($bdbm);
	}
	
	function portal_getInvoiceReceipt($void, $inv)
	{	
		include "db_conn.php";	
		$rcpt_table ="client_receipt_".$void;
		$data_r = mysqli_query($bdbm,"SELECT receipt_id FROM ".$rcpt_table." WHERE invoice_id = '$inv'") or die("Error:\n ".mysqli_error($bdbm));
		$fdata = mysqli_fetch_array($data_r);
		return $fdata['receipt_id'];
		mysqli_close($bdbm);
	}
	function void_invoice($inv, $void)
	{
		include "db_conn.php";	
		$Inv_table_prefix = "client_invoices_";
		$active_inv_table = $Inv_table_prefix.$void;
		$data_q = "UPDATE $active_inv_table SET invoice_status='3' WHERE invoice_id='$inv'";
		$data_r = mysqli_query ($bdbm,$data_q) or die("Error:\n". mysqli_error($bdbm));
		mysqli_close($bdbm);
	}


	
}
?>