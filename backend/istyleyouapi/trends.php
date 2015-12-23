<?php
include("db_config.php");
include("ProductLink.php");

if($_SERVER['REQUEST_METHOD']=="GET" && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])){
	$userid = mysql_real_escape_string($_REQUEST['userid']);

	$user_details_query = "SELECT user_id, gender, bodytype
							FROM userdetails
							WHERE user_id = $userid
							LIMIT 0,1";
	$user_res=mysql_query($user_details_query);
	$user_rows=mysql_num_rows($user_res);

	if($user_rows > 0){
		$user_data = mysql_fetch_array($user_res);
		$gender = $user_data[1];
		$bodytype = $user_data[2];
		$body_type_condition = $gender == 'female' ? " AND cl.body_type = '{$bodytype}'" : "";

		//Get 4 latest looks for 4 occasions which are not unliked by current user
		$looks = array();

		$page = isset($_GET['page']) && $_GET['page'] != '' ? mysql_real_escape_string($_GET['page']) : 0;
		$occasions = array('Wine & Dine', 'Casuals', 'Ethnic/Festive', 'Work Wear');
		if(isset($_GET['occasion']) && $_GET['occasion'] != ''){
			$occasions = array_intersect($occasions, array(mysql_real_escape_string($_GET['occasion'])));
		}

		$record_start = intval($page * 4);
		$records_count = 4;

		//var_dump($page, $occasions);
		//die;

		foreach($occasions as $occasion){
			$looks_sql =
				"SELECT cl.look_id, look_description, look_image, lookprice, cl.occasion, look_name, uf.fav_id,
						sd.stylish_id, sd.stylish_name, sd.stylish_image
				FROM looks cl
					LEFT JOIN usersfav uf ON cl.look_id = uf.look_id
					JOIN stylish_details sd on sd.stylish_id = cl.stylish_id
				WHERE cl.gender = '$gender'
					$body_type_condition
					AND cl.occasion = '$occasion'
					AND cl.status_id = 1
					AND (uf.user_id is null OR uf.user_id = '$userid')
					AND cl.look_id NOT IN
						(SELECT look_id
						FROM users_unlike
						WHERE user_id='$userid')
				ORDER BY cl.date DESC
				LIMIT $record_start, $records_count ";
			//echo $looks_sql . "<br /><br />";

			$looks_res = mysql_query($looks_sql);

			while ($data = mysql_fetch_array($looks_res)) {
				$looks[] = $data;
			}
			unset($looks_res);
		}
//var_dump($looks);
		$looks_count = count($looks);

		if($looks_count > 0) {
			// Get all favourite products of current user
			$fav_prod_sql =
				"Select product_id
					from usersfav join products
						on usersfav.product_id = products.id
					where user_id='$userid'";
			$fav_prod_res = mysql_query($fav_prod_sql);
			$fav_prod_count = mysql_num_rows($fav_prod_res);

			while ($data = mysql_fetch_array($fav_prod_res)) {
				$fav_prods[] = $data['product_id'];
			}

			for ($i = 0; $i < $looks_count; $i++) {
				$look_id = $looks[$i][0];

				//Get products info for current look
				$current_look_products_query =
					"select id,product_name,upload_image,product_price,product_type,product_link, ld.agency_id, ld.merchant_id
					from products ld join looks
						on looks.product_id1=ld.id
						or looks.product_id2=ld.id
						or looks.product_id3=ld.id
						or looks.product_id4=ld.id
					where look_id='$look_id'";
				$current_look_products_res = mysql_query($current_look_products_query);
				$current_look_products = [];
				while ($data1 = mysql_fetch_array($current_look_products_res)) {
					$current_look_products[] = $data1;
				}

				$productarray = array();
				for ($j = 0; $j < count($current_look_products); $j++) {
					if ($fav_prod_count == 0) {
						$fav = 'No';
					}
					for ($k = 0; $k < $fav_prod_count; $k++) {
						if ($current_look_products[$j][0] == $fav_prods[$k]) {
							$fav = 'yes';
							break;
						} else {
							$fav = 'No';
						}
					}

					$productarray[] = array(
						'fav' => $fav,
						'productid' => $current_look_products[$j][0],
						'productname' => $current_look_products[$j][1],
						'productimage' => $current_look_products[$j][2],
						'productprice' => $current_look_products[$j][3],
						'producttype' => $current_look_products[$j][4],
						'productlink' => ProductLink::getDeepLink($current_look_products[$j][6],
														$current_look_products[$j][7],
														$current_look_products[$j][5])
					);
				}

				$stylist_details = array();
				$stylist_details['stylish_id'] = $looks[$i]['stylish_id'];
				$stylist_details['stylish_name'] = $looks[$i]['stylish_name'];
				$stylist_details['stylish_image'] = $looks[$i]['stylish_image'];

				$current_look_details =
					array(
						'lookdetails' =>
							array(
								'fav' => $looks[$i][6] == null ? 'No' : 'Yes',
								'lookid' => $looks[$i]['look_id'],
								'lookdescription' => $looks[$i]['look_description'],
								'lookimage' => $looks[$i]['look_image'],
								'lookprice' => $looks[$i]['lookprice'],
								'occasion' => $looks[$i]['occasion'],
								'lookname' => $looks[$i]['look_name'],
								'productdetails' => $productarray,
								'stylish_details' => $stylist_details
							)
					);
				$looks_and_products[] = $current_look_details;
				unset($current_look_products);
			}

			$response = array('result' => 'success', 'looks' => $looks_and_products);
		}
		else{
			$response=array('result'=>'fail','response_message'=>'No records');
		}
	}
	else{
		$response=array( 'result'=>'fail', 'response_message'=>'userid doesnt exist in db' );
	}
}
else{
	$response=array('result'=>'fail','response_message'=>'userid empty');
}

//var_dump($response['looks'][0]['lookdetails']);

mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($response);
?>
