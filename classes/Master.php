<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_department(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `department_list` set {$data} ";
		}else{
			$sql = "UPDATE `department_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `department_list` where `name`='{$name}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Department Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Department details successfully added.";
				else
					$resp['msg'] = "Department details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_department(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `department_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Department has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_curriculum(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `curriculum_list` set {$data} ";
		}else{
			$sql = "UPDATE `curriculum_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `curriculum_list` where `name`='{$name}' and `department_id` = '{department_id}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Curriculum Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Curriculum details successfully added.";
				else
					$resp['msg'] = "Curriculum details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_curriculum(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `curriculum_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Curriculum has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_archive() {
		$resp = array();
	
		if(empty($_POST['id'])) {
			// Generating archive code
			$pref = date("Ym");
			$code = sprintf("%'.04d", 1);
			
			while(true) {
				$check = $this->conn->query("SELECT * FROM `archive_list` WHERE archive_code = '{$pref}{$code}'")->num_rows;
				
				if($check > 0) {
					$code = sprintf("%'.04d", abs($code) + 1);
				} else {
					break;
				}
			}
			
			$_POST['archive_code'] = $pref . $code;
			$_POST['student_id'] = $this->settings->userdata('id');
			$_POST['curriculum_id'] = $this->settings->userdata('curriculum_id');
		}
		
		// Sanitize and process inputs
		foreach($_POST as $k => $v) {
			if(!in_array($k, array('id')) && !is_array($_POST[$k])) {
				if(!is_numeric($v)) {
					$v = $this->conn->real_escape_string($v);
				}
				$data[$k] = $v;
			}
		}
		
		// Handle abstract and members fields
		if(isset($data['abstract'])) {
			$data['abstract'] = htmlentities($data['abstract']);
		}
		if(isset($data['members'])) {
			$data['members'] = htmlentities($data['members']);
		}
		
		// Check and handle PDF upload
		if(isset($_FILES['pdf']) && !empty($_FILES['pdf']['tmp_name'])) {
			$type = mime_content_type($_FILES['pdf']['tmp_name']);
			
			if($type != "application/pdf") {
				$resp['status'] = "failed";
				$resp['msg'] = "Invalid Document File Type.";
				return json_encode($resp);
			} else {
				$fname = 'uploads/pdf/archive-'.$data['id'].'.pdf';
				$dir_path = base_app . $fname;
				
				if(move_uploaded_file($_FILES['pdf']['tmp_name'], $dir_path)) {
					$data['document_path'] = $fname . '?v=' . time();
				} else {
					$resp['msg'] = "Failed to upload Document File.";
				}
			}
		}
		
		// Check and handle image upload
		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])) {
			$type = mime_content_type($_FILES['img']['tmp_name']);
			$allowed = array('image/png', 'image/jpeg');
			
			if(!in_array($type, $allowed)) {
				$resp['status'] = "failed";
				$resp['msg'] = "Invalid Image File Type.";
			} else {
				$fname = 'uploads/banners/archive-'.$data['id'].'.png';
				$dir_path = base_app . $fname;
				
				// Resize and save image
				$new_width = 1280;
				$new_height = 720;
				
				list($width, $height) = getimagesize($_FILES['img']['tmp_name']);
				$t_image = imagecreatetruecolor($new_width, $new_height);
				$gdImg = ($type == 'image/png') ? imagecreatefrompng($_FILES['img']['tmp_name']) : imagecreatefromjpeg($_FILES['img']['tmp_name']);
				
				if($gdImg && imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height)) {
					if(is_file($dir_path)) {
						unlink($dir_path);
					}
					$uploaded_img = imagepng($t_image, $dir_path);
					imagedestroy($gdImg);
					imagedestroy($t_image);
					
					if($uploaded_img) {
						$data['banner_path'] = $fname . '?v=' . time();
					} else {
						$resp['msg'] = "Failed to upload Image File.";
					}
				} else {
					$resp['msg'] = "Failed to process Image File.";
				}
			}
		}
		
		// Prepare SQL query
		if(empty($_POST['id'])) {
			$sql = "INSERT INTO `archive_list` SET ";
		} else {
			$sql = "UPDATE `archive_list` SET ";
		}
		
		$sql .= implode(', ', array_map(function($k, $v) {
			return "`{$k}` = '{$v}'";
		}, array_keys($data), $data));
		
		if(!empty($_POST['id'])) {
			$sql .= " WHERE id = '{$_POST['id']}'";
		}
		
		// Execute query
		$save = $this->conn->query($sql);
		
		if($save) {
			$aid = !empty($_POST['id']) ? $_POST['id'] : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['id'] = $aid;
			
			if(empty($_POST['id'])) {
				$resp['msg'] = "Archive was successfully submitted.";
			} else {
				$resp['msg'] = "Archive details were updated successfully.";
			}
			
			// Update paths in database
			if(isset($data['document_path'])) {
				$this->conn->query("UPDATE `archive_list` SET `document_path` = '{$data['document_path']}' WHERE id = '{$aid}'");
			}
			if(isset($data['banner_path'])) {
				$this->conn->query("UPDATE `archive_list` SET `banner_path` = '{$data['banner_path']}' WHERE id = '{$aid}'");
			}
			
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred.";
			$resp['err'] = $this->conn->error . " [{$sql}]";
		}
		
		if($resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
		
		return json_encode($resp);
	}
	
	function update_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `archive_list` set status  = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = "Archive status has successfully updated.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred. Error: " .$this->conn->error;
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_department':
		echo $Master->save_department();
	break;
	case 'delete_department':
		echo $Master->delete_department();
	break;
	case 'save_curriculum':
		echo $Master->save_curriculum();
	break;
	case 'delete_curriculum':
		echo $Master->delete_curriculum();
	break;
	case 'save_archive':
		echo $Master->save_archive();
	break;
	case 'delete_archive':
		echo $Master->delete_archive();
	break;
	case 'update_status':
		echo $Master->update_status();
	break;
	// case 'save_payment':
	// 	echo $Master->save_payment();
	// break;
	// case 'delete_payment':
	// 	echo $Master->delete_payment();
	// break;
	default:
		// echo $sysset->index();
		break;
}