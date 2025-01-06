<style>
    .img-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        object-position: center center;
        border-radius: 100%;
    }
    th, td {
        text-align: center;
        padding: 10px;
        border: 1px solid gray;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    thead th {
        background-color: #f2f2f2;
    }
</style>

<div class="card card-outline card-primary" style="border-color: #800000; margin-top: 1%;">
    <div class="card-header">
        <h3 class="card-title">List of Program Study Archives</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-3">
                    <select id="filter-year" class="form-control">
                        <option value="">Year</option>
                        <?php for ($i = 0; $i < 5; $i++): 
                            $year_option = date("Y", strtotime(date("Y") . " -{$i} years")); ?>
                            <option value="<?= $year_option ?>"><?= $year_option ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filter-course" class="form-control">
                        <option value="">All Program</option>
                        <?php
                        $courses = $conn->query("SELECT DISTINCT name FROM curriculum_list");
                        while ($course = $courses->fetch_assoc()): ?>
                            <option value="<?= $course['name'] ?>"><?= $course['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <table class="table table-hover" id="archives-table">
                <colgroup>
                    <col width="6%">
                    <col width="15%">
                    <col width="11%">
                    <col width="15%">
                    <col width="18%">
                    <col width="15%">
                    <col width="11%">
                    <col width="11%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date Submitted</th>
                        <th>Year</th>
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
                    // Adjusted SQL query to prioritize disapproved before approved
                    $qry = $conn->query("SELECT al.*, cl.name as course_name FROM `archive_list` al JOIN `curriculum_list` cl ON al.curriculum_id = cl.id ORDER BY al.status ASC, al.archive_code ASC");
                    while ($row = $qry->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])); ?></td>
                            <td><?php echo date("Y", strtotime($row['date_created'])); ?></td>
                            <td><?php echo ($row['archive_code']); ?></td>
                            <td><?php echo ucwords($row['title']); ?></td>
                            <td><?php echo $row['course_name']; ?></td>
                            <td class="text-center">
                                <?php
                                switch ($row['status']) {
                                    case '1':
                                        echo "<span class='badge badge-success badge-pill'>Approved</span>";
                                        break;
                                    case '0':
                                        echo "<span class='badge badge-secondary badge-pill'>Disapproved</span>";
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
                                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item update_status" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-status="<?php echo $row['status']; ?>"><span class="fa fa-check text-dark"></span> Update Status</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Function to filter table
    function filterTable() {
        const year = $('#filter-year').val();
        const course = $('#filter-course').val();

        $('#archives-table tbody tr').filter(function() {
            const rowYear = $(this).find('td:nth-child(3)').text();
            const rowCourse = $(this).find('td:nth-child(6)').text();

            $(this).toggle(
                (year === '' || rowYear === year) &&
                (course === '' || rowCourse === course)
            );
        });
    }

    // Attach event handlers for filtering
    $('#filter-year, #filter-course').on('change', filterTable);

    // Handle delete action
    $('.delete_data').on('click', function() {
        const id = $(this).data('id');
        _conf("Are you sure to delete this project permanently?", "delete_archive", [id]);
    });

    // Handle update status action
    $('.update_status').on('click', function() {
        const id = $(this).data('id');
        const status = $(this).data('status');
        uni_modal("Update Details", "archives/update_status.php?id=" + id + "&status=" + status);
    });

    // Handle view action
    $('.view_data').on('click', function() {
        const id = $(this).data('id');
        uni_modal("Archive Details", "archives/view_archive.php?id=" + id, 'mid-large');
    });

    // Initialize DataTable
    $('.table').dataTable({
        pageLength: 50,
        columnDefs: [
            { orderable: false, targets: 6 },
            { targets: 3, orderData: [3] }
        ],
    });
});

// Function to delete archive
function delete_archive(id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_archive",
        method: "POST",
        data: { id: id },
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
