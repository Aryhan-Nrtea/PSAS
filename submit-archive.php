<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * FROM `archive_list` where id = '{$_GET['id']}'");
    if($qry->num_rows){
        foreach($qry->fetch_array() as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
    if(isset($student_id)){
        if($student_id != $_settings->userdata('id')){
            echo "<script> alert('You don't have an access to this page'); location.replace('./'); </script>";
        }
    }
}
?>
<style>
    .banner-img{
		object-fit:scale-down;
		object-position:center center;
        height: 50vh;
        width:calc(100%);

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
    <div class="card card-outline card-primary shadow rounded-0" style="border-color: #800000;">
        <div class="card-header rounded-0">
            <h5 class="card-title"><?= isset($id) ? "Update Archive- $archive_code Details" : "Program Study Archive" ?></h5>
        </div>

        <div class="card-body rounded-0">
            <div class="container-fluid">
                <form action="" id="archive-form">
                    <input type="hidden" name="id" value="<?= isset($id) ? $id : "" ?>">

                    <div class="row">
                        <div class="col-lg-12">
                        <div class="_custom-container">
                            <div class="form-group">
                                <label for="title" class="control-label text-navy">Program Study Title</label>
                                <input type="text" name="title" id="title" autofocus placeholder="Program Study Title" class="form-control form-control-border" value="<?= isset($title) ?$title : "" ?>" required>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                        <div class="_custom-container">
                            <div class="form-group">
                                <label for="year" class="control-label text-navy">Year</label>
                                <select name="year" id="year" class="form-control form-control-border" required>
                                    <?php 
                                        for($i= 0;$i < 5; $i++):
                                    ?>
                                    <option <?= isset($year) && $year == date("Y",strtotime(date("Y")." -{$i} years")) ? "selected" : "" ?>><?= date("Y",strtotime(date("Y")." -{$i} years")) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
    <div class="col-lg-12">
        <div class="_custom-container">
            <div class="form-group">
                <label for="abstract" class="control-label text-navy">Abstract</label>
                <textarea rows="3" name="abstract" id="abstract" placeholder="abstract" class="form-control form-control-border summernote" required><?= isset($abstract) ? html_entity_decode($abstract) : "" ?></textarea>
            </div>
        </div>
    </div>
</div>

                    <div class="row">
                        <div class="col-lg-12">
                        <div class="_custom-container">
                            <div class="form-group">
                                <label for="members" class="control-label text-navy">Program Study Members</label>
                                <textarea rows="3" name="members" id="members" placeholder="members" class="form-control form-control-border summernote-list-only" required><?= isset($members) ? html_entity_decode($members) : "" ?></textarea>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                        <div class="_custom-container">
                            <div class="form-group">
                                <label for="img" class="control-label text-navy">Hardbound Cover Image</label>
                                <input type="file" id="img" name="img" class="form-control form-control-border" accept="image/png,image/jpg,image/jpeg" onchange="displayImg(this,$(this))" <?= !isset($id) ? "required" : "" ?>>
                            </div>

                            <div class="form-group text-center">
                                <img src="<?= validate_image(isset($banner_path) ? $banner_path : "") ?>" alt="My Avatar" id="cimg" class="img-fluid banner-img">
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                        <div class="_custom-container">
                            <div class="form-group">
                                <label for="pdf" class="control-label text-navy">Program Study Document (PDF File Only)</label>
                                <input type="file" id="pdf" name="pdf" class="form-control form-control-border" accept="application/pdf" <?= !isset($id) ? "required" : "" ?>>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                        <div class="_custom-container">
                        <div class="form-group">
                                <label for="img" class="control-label text-navy">Program Study Approval Sheet</label>
                                <input type="file" id="sheet" name="sheet" class="form-control form-control-border" accept="image/png,image/jpg,image/jpeg" onchange="displaySheet(this,$(this))" <?= !isset($id) ? "required" : "" ?>>
                            </div>

                            <div class="form-group text-center">
                                <img src="<?= validate_image(isset($sheet_path) ? $sheet_path : "") ?>" alt="My Avatar" id="cimg1" class="img-fluid banner-img">
                            </div>
                        </div>
                        </div>
                    </div>
                    
                    <!-- <div class="row">
                        <div class="col-lg-12">
                        <div class="_custom-container">
                            <div class="form-group">
                                  
                                    <label for="disclaimer" class="control-label text-navy">Disclaimer Notice</label>
                                    <div class="form-group">
                                    <input type="checkbox" name="disclaimer" id="disclaimer" value="1">
                                       
                                            I am responsible for submitting an accurate and complete softcopy of our program study. Failure to comply with submission guidelines may result in academic penalties, and could delay my graduation.

                                            I will ensure my submission meets all requirements. For assistance, I will contact my supervisor.

                                            By submitting, I acknowledge and accept these terms.
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                    </div> -->
                                   

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group text-center">
                                <button class="btn btn-default btn-primary" style="background-color: #d30707; color: white;">Submit</button>
                                <a href="./?page=profile" class="btn btn-default btn-primary bg-navy"> Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    
    function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
            $('#cimg').attr('src', "<?= validate_image(isset($avatar) ? $avatar : "") ?>");
        }
	}
    function displaySheet(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg1').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
            $('#cimg1').attr('src', "<?= validate_image(isset($avatar) ? $avatar : "") ?>");
        }
	}
    $(function(){
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                ['insert', ['link', 'picture']],
                [ 'view', [ 'undo', 'redo', 'help' ] ]
            ]
        })
        $('.summernote-list-only').summernote({
            height: 200,
            toolbar: [
                [ 'font', [ 'bold', 'italic', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ]
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul' ] ],
                [ 'view', [ 'undo', 'redo', 'help' ] ]
            ]
        })
        // Archive Form Submit
        $('#archive-form').submit(function(e){
            e.preventDefault()
            var _this = $(this)
                $(".pop-msg").remove()
            var el = $("<div>")
                el.addClass("alert pop-msg my-2")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_archive",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType:'json',
                error:err=>{
                    console.log(err)
                    el.text("An error occured while saving the data")
                    el.addClass("alert-danger")
                    _this.prepend(el)
                    el.show('slow')
                    end_loader()
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.href= "./?page=view_archive&id="+resp.id
                    }else if(!!resp.msg){
                        el.text(resp.msg)
                        el.addClass("alert-danger")
                        _this.prepend(el)
                        el.show('show')
                    }else{
                        el.text("An error occured while saving the data")
                        el.addClass("alert-danger")
                        _this.prepend(el)
                        el.show('show')
                    }
                    end_loader();
                    $('html, body').animate({scrollTop: 0},'fast')
                }
            })
        })
    })
</script>