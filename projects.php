<div class="content py-2">
    <div class="col-12">
        <div class="card card-outline card-primary shadow rounded-0" style="border-color: #800000;">
            <div class="card-body rounded-0">
                <h2>Program Studies List</h2>
                <hr class="bg-navy">
                
                <!-- Dropdown Filters -->
                <div class="filter-container mb-3">
                    <select id="filter-year" class="form-control">
                        <option value="">Year</option>
                        <?php
                        // Retrieve filters from query parameters
                        $year_filter = isset($_GET['year']) ? $_GET['year'] : '';
                        $letter_filter = isset($_GET['letter']) ? $_GET['letter'] : '';

                        // Generate year options dynamically
                        for ($i = 0; $i < 5; $i++):
                            $year_option = date("Y", strtotime(date("Y") . " -{$i} years"));
                        ?>
                            <option value="<?= $year_option ?>" <?= isset($year_filter) && $year_filter == $year_option ? "selected" : "" ?>>
                                <?= $year_option ?>
                            </option>
                        <?php endfor; ?>
                    </select>

                    <div class="filter-container mb-3">
                        <select id="filter-letter" class="form-control mt-2">
                            <option value="">Title (A-Z)</option>
                            <?php foreach (range('A', 'Z') as $letter): ?>
                                <option value="<?= $letter ?>" <?= isset($_GET['letter']) && $_GET['letter'] == $letter ? 'selected' : '' ?>>
                                    <?= $letter ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Display Selected Filters -->
                    <p class="text-muted mt-2">Selected Year: <b><?= htmlspecialchars($year_filter) ?></b></p>
                    <p class="text-muted mt-2">Selected Letter: <b><?= htmlspecialchars($letter_filter) ?></b></p>
                </div>

                <?php 
                $limit = 10;
                $page = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
                $offset = $limit * ($page - 1);
                $paginate = " LIMIT {$limit} OFFSET {$offset}";
                $isSearch = isset($_GET['q']) ? "&q={$_GET['q']}" : "";
                $search = "";
                
                if (isset($_GET['q'])) {
                    $keyword = $conn->real_escape_string($_GET['q']);
                    $search = " AND (title LIKE '%{$keyword}%' 
                                    OR members LIKE '%{$keyword}%' 
                                    OR curriculum_id IN (SELECT id FROM curriculum_list 
                                                         WHERE name LIKE '%{$keyword}%' 
                                                         OR description LIKE '%{$keyword}%') 
                                    OR curriculum_id IN (SELECT id FROM curriculum_list 
                                                         WHERE department_id IN (SELECT id FROM department_list 
                                                                                  WHERE name LIKE '%{$keyword}%' 
                                                                                  OR description LIKE '%{$keyword}%'))) ";
                }
                
               
                
                
                // Apply year filter
                if ($year_filter) {
                    $search .= " AND YEAR(year) = '{$year_filter}' "; 
                }
               
                // Apply letter filter if provided
                if ($letter_filter) {
                    $search .= " AND title LIKE '{$letter_filter}%' ";
                }

                // Fetch students
                $students = $conn->query("SELECT * FROM student_list 
                                          WHERE id IN (SELECT student_id FROM archive_list WHERE status = 1 {$search})");
                $students_data = $students->fetch_all(MYSQLI_ASSOC);

                // Create student array
                $student_arr = array_map(function($student) {
                    return $student['firstname'] . ' ' . $student['lastname'];
                }, $students_data);

                $student_arr = array_combine(array_column($students_data, 'id'), $student_arr);

                // Count all archives
                $count_all = $conn->query("SELECT * FROM archive_list WHERE status = 1 {$search}")->num_rows;    
                $pages = ceil($count_all / $limit);
                
                // Fetch archives ordered by title
                $archives = $conn->query("SELECT * FROM archive_list 
                                          WHERE status = 1 {$search} 
                                          ORDER BY title ASC 
                                          {$paginate}");    
                ?>
                <?php if (!empty($isSearch)): ?>
                    <h3 class="text-center"><b>Search Result for "<?= htmlspecialchars($keyword) ?>" keyword</b></h3>
                <?php endif ?>
                <div class="list-group">
                    <?php 
                    while ($row = $archives->fetch_assoc()):
                        $row['abstract'] = strip_tags(html_entity_decode($row['abstract']));
                        $formatted_date = date("F d, Y h:i A", strtotime($row['date_created']));
                    ?>
                    <a href="./?page=view_archive&id=<?= $row['id'] ?>" class="text-decoration-none text-dark list-group-item list-group-item-action">
                        <div class="row">
                            <div class="col-lg-4 col-md-5 col-sm-12 text-center">
                                <img src="<?= validate_image($row['banner_path']) ?>" class="banner-img img-fluid" alt="Banner Image">
                            </div>
                            <div class="col-lg-8 col-md-7 col-sm-12">
                                <h3 class="text-navy"><b><?php echo htmlspecialchars($row['title']) ?></b></h3>
                                <small class="text-muted">By <b class="text-info"><?= isset($student_arr[$row['student_id']]) ? htmlspecialchars($student_arr[$row['student_id']]) : "N/A" ?></b></small>
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
                        <div class="col-md-6"><span class="text-muted">Display Items: <?= $archives->num_rows ?></span></div>
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <li class="page-item"><a class="page-link" href="./?page=projects<?= $isSearch ?>&year=<?= $year_filter ?>&p=<?= $page - 1 ?>" <?= $page == 1 ? 'disabled' : '' ?>>«</a></li>
                                <?php for ($i = 1; $i <= $pages; $i++): ?>
                                <li class="page-item"><a class="page-link <?= $page == $i ? 'active' : '' ?>" href="./?page=projects<?= $isSearch ?>&year=<?= $year_filter ?>&p=<?= $i ?>"><?= $i ?></a></li>
                                <?php endfor; ?>
                                <li class="page-item"><a class="page-link" href="./?page=projects<?= $isSearch ?>&year=<?= $year_filter ?>&p=<?= $page + 1 ?>" <?= $page == $pages ? 'disabled' : '' ?>>»</a></li>
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
