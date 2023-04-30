<?php
include '../common/access.php';
include '../common/db_connect.php';


$all_bridges = $conn->query("SELECT LTP.*,LTPF.AttachmentName FROM lita.tblPosts LTP LEFT JOIN lita.tblPostFiles LTPF ON LTP.PostID = LTPF.PostID WHERE PostType = 'news' ORDER BY CreatedAt DESC;");
?>

<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from demo.plainadmin.com/datatables.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Jan 2023 17:56:06 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<?php
include 'head.php';
?>

<body>
  <!-- ======== sidebar-nav start =========== -->
  <?php
  include 'side_bar.php';
  ?>
  <div class="overlay"></div>
  <!-- ======== sidebar-nav end =========== -->

  <!-- ======== main-wrapper start =========== -->
  <main class="main-wrapper">
    <!-- ========== header start ========== -->
    <?php
    include 'header.php';
    ?>
    <?php

    ////delete post ///////
    if (isset($_POST['delete_post'])) {
      $postid = $_POST['postid'];

      //DELETING A FILE 
      $sql = "SELECT AttachmentName FROM lita.tblPostFiles WHERE PostID = '$postid';";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $fileName = "";
      while ($row = $result->fetch_assoc()) {
        $fileName = $row["AttachmentName"];
      }

      if (unlink('../upload/' . $fileName)) {
        $conn->query("DELETE FROM lita.tblPosts WHERE PostID = '$postid';");
        $message = "successfully deleted <b>" . $postid . "</b>";

        echo '<script language="javascript">'
          . ';location.href="news.php"'
          . '</script>';
      }
    }

    ?>
    <!-- ========== header end ========== -->

    <!-- ========== table components start ========== -->
    <section class="table-components">
      <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="title mb-30">
                <h2>All Bridges</h2>
              </div>
            </div>
            <!-- end col -->
          </div>
          <!-- end row -->
        </div>
        <!-- ========== title-wrapper end ========== -->
        <div class="tabs-wrapper">
          <div class="row">
            <div class="col-lg-12">
              <div class="tab-style-2 card-style mb-30">

                <div class="tab-content" id="nav-tabContent2">
                  <div class="tab-pane fade show active" id="news">
                    <a href="add-news.php" class="btn primary-btn"> <span class=" lni lni-plus"></span> Add
                      News</a><br><br><br>
                    <div class="table-responsive">
                      <table id="table" class="table">
                        <thead>
                          <tr>
                            <th>
                              <h6>#</h6>
                            </th>
                            <th>
                              <h6>Bridge Name</h6>
                            </th>
                            <th>
                              <h6>Location</h6>
                            </th>
                            <th>
                              <h6>Status</h6>
                            </th>
                            <th>
                              <h6>Road Status</h6>
                            </th>
                            <th data-type="date" data-format="YYYY/MM/DD">
                              <h6>Action</h6>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $serial = 1;
                          while ($row = $all_bridges->fetch_assoc()) { ?>
                            <tr>
                              <td>
                                <?= $serial; ?>
                              </td>
                              <td>
                                <?= substr($row['Title'], 0, 40); ?>
                              </td>
                              <td>
                                <?= substr($row['PostCategory'], 0, 40); ?>
                              </td>
                              <td>
                                <?= ucfirst($row['Focus']); ?>
                              </td>
                              <td>
                                <?= date('d F Y ', strtotime($row['CreatedAt'])); ?>
                              </td>
                              <td>
                                <div class="action">
                                  <form class="text-primary"
                                    action="add-news.php?action=editPost&amp;qwert=<?php echo $row['PostID']; ?>"
                                    enctype="multipart/form-data" method="POST">

                                    <button type="submit" name="editpost" class="lni lni-pencil"></button>

                                  </form>
                                  | <button class="text-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal<?= $row['PostID']; ?>"><i class="lni lni-trash-can"></i>
                                  </button>
                                </div>
                              </td>

                              <!-- Delete Modal -->
                              <div class="modal fade" id="deleteModal<?= $row['PostID']; ?>" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Delete post</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      Are you sure you want to delete <b>
                                        <?= $row['Title']; ?>
                                      </b>
                                    </div>
                                    <div class="modal-footer">
                                      <form action="news.php" method="post">
                                        <button type="submit" name="delete_post" class="btn btn-primary">Yes</button>
                                        <input type="hidden" name="postid" value="<?= $row['PostID']; ?>">
                                        <button type="button" class="btn btn-secondary"
                                          data-bs-dismiss="modal">No</button>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </tr>
                          <?php }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="tabContent-2-2">
                    <h5>No items available</h5>
                  </div>
                  <div class="tab-pane fade" id="tabContent-2-3">
                    <h5>No items available</h5>
                  </div>
                </div>
              </div>
              <!-- end card -->
            </div>
          </div>
        </div>
      </div>
      <!-- end container -->
    </section>
    <!-- ========== table components end ========== -->

    <!-- ========== footer start =========== -->
    <?php
    include 'footer_admin.php';
    ?>
    <!-- ========== footer end =========== -->
  </main>
  <!-- ======== main-wrapper end =========== -->

  <!-- ============ Theme Option Start ============= -->

  <div class="option-overlay"></div>
  <div class="option-box">
    <div class="option-header">
      <h5>Settings</h5>
      <button class="option-btn-close text-gray">
        <i class="lni lni-close"></i>
      </button>
    </div>
    <h6 class="mb-10">Layout</h6>
    <ul class="mb-30">
      <li><button class="leftSidebarButton active">Left Sidebar</button></li>
      <li><button class="rightSidebarButton">Right Sidebar</button></li>
    </ul>

    <h6 class="mb-10">Theme</h6>
    <ul class="d-flex flex-wrap align-items-center">
      <li>
        <button class="lightThemeButton active">
          Light Theme + Sidebar 1
        </button>
      </li>
      <li>
        <button class="lightThemeButton2">Light Theme + Sidebar 2</button>
      </li>
      <li><button class="darkThemeButton">Dark Theme + Sidebar 1</button></li>
      <li>
        <button class="darkThemeButton2">Dark Theme + Sidebar 2</button>
      </li>
    </ul>

    <div class="promo-box">
      <h3>PlainAdmin Pro</h3>
      <p>Get All Dashboards and 300+ UI Elements</p>
      <a href="https://plainadmin.com/pro" target="_blank" rel="nofollow" class="main-btn primary-btn btn-hover">
        Purchase Now
      </a>
    </div>
  </div>
  <!-- ============ Theme Option End ============= -->

  <!-- ========= All Javascript files linkup ======== -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/Chart.min.js"></script>
  <script src="assets/js/apexcharts.min.js"></script>
  <script src="assets/js/dynamic-pie-chart.js"></script>
  <script src="assets/js/moment.min.js"></script>
  <script src="assets/js/fullcalendar.js"></script>
  <script src="assets/js/jvectormap.min.js"></script>
  <script src="assets/js/world-merc.js"></script>
  <script src="assets/js/polyfill.js"></script>
  <script src="assets/js/quill.min.js"></script>
  <script src="assets/js/datatable.js"></script>
  <script src="assets/js/Sortable.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script>
    const dataTable = new simpleDatatables.DataTable("#table", {
      searchable: true,
    });
  </script>
</body>

<!-- Mirrored from demo.plainadmin.com/datatables.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Jan 2023 17:56:06 GMT -->

</html>