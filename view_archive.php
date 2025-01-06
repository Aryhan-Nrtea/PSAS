<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT a.* FROM `archive_list` a where a.id = '{$_GET['id']}'");
    if($qry->num_rows){
        foreach($qry->fetch_array() as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
    $submitted = "N/A";
    if(isset($student_id)){
        $student = $conn->query("SELECT * FROM student_list where id = '{$student_id}'");
        if($student->num_rows > 0){
            $res = $student->fetch_array();
            $submitted = $res['firstname'] . ' ' . $res['lastname'];

        }
    }
}
?>
<style>
   #document_field {
    width: calc(100%);
    height: 80vh; /* Set a specific height */
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 10px;
    overflow: hidden; /* Prevent overflow from iframe */
    margin: 0 auto; /* Center the document field */
}


#banner-img {
    object-fit: scale-down;
    object-position: center center;
    height: auto; /* Maintain aspect ratio */
    width: calc(80%);
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px; /* Add some space below the image */
}
#sheet-img {
    object-fit: scale-down;
    object-position: center center;
    height: auto; /* Maintain aspect ratio */
    width: calc(80%);
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px; /* Add some space below the image */
}
.image-container {
    width: 100%; /* Set a fixed width */
    margin: 0 auto; /* Center the container */
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 10px; /* Add some padding */
    text-align: center; /* Center the image */
    margin-bottom: 3%;
}
._custom-container {
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 15px; /* Add some padding */
    margin-bottom: 20px; /* Space below the container */
}

       
        
</style>
<div class="content py-4">
    <div class="col-12">
        <div class="card card-outline card-primary shadow rounded-0" style="border-color: #800000;">
            <div class="card-header">
                <h3 class="card-title">
                    Archive - <?= isset($archive_code) ? $archive_code : "" ?>
                </h3>
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <h2><b><?= isset($title) ? $title : "" ?></b></h2>
                    <small class="text-muted">Submitted by <b class="text-info"><?= $submitted ?></b> on  <?= date("F d, Y h:i A",strtotime($date_created)) ?></small>
                    <?php if(isset($student_id) && $_settings->userdata('login_type') == "2" && $student_id == $_settings->userdata('id')): ?>
                        <div class="form-group">
                            <!-- <a href="./?page=submit-archive&id=<?= isset($id) ? $id : "" ?>" class="btn btn-default bg-navy btn-sm"><i class="fa fa-edit"></i> Edit</a> -->
                            <!-- <button type="button" data-id = "<?= isset($id) ? $id : "" ?>" class="btn btn-flat btn-danger btn-sm delete-data"><i class="fa fa-trash"></i> Delete</button> -->
                        </div>
                    <?php endif; ?>
                    <hr class="bg-navy">
                    
                    <div class="image-container">
                    <legend style="font-weight: bold; text-align: center;">Hardbound Cover Image</legend>
                    <center>
                        <img src="<?= validate_image(isset($banner_path) ? $banner_path : "") ?>" alt="Banner Image" id="banner-img" class="img-fluid banner-img">
                    </center>
                    </div>

                    <div class="image-container">
                    <fieldset>
                        <legend style="font-weight: bold; text-align: center; margin-top: 5%;">Year</legend>
                        <div class="text-center"><large><?= isset($year) ? $year : "----" ?></large></div>
                    </fieldset>
                    </div>
                    
                    <div class="image-container">
                    <fieldset>
                        <legend style="font-weight: bold; text-align: center; margin-top: 5%;">Abstract</legend>
                        <div class="text-center"><large><?= isset($abstract) ? html_entity_decode($abstract) : "" ?></large></div>
                    </fieldset>
                    </div>

                    <div class="image-container">
                    <fieldset>
                        <legend style="font-weight: bold; text-align: center; margin-top: 5%;">Program Study Members</legend>
                        <div class="text-center"><large><?= isset($members) ? html_entity_decode($members) : "" ?></large></div>
                    </fieldset>
                    </div>

                        <div class="image-container">
                        <legend style="font-weight: bold; text-align: center;">Program Study Document</legend>
                        
                            <iframe src="<?= isset($document_path) ? base_url.$document_path : "" ?>" frameborder="0" id="document_field" class="text-center w-100">Loading Document ...</iframe>
                       
                        </div>
                   
                        
                       
                    <div class="image-container">
                    <legend style="font-weight: bold; text-align: center;">Program Study Approval Sheet</legend>
                    <center>
                        <img src="<?= validate_image(isset($sheet_path) ? $sheet_path : "") ?>" alt="Banner Image" id="sheet-img" class="img-fluid banner-img">
                    </center>
                    </div>

                   
                   
                    

                   
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.delete-data').click(function(){
            _conf("Are you sure to delete <b>Archive-<?= isset($archive_code) ? $archive_code : "" ?></b>","delete_archive")
        })
    })
    function delete_archive(){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_archive",
			method:"POST",
			data:{id: "<?= isset($id) ? $id : "" ?>"},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.replace("./");
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>