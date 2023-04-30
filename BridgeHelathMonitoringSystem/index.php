<?php

$relative_path = '';
include('common/db_connect.php');
include('include/menu_bar.php');

?>

<!-- partial -->
<div class="flash-news-banner">
  <div class="container">
    <div class="d-lg-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">

      </div>
      <span>

      </span>

      <div class="d-flex">
        <span class="mr-3 text-danger">
          <?php
          $date = $conn->query("SELECT now() date");
          $date = $date->fetch_assoc()['date'];
          echo date('d F Y', strtotime($date));
          ?>
        </span>
      </div>
    </div>
  </div>
</div>
<div class="content-wrapper">
  <div class="container">
    <div class="row" data-aos="fade-up">
      <div class="col-xl-8 stretch-card grid-margin">
        <div class="position-relative">
          <?php
          while ($row = $closest_bridge->fetch_assoc()) { ?>
            <a href="pages/single-news.php?id=<?= $row['PostID']; ?>">
              <img src="upload/<?= $row['AttachmentName']; ?>" alt="banner" class="img-fluid" />
              <div class="banner-content" style="background-color: #00000085;">
                <div class="badge badge-danger fs-12 font-weight-bold mb-3">
                  national news
                </div>
                <h1 class="mb-0">
                  <?= $row['Title']; ?>
                </h1>
                <h1 class="mb-2">
                  <?= substr($row['Subtitle'], 0, 50) . '...'; ?>
                </h1>
                <div class="fs-12">
                  <span class="mr-2">Date </span>
                  <?= date('d F Y', strtotime($row['CreatedAt'])); ?>
                </div>
              </div>
            </a>

          <?php }
          ?>
        </div>
      </div>
      <div class="col-xl-4 stretch-card grid-margin">
        <div class="card bg-dark text-white">
          <div class="card-body">
            <h2>Nearest Bridges</h2>

            <?php
            while ($row = $nearestBridges->fetch_assoc()) { ?>
              <a href="pages/single-news.php?id=<?= $row['PostID']; ?>" style="color: white;">
                <div class="d-flex border-bottom-blue pt-3 align-items-center justify-content-between">

                  <div class="pr-3">
                    <h5>
                      <?= substr($row['Title'], 0, 30) . '...'; ?>
                    </h5>
                    <div class="fs-12">
                      <span class="mr-2">Date </span>
                      <?= date('d F Y', strtotime($row['CreatedAt'])); ?>
                    </div>
                  </div>
                  <div class="rotate-img">
                    <img src="upload/<?= $row['AttachmentName']; ?>" alt="thumb" class="img-fluid img-lg" />
                  </div>
                </div>
              </a>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- main-panel ends -->
<!-- container-scroller ends -->

<!-- partial:partials/_footer.html -->


<!-- partial -->
</div>
</div>
<!-- inject:js -->
<script src="assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<script src="assets/vendors/aos/dist/aos.js/aos.js"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="assets/js/demo.js"></script>
<script src="assets/js/jquery.easeScroll.js"></script>
<!-- End custom js for this page-->
</body>

</html>