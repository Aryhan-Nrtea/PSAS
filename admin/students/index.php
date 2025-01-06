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
        border: 1px solid gray; /* Add border to table cells */
    }
    table {
        border-collapse: collapse; /* Ensures borders are merged */
        width: 100%; /* Optional: make the table take full width */
    }
    thead th {
        background-color: #f2f2f2; /* Optional: add background color for headers */
    }
</style>

<div class="card card-outline card-primary" style="border-color: #800000; margin-top: 1%;">
    <div class="card-header">
        <h3 class="card-title">List of Students</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-3">
                    <select id="filterDepartment" class="form-control">
                        <option value="">Department</option>
                        <?php 
                        $departments = $conn->query("SELECT * FROM department_list");
                        while ($dept = $departments->fetch_assoc()) {
                            echo "<option value='{$dept['id']}'>{$dept['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterCurriculum" class="form-control">
                        <option value="">Program</option>
                        <?php 
                        $curriculums = $conn->query("SELECT * FROM curriculum_list");
                        while ($curr = $curriculums->fetch_assoc()) {
                            echo "<option value='{$curr['id']}'>{$curr['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
              
            </div>
            <table class="table" id="studentTable">
                <colgroup>
                    <col width="11%">
                    <col width="11%">
                    <col width="11%">
                    <col width="11%">
                    <col width="11%">
                    <col width="11%">
                    <col width="11%">
                    <col width="11%">
                    <col width="11%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Gender</th> 
                        <th>Email</th>
                        <th>Department</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 1;
                        // Adjusted SQL query to prioritize "Not Verified" (status = 0) first
                        $qry = $conn->query("SELECT s.*, 
                            CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) AS name,
                            d.name AS department_name,
                            c.name AS curriculum_name
                            FROM student_list s
                            LEFT JOIN department_list d ON s.department_id = d.id
                            LEFT JOIN curriculum_list c ON s.curriculum_id = c.id
                            ORDER BY s.status ASC, name ASC");
                        
                        if (!$qry) {
                            die("SQL Error: " . $conn->error);
                        }

                        while($row = $qry->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center">
                                <img src="<?php echo validate_image($row['avatar']); ?>" class="img-avatar img-thumbnail p-0 border-2" alt="user_avatar">
                            </td>
                            <td><?php echo ucwords($row['name']); ?></td>
                            <td><?php echo ucfirst($row['gender']); ?></td> 
                            <td><p class="m-0 truncate-1"><?php echo $row['email']; ?></p></td>
                            <td><?php echo $row['department_name']; ?></td>
                            <td><?php echo $row['curriculum_name']; ?></td>
                            <td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-pill badge-success">Verified</span>
                                <?php else: ?>
                                    <span class="badge badge-pill badge-primary">Not Verified</span>
                                <?php endif; ?>
                            </td>
                            <td align="center">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item view_details" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>">
                                        <span class="fa fa-eye text-dark"></span> View
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <?php if($row['status'] != 1): ?>
                                        <a class="dropdown-item verify_user" href="javascript:void(0)" data-id="<?= $row['id']; ?>" data-name="<?= $row['email']; ?>">
                                            <span class="fa fa-check text-primary"></span> Verify
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    <?php endif; ?>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-name="<?= $row['email']; ?>">
                                        <span class="fa fa-trash text-danger"></span> Delete
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

<script>
   $(document).ready(function() {
    // Function to filter table
    function filterTable() {
        const department = $('#filterDepartment').val();
        const curriculum = $('#filterCurriculum').val();

        $('#studentTable tbody tr').filter(function() {
            $(this).toggle(
                (department === '' || $(this).find('td:eq(5)').text() === $('#filterDepartment option:selected').text()) &&
                (curriculum === '' || $(this).find('td:eq(6)').text() === $('#filterCurriculum option:selected').text())
            );
        });
    }

    // Attach event handlers
    $('#filterDepartment, #filterCurriculum').on('change', filterTable);

    // Click event for delete action
    $('.delete_data').on('click', function(e) {
        e.preventDefault(); // Prevent default action
        _conf("Are you sure to delete <b>" + $(this).attr('data-name') + "</b> from the Student List permanently?", "delete_user", [$(this).attr('data-id')]);
    });

    // Click event for verify action
    $('.verify_user').on('click', function(e) {
        e.preventDefault(); // Prevent default action
        _conf("Are you sure to verify <b>" + $(this).attr('data-name') + "</b>?", "verify_user", [$(this).attr('data-id')]);
    });

    // Click event for viewing details
    $('.view_details').on('click', function(e) {
        e.preventDefault(); // Prevent default action
        uni_modal('Student Details', "students/view_details.php?id=" + $(this).attr('data-id'), 'mid-large');
    });

    $('.table').dataTable({
        pageLength: 50, // Set the default number of entries to display
        columnDefs: [
            { orderable: false, targets: 6 }
        ],
    });
    
});

    function delete_user($id){
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Users.php?f=delete_student",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp){
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        });
    }

    function verify_user($id){
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Users.php?f=verify_student",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp){
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        });
    }
</script>
