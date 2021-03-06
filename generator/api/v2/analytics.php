<?
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

function GetAnalyticsData($connDyn, $id)
{
	global $Marshaler;
	$ret["error_code"] = "0";
/*
	$project_data ='{
		":id" : "'.$id.'"
	}';

	
	$table = ExecuteQuery("modu_deliverables",$project_data,"deliverable_id = :id", "" , "" , false);
	$projects = []; 

	$site_id="";

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
			$site_id = $Marshaler->unmarshalValue($dbdata[0]['site-id']);
		}
	}else{
		$ret["error_code"] = "500";
		$ret["message"] = $table["error"];
		return $ret;
	}
	if ($site_id == "")
	{
		$ret["error_code"] = "500";
		$ret["message"] = "Unable to get data";
		echo "error";
		return $ret;
	}*/

	$site_id = $id;

	$dates=[];
	$scroll["0-25"]=0;
		$scroll["26-50"]=0;
		$scroll["51-75"]=0;
		$scroll["76-100"]=0;
	for ($x = 0; $x <= 6; $x++) {
	    $rep_date = date("Ymd", strtotime("-".$x." days", strtotime(date("Y-m-d"))));
	    $rep_date2 = date("m/d/Y", strtotime("-".$x." days", strtotime(date("Y-m-d"))));

	    #Add Date for return to dashboard
	    $dates[]=$rep_date2;

	    $events_data ='{
			":id" : "'.$site_id.'",
			":pDate" : "'.$rep_date.'"
		}';

		$table = ExecuteQuery("baw_events",$events_data,"site_id = :id AND e_date = :pDate", "site_id-e_date-index" , "" , false);


		
		if ($table["error"]=="")
		{
			$dbdata = $table["data"]['Items'];
			if (count($dbdata)>0)
			{
				$session_data=[];
				$users_data=[];
				$time_page=[];
				$tvisits = 0;
				$tnewUsers = 0;
				$tclicks = 0;
				$traffic = [];

				$traffic["Search"] = 0 ;
				$traffic["Direct"] = 0 ;
				$traffic["Other"] = 0 ;
				$traffic["Social"] = 0 ;
				foreach  ($dbdata as $e)
				{
					
					switch ($Marshaler->unmarshalValue($e['e_type']))
					{

						case "new_session":
							$tnewUsers = $tnewUsers + 1;
							break;
						case "visit":
							$tvisits = $tvisits + 1;
							$user_id = $Marshaler->unmarshalValue($e['user_id']);
							$session_id = $Marshaler->unmarshalValue($e['session_id']);

							if (array_search($user_id,$users_data)===false)
							{
								$users_data[] = $user_id;
							}

							if (array_search($session_id,$session_data)===false)
							{
								$session_data[] = $session_id;
							}

							if ($e["ref_type"])
							{
								$ref_type = $Marshaler->unmarshalValue($e['ref_type']);
								if ($traffic[$ref_type])
								{
									$traffic[$ref_type] ++;

								}else{
									$traffic[$ref_type] = 1;
								}
							}


							break;
						case "click":
							if (is_array($e['e_value']))
							{
								$tclicks = $tclicks + $Marshaler->unmarshalValue($e['e_value']);
							}
							break;

						case "scroll_percentage":
							if (is_array($e['e_value']))
							{
								$scroll_value = $Marshaler->unmarshalValue($e['e_value']);
								//echo "scroll_percentage ".$scroll_value;
								if ($scroll_value >=0 && $scroll_value <=25)
								{
									$scroll["0-25"]++;
								}elseif ($scroll_value>25 && $scroll_value<=50)
								{
									$scroll["26-50"]++;
								}elseif ($scroll_value>50 && $scroll_value<=75)
								{
									$scroll["51-75"]++;
								}
								elseif ($scroll_value>75 )
								{
									$scroll["76-100"]++;
								}
								//print_r($scroll);	
							}
							
							break;
						case "time_page":
							if (is_array($e['e_value']))
							{
								$time_page [] = $Marshaler->unmarshalValue($e['e_value']);
							}
							
							break;
					}
				}
				$sessions[] = count($session_data);
				$users[] = count($users_data);
				$visits[] = $tvisits;
				$new_users[] = $tnewUsers;
				$clicks[] = $tclicks;
				if (count($time_page)>0)
				{
					$timevisits [] = array_sum($time_page) / count($time_page);
				}else
				{
					$timevisits [] = 0;
				}
				//print_r($session_data);
			}
			else{

				$users[]=0;
				$sessions[] = 0;
				$new_users[] = 0;
				$visits[] = 0;
				$clicks[] = 0;
				$timevisits [] =0;

			}
		}else{
			echo "error ".$table["error"];
		}
	} 

	$return["dates"] = $dates;
	$return["users"] = $users;
	$return["new_users"] = $new_users;
	$return["sessions"] = $sessions;
	$return["visits"] = $visits;
	$return["average_duration"] = $timevisits;
	$return["total_clicks_per_day"] = $clicks;
	$return["page_scroll"] = $scroll;
	$return["traffic_sources"] = $traffic;
	echo json_encode($return);
	//print_r($dates);
}

?>