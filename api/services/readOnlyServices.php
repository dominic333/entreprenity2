<?php


//Function to fetch locations based on countries
//September 07,2016
function getLocationsUnderCountry()
{
	$data= array();	
	
	$ccode = validate_input($_GET['country']);
	//SELECT id,location_desc,country_code FROM location_info
	$qry="SELECT id,location_desc,country_code FROM location_info WHERE country_code=".$ccode." AND id NOT IN (10)";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']				=	$row['id'];
			$data[$i]['location_desc']	=	$row['location_desc'];
			$i++;
      }	
   }
	return $data;
}


//Function to fetch country locations (selected countries)
//September 07,2016
function getSelectedCountries()
{
	//Just Indonesia, Singapore, Philippines Malaysia for now
	$data= array();	
	$qry="SELECT * FROM vo_country_list WHERE country IN ('Indonesia','Singapore','Philippines','Malaysia')";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
	if($count_res>0)
   {
		while($row=mysqli_fetch_array($res))
	   {
	   	$data[$i]['country']			=	$row['country'];
			$data[$i]['country_code']	=	$row['country_code'];
			$i++;
	   }
   }	
	return $data;
}

//Function to fetch country locations (selected countries)
//September 07,2016
function getAllCountries()
{
	$data= array();	
	$qry="SELECT * FROM vo_country_list";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['country']			=	$row['country'];
			$data[$i]['country_code']	=	$row['country_code'];
			$i++;
      }	
   }
	return $data;
}


//Function to get all Event Categories
//June 20,2016
function getAllEventCatgories()
{
	$data= array();	
	$qry="SELECT  id,category_name FROM entrp_event_categories";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']				=	$row['id'];
			$data[$i]['category']	=	$row['category_name'];
			$i++;
      }	
   }
   else
   {
   	$data[$i]['id']				=	"";
		$data[$i]['category']	=	"";
   }
	return $data;
}


//Function to fetch all company categories
//June 15,2016
function getAllCompanyCategories()
{

	$final_array= array();
	
	$qry="SELECT * FROM entrp_company_categories";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$temp_data       =   json_decode($row['category'],true); 
  			$final_array     =   array_merge($final_array ,$temp_data);				
		}
	}   
	return $final_array;
}


//Function to get all interest set
//May 02, 2016
function get_all_interest_sets()
{
	$i=0;
	$data= array();
	$qry="SELECT *  
			FROM entrp_interests
			WHERE status=1 
			";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			//$data[$i]['id']		=	$row['id'];
			$data[$i]		=	$row['interest'];  			
			$i++;
		}		
	}
	return $data;

}

//Function to get all skill set
//May 02, 2016
function get_all_skill_sets()
{
	$i=0;
	$data= array();
	$qry="SELECT *  
			FROM entrp_skills
			WHERE status=1 
			";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			//$data[$i]['id']		=	$row['id'];
			$data[$i]		=	$row['skills'];  			
			$i++;
		}		
	}
	return $data;
}

//Function to get all languages to show in the profile preferences
//May 24, 2016
//Arshad
function get_all_languages()
{
	$i = 0;
	$data = array();
	$qry = "SELECT *  
			 FROM entrp_languages
			 WHERE lang_status = 1 
			 ";
   $res = getData($qry);
	$count_res = mysqli_num_rows($res);
	if($count_res > 0)
	{
		while($row = mysqli_fetch_array($res))
		{
			$data[$i]['id']		=	$row['lang_id'];
			$data[$i]['text']		=	$row['lang_name'];
			$data[$i]['image']		=	$row['lang_image'];
			$i++;
		}		
	}
	return $data;
}

//Function to get all timezones to show in the profile preferences
//May 24, 2016
//Arshad
function get_all_timezones()
{
	$i = 0;
	$data = array();
	$qry = "SELECT *  
			 FROM entrp_timezones
			 WHERE timezone_status = 1 
			 ";
   $res = getData($qry);
	$count_res = mysqli_num_rows($res);
	if($count_res > 0)
	{
		while($row = mysqli_fetch_array($res))
		{
			$data[$i]['id']		=	$row['timezone_id'];
			$data[$i]['text']		=	$row['timezone_name'];
			$i++;
		}		
	}
	return $data;
}


//Function to fetch location list (centers)
//April 19,2016
function getLocations_dropdown()
{
	$data= array();	
	//$qry="SELECT  id,location_desc FROM location_info WHERE id NOT IN (3,4,19,20,31,42,49)";
	$qry="SELECT  id,location_desc 
			FROM location_info 
			WHERE id NOT IN (3,4,10,19,20,31,42,49) 
			AND country_code IN (SELECT country_code FROM vo_country_list WHERE country IN ('Indonesia','Singapore','Philippines','Malaysia'))";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']				=	$row['id'];
			$data[$i]['location_desc']	=	$row['location_desc'];
			$i++;
      }	
   }
   else
   {
   	$data[$i]['id']				=	"";
		$data[$i]['location_desc']	=	"";
   }
	return $data;	
}



?>