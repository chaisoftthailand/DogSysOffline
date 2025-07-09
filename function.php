<?
function saveBase64Image($base64String, $prefix = 'img_') {
    if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
        $data = substr($base64String, strpos($base64String, ',') + 1);
        $data = base64_decode($data);
        if ($data === false) {
            return '';
        }
        $extension = strtolower($type[1]);
        $filename = 'uploads/' . $prefix . uniqid() . '.' . $extension;
        file_put_contents($filename, $data);
        return $filename;
    }
    return '';
}


$aRole=Array('คนทั่วไป','ลูกค้า','เจ้าหน้าที่คลินิก','ผู้ดูแลระบบ');
function opt_role($string){ 
	$aRole=Array('คนทั่วไป','ลูกค้า','เจ้าหน้าที่คลินิก','ผู้ดูแลระบบ');
	for($i=0;$i<=count($aRole)-1;$i++){
		if($string==$i) {?>
			<option value=<?=$i;?>  selected><?=$i;?>.<?=$aRole[$i];?></option><?
		} else {?>
		<option value=<?=$i;?>><?=$i;?>.<?=$aRole[$i];?></option>
		<?
		}
	}
}
function ret_user($user_id,$objCon){ 
	$strSQL = "SELECT * FROM user WHERE id=$user_id ORDER BY id limit 1";	   
  	$objQuery = mysqli_query($objCon, $strSQL);
	while($objRequest = mysqli_fetch_assoc($objQuery)) { 
			return $objRequest['fullname']; 
	}
}

function ret_clinic($clinic_id,$objCon){ 
	$strSQL = "SELECT * FROM clinics WHERE clinic_id=$clinic_id ORDER BY clinic_id limit 1";	   
  	$objQuery = mysqli_query($objCon, $strSQL);
	while($objRequest = mysqli_fetch_assoc($objQuery)) { 
			return $objRequest['clinic_name']; 
	}
}
function opt_clinic($clinic_id,$objCon){ 
	$strSQL = "SELECT * FROM clinics ORDER BY clinic_id ";	   
  	$objQuery = mysqli_query($objCon, $strSQL);
	while($objRequest = mysqli_fetch_assoc($objQuery)) { 
		if($clinic_id==$objRequest['clinic_id']) {?>
			<option value=<?=$objRequest['clinic_id'];?>  selected><?=$objRequest['clinic_name'];?></option><?
		} else {?>
		<option value=<?=$objRequest['clinic_id'];?>><?=$objRequest['clinic_name'];?></option>
	  <?}
}}
function dec_enc($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'Ramintra';
    $secret_iv = 'Ramintra';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
return $output;
}

?> 