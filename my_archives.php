<div class="content py-3">
    <div class="container-fluid">
        <div class="card card-outline card-primary shadow rounded-0" style="border-color: #800000;">
            <div class="card-header rounded-0">
                <h4 class="card-title">My Submitted Program Study</h4>
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <table class="table table-hover table-striped">
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="15%">
                            <col width="20%">
                            <col width="20%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date Created</th>
                                <th>Archive Code</th>
                                <th>Program Study Title</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                                $curriculum = $conn->query("SELECT * FROM curriculum_list WHERE id IN (SELECT curriculum_id FROM `archive_list` WHERE student_id = '{$_settings->userdata('id')}' )");
                                $cur_arr = array_column($curriculum->fetch_all(MYSQLI_ASSOC), 'name', 'id');
                                $qry = $conn->query("SELECT * FROM `archive_list` WHERE student_id = '{$_settings->userdata('id')}' ORDER BY unix_timestamp(`date_created`) ASC");
                                while ($row = $qry->fetch_assoc()):
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])); ?></td>
                                    <td><?php echo ($row['archive_code']); ?></td>
                                    <td><?php echo ucwords($row['title']); ?></td>
                                    <td><?php echo $cur_arr[$row['curriculum_id']]; ?></td>
                                    <td class="text-center">
                                        <?php
                                            echo $row['status'] == '1' ? "<span class='badge badge-success badge-pill'>Approved</span>" : "<span class='badge badge-secondary badge-pill'>Disapproved</span>";
                                        ?>
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>">
                                                <span class="fa fa-eye text-dark"></span> View
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="./?page=submit-archive&id=<?= $row['id'] ?>">
                                                <span class="fa fa-edit text-green"></span> Edit
                                            </a>
                                            
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
</div>

<script>
    // Handle view action
    $('.view_data').on('click', function() {
        const id = $(this).data('id');
        uni_modal("Archive Details", "admin/archives/view_archive.php?id=" + id, 'mid-large');
    });

    $(function() {
        $('.delete_data').click(function() {
            _conf("Are you sure to delete this project permanently?", "delete_archive", [$(this).data('id')]);
        });
        $('.table td, .table th').addClass('py-1 px-2 align-middle');
        $('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
    });

    function delete_archive($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_archive",
            method: "POST",
            data: { id: $id },
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp === 'object' && resp.status === 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        });
    }
</script>
