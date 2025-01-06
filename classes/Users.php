<?php
require_once('../config.php');
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		if(!isset($_POST['status']) && $this->settings->userdata('login_type') == 1){
			$_POST['status'] = 1;
			$_POST['type'] = 2;
		}
		extract($_POST);
		$oid = $id;
		$data = '';
		if(isset($oldpassword)){
			if(md5($oldpassword) != $this->settings->userdata('password')){
				return 4;
			}
		}
		$chk = $this->conn->query("SELECT * FROM `users` where username ='{$username}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}
		foreach($_POST as $k => $v){
			if(in_array($k,array('firstname','middlename','lastname','username','type'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}

		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			if($qry){
				$id = $this->conn->insert_id;
				$this->settings->set_flashdata('success','User Details successfully saved.');
				$resp['status'] = 1;
			}else{
				$resp['status'] = 2;
			}

		}else{
			$qry = $this->conn->query("UPDATE users set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				if($id == $this->settings->userdata('id')){
					foreach($_POST as $k => $v){
						if($k != 'id'){
							if(!empty($data)) $data .=" , ";
							$this->settings->set_userdata($k,$v);
						}
					}
					
				}
				$resp['status'] = 1;
			}else{
				$resp['status'] = 2;
			}
			
		}
		
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = 'uploads/avatar/avatar-'.$id.'.png';
			$dir_path =base_app. $fname;
			$upload = $_FILES['img']['tmp_name'];
			$type = mime_content_type($upload);
			$allowed = array('image/png','image/jpeg');
			if(!in_array($type,$allowed)){
				$resp['msg'].=" But Image failed to upload due to invalid file type.";
			}else{
				$new_height = 200; 
				$new_width = 200; 
		
				list($width, $height) = getimagesize($upload);
				$t_image = imagecreatetruecolor($new_width, $new_height);
				imagealphablending( $t_image, false );
				imagesavealpha( $t_image, true );
				$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
				imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				if($gdImg){
						if(is_file($dir_path))
						unlink($dir_path);
						$uploaded_img = imagepng($t_image,$dir_path);
						imagedestroy($gdImg);
						imagedestroy($t_image);
				}else{
				$resp['msg'].=" But Image failed to upload due to unknown reason.";
				}
			}
			if(isset($uploaded_img)){
				$this->conn->query("UPDATE users set `avatar` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}' ");
				if($id == $this->settings->userdata('id')){
						$this->settings->set_userdata('avatar',$fname);
				}
			}
		}
		if(isset($resp['msg']))
		$this->settings->set_flashdata('success',$resp['msg']);
		return  $resp['status'];
	}
	public function delete_users(){
		extract($_POST);
		$avatar = $this->conn->query("SELECT avatar FROM users where id = '{$id}'")->fetch_array()['avatar'];
		$qry = $this->conn->query("DELETE FROM users where id = $id");
		if($qry){
			$avatar = explode("?",$avatar)[0];
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			if(is_file(base_app.$avatar))
				unlink(base_app.$avatar);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	public function save_student() {
		extract($_POST);
		$data = '';
		$resp = ['status' => 'failed', 'msg' => 'An unexpected error occurred.'];
	
		// Validate email address
		if (isset($email)) {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@evsu\.edu\.ph$/', $email)) {
				return json_encode(["status" => 'failed', "msg" => 'Invalid email address. Only institutional email addresses are allowed.']);
			}
		} else {
			return json_encode(["status" => 'failed', "msg" => 'Email address is required.']);
		}
	
		// Check old password
		if (isset($oldpassword)) {
			if (md5($oldpassword) != $this->settings->userdata('password')) {
				return json_encode(["status" => 'failed', "msg" => 'Old Password is Incorrect']);
			}
		}
	
		// Check for existing email
		$chk = $this->conn->query("SELECT * FROM `student_list` WHERE email = '{$email}' " . ($id > 0 ? " AND id != '{$id}' " : ""))->num_rows;
		if ($chk > 0) {
			return json_encode(["status" => 'failed', "msg" => 'Email address already exists.']);
		}
	
		// Prepare data for insertion/updation
		foreach ($_POST as $k => $v) {
			if (!in_array($k, ['id', 'oldpassword', 'cpassword', 'password'])) {
				$data .= empty($data) ? "" : ", ";
				$data .= "{$k} = '{$v}'";
			}
		}
	
		// Hash new password if provided
		if (!empty($password)) {
			$password = md5($password);
			$data .= empty($data) ? "" : ", ";
			$data .= "`password` = '{$password}'";
		}
	
		// Insert or update student details
		if (empty($id)) {
			$qry = $this->conn->query("INSERT INTO student_list SET {$data}");
			if ($qry) {
				$id = $this->conn->insert_id;
				$this->settings->set_flashdata('success', 'Student User Details successfully saved.');
				$resp['status'] = "success";
			} else {
				$resp['msg'] = "An error occurred while saving the data. Error: " . $this->conn->error;
			}
		} else {
			$qry = $this->conn->query("UPDATE student_list SET {$data} WHERE id = {$id}");
			if ($qry) {
				$this->settings->set_flashdata('success', 'Student User Details successfully updated.');
				$resp['status'] = "success";
			} else {
				$resp['msg'] = "An error occurred while updating the data. Error: " . $this->conn->error;
			}
		}

		// Handle file upload
		 // Check if an image is uploaded
		 if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = 'uploads/profile/profile-' . $id . '.png';
			$dir_path = base_app . $fname;
			$upload = $_FILES['img']['tmp_name'];
			$type = mime_content_type($upload);
			$allowed = ['image/png', 'image/jpeg', 'image/jpg'];
	
			// Validate the image type
			if (!in_array($type, $allowed)) {
				return json_encode(["status" => 'failed', "msg" => 'Invalid file type. Only PNG and JPEG formats are allowed.']);
			}
	
			// Resize the image
			list($width, $height) = getimagesize($upload);
			$new_height = 200;
			$new_width = 200;
			$t_image = imagecreatetruecolor($new_width, $new_height);
			imagealphablending($t_image, false);
			imagesavealpha($t_image, true);
			
			// Create a new image resource based on the file type
			$gdImg = ($type == 'image/png') ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
			imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			
			// Save the resized image
			if (imagepng($t_image, $dir_path)) {
				imagedestroy($gdImg);
				imagedestroy($t_image);
				
				// Update avatar in the database
				$this->conn->query("UPDATE student_list SET `avatar` = '{$fname}' WHERE id = '{$id}'");
				if ($this->conn->affected_rows > 0) {
					$resp['status'] = 'success';
					$resp['msg'] = 'Avatar successfully uploaded.';
					if ($id == $this->settings->userdata('id')) {
						$this->settings->set_userdata('avatar', $fname);
					}
				} else {
					$resp['msg'] = 'Failed to update avatar in the database.';
				}
			} else {
				$resp['msg'] = 'Failed to save the resized image.';
			}
		} else {
			$resp['msg'] = 'No image uploaded.';
		}
	
		return json_encode($resp);
	}
		
	
	
	public function verify_student(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `student_list` set `status` = 1 where id = $id");
		if($update){
			$this->settings->set_flashdata('success','Student Account has verified successfully.');
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	


public function delete_student(){
	extract($_POST);
	$avatar = $this->conn->query("SELECT avatar FROM student_list where id = '{$id}'")->fetch_array()['avatar'];
	$qry = $this->conn->query("DELETE FROM student_list where id = $id");
	if($qry){
		$avatar = explode("?",$avatar)[0];
		$this->settings->set_flashdata('success','Student Details successfully deleted.');
		if(is_file(base_app.$avatar))
			unlink(base_app.$avatar);
		$resp['status'] = 'success';
	}else{
		$resp['status'] = 'failed';
	}
	return json_encode($resp);
}
}


$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	case 'save_student':
		echo $users->save_student();
	break;
	case 'delete_student':
		echo $users->delete_student();
	break;
	case 'verify_student':
		echo $users->verify_student();
	break;
	default:
		// echo $sysset->index();
		break;
}