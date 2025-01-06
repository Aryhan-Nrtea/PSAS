<?php 
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']); // Escape the ID for security

    // Fetch department details
    $qry = $conn->query("SELECT * FROM department_list WHERE `status` = 1 AND id = '{$id}'");
    if ($qry->num_rows > 0) {
        $department = $qry->fetch_assoc();
    } else {
        echo "<script> alert('Unknown Department ID'); location.replace('./') </script>";
        exit; // Ensure script execution stops here
    }
} else {
    echo "<script> alert('Department ID is required'); location.replace('./') </script>";
    exit; // Ensure script execution stops here
}
?>
<div class="content py-2">
    <div class="col-12">
        <div class="card card-outline card-primary shadow rounded-0" style="border-color: #800000;">
            <div class="card-body rounded-0">
                <h2>Program Studies of <?= htmlspecialchars($department['name'] ?? "") ?></h2>
                <p><small><?= htmlspecialchars($department['description'] ?? "") ?></small></p>
                <hr class="bg-navy">

                <!-- Dropdown Filters -->
                <div class="filter-container mb-3">
                    <select id="filter-year" class="form-control">
                        <option value="">Year</option>
                        <?php
                        // Retrieve filters from query parameters
                        $year_filter = isset($_GET['year']) ? $_GET['year'] : '';
                        
                        $letter_filter = isset($_GET['letter']) ? $_GET['letter'] : '';


                        // Generate year options dynamically for the past 5 years
                        for ($i = 0; $i < 5; $i++):
                            $year_option = date("Y", strtotime(date("Y") . " -{$i} years"));
                        ?>
                            <option value="<?= htmlspecialchars($year_option) ?>" <?= $year_filter === $year_option ? "selected" : "" ?>>
                                <?= htmlspecialchars($year_option) ?>
                            </option>
                        <?php endfor; ?>
                    </select>

                  

                    <!-- Letter Filter -->
                    <select id="filter-letter" class="form-control mt-2">
                        <option value="">Title (A-Z)</option>
                        <?php foreach (range('A', 'Z') as $letter): ?>
                            <option value="<?= htmlspecialchars($letter) ?>" <?= isset($_GET['letter']) && $_GET['letter'] == $letter ? 'selected' : '' ?>>
                                <?= htmlspecialchars($letter) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Display Selected Year -->
                    <p class="text-muted mt-2">Selected Year: <b><?= htmlspecialchars($year_filter) ?></b></p>
                   
                    <p class="text-muted mt-2">Selected Letter: <b><?= htmlspecialchars($letter_filter) ?></b></p>
                </div>

                <?php 
                $limit = 10;
                $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
                $offset = $limit * ($page - 1);

                // Apply year and date filter in SQL query
                $year_filter_sql = $year_filter ? "AND YEAR(year) = '{$year_filter}'" : "";
                
                $letter_filter_sql = $letter_filter ? "AND title LIKE '{$letter_filter}%'" : "";

                // Retrieve students and their data
                $students_query = $conn->query("SELECT * FROM student_list WHERE id IN (SELECT student_id FROM archive_list WHERE `status` = 1 AND curriculum_id IN (SELECT id FROM curriculum_list WHERE department_id = '{$id}'))");
                $students_data = $students_query->fetch_all(MYSQLI_ASSOC);

                $student_arr = array_map(function($student) {
                    return $student['firstname'] . ' ' . $student['lastname'];
                }, $students_data);

                $student_arr = array_combine(array_column($students_data, 'id'), $student_arr);

                // Count total number of projects with filters
                $count_all_query = $conn->query("SELECT COUNT(*) FROM archive_list WHERE `status` = 1 AND curriculum_id IN (SELECT id FROM curriculum_list WHERE department_id = '{$id}') {$year_filter_sql} {$letter_filter_sql}");
                $count_all = $count_all_query->fetch_row()[0];
                $pages = ceil($count_all / $limit);

                // Fetch archives with filters and pagination
                $archives_query = $conn->query("SELECT * FROM archive_list WHERE `status` = 1 AND curriculum_id IN (SELECT id FROM curriculum_list WHERE department_id = '{$id}') {$year_filter_sql}  {$letter_filter_sql} ORDER BY title ASC LIMIT {$limit} OFFSET {$offset}");?>
                <div class="list-group">
                    <?php while ($row = $archives_query->fetch_assoc()): ?>
                        <?php
                        $row['abstract'] = strip_tags(html_entity_decode($row['abstract']));
                        $formatted_date = date("F d, Y h:i A", strtotime($row['date_created']));
                        ?>
                        <a href="./?page=view_archive&id=<?= htmlspecialchars($row['id']) ?>" class="text-decoration-none text-dark list-group-item list-group-item-action">
                            <div class="row">
                                <div class="col-lg-4 col-md-5 col-sm-12 text-center">
                                    <img src="<?= validate_image($row['banner_path']) ?>" class="banner-img img-fluid " alt="Banner Image">
                                </div>
                                <div class="col-lg-8 col-md-7 col-sm-12">
                                    <h3 class="text-navy"><b><?= htmlspecialchars($row['title']) ?></b></h3>
                                    <small class="text-muted">By <b class="text-info"><?= htmlspecialchars($student_arr[$row['student_id']] ?? "N/A") ?></b></small>
                                    <p class="truncate-5"><?= htmlspecialchars($row['abstract']) ?></p>
                                    <small class="text-muted">Submitted on <?= $formatted_date ?></small>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="card-footer clearfix rounded-0">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6"><span class="text-muted">Display Items: <?= $archives_query->num_rows ?></span></div>
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <li class="page-item"><a class="page-link" href="./?page=projects_per_department&id=<?= htmlspecialchars($id) ?>&p=<?= max($page - 1, 1) ?>" <?= $page == 1 ? 'disabled' : '' ?>>«</a></li>
                                <?php for ($i = 1; $i <= $pages; $i++): ?>
                                    <li class="page-item"><a class="page-link <?= $page == $i ? 'active' : '' ?>" href="./?page=projects_per_department&id=<?= htmlspecialchars($id) ?>&p=<?= $i ?>"><?= $i ?></a></li>
                                <?php endfor; ?>
                                <li class="page-item"><a class="page-link" href="./?page=projects_per_department&id=<?= htmlspecialchars($id) ?>&p=<?= min($page + 1, $pages) ?>" <?= $page == $pages || $pages <= 1 ? 'disabled' : '' ?>>»</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const yearFilter = document.getElementById('filter-year');
    
    const letterFilter = document.getElementById('filter-letter');

    function updateFilter() {
        const year = yearFilter.value;
        
        const letter = letterFilter.value;

        const url = new URL(window.location.href);
        url.searchParams.set('year', year);
        
        url.searchParams.set('letter', letter);

        window.location.href = url.href;
    }

    yearFilter.addEventListener('change', updateFilter);
    
    letterFilter.addEventListener('change', updateFilter);
});

</script>
