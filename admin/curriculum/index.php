<style>
    .img-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        object-position: center center;
        border-radius: 100%;
    }
    th {
        text-align: center;
        padding: 10px;
        background-color: #f2f2f2; /* Optional: add background color for headers */
    }
    tbody td {
        text-align: center;
       
    }
   
</style>

<div class="card card-outline card-primary" style="border-color: #800000; margin-top: 1%;">
	<div class="card-header">
		<h3 class="card-title">List of Programs</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-default btn-primary" style="background-color: #d30707; color: white"><span class="fas fa-plus"></span>  Add New Program</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover">
				<colgroup>
					<col width="1%">
					<!-- <col width="20%"> -->
					<col width="5%">
					<col width="5%">
					<col width="5%">
					<col width="1%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<!-- <th>Date Created</th> -->
						<th>Department</th>
						<th>Program</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT c.*, d.name as department from `curriculum_list` c inner join `department_list` d on c.department_id = d.id order by c.`name` asc");
						while($row = $qry->fetch_assoc()):
						
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<!-- <td class=""><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td> -->
							<td class=""><?php echo $row['department'] ?></td>
							<td><?php echo ucwords($row['name']) ?></td>
							<td class="text-center">
                                <?php
                                    switch($row['status']){
                                        case '1':
                                            echo "<span class='badge badge-success badge-pill'>Active</span>";
                                            break;
                                        case '0':
                                            echo "<span class='badge badge-secondary badge-pill'>Inactive</span>";
                                            break;
                                    }
                                ?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
        $('#create_new').click(function(){
			uni_modal("Course Details","curriculum/manage_curriculum.php")
		})
        $('.edit_data').click(function(){
			uni_modal("Course Details","curriculum/manage_curriculum.php?id="+$(this).attr('data-id'))
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Course permanently?","delete_curriculum",[$(this).attr('data-id')])
		})
		$('.view_data').click(function(){
			uni_modal("Course Details","curriculum/view_curriculum.php?id="+$(this).attr('data-id'))
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
	})
	function delete_curriculum($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_curriculum",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>