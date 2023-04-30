<?php
  
  $relative_path = '../';
  include ('../common/db_connect.php');
  ////GET  NEWS/////
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $slider_national_news = $conn->query("SELECT LTP.*, LTPF.Caption,LTPF.AttachmentName FROM lita.tblPosts LTP LEFT JOIN lita.tblPostFiles LTPF ON LTP.PostID = LTPF.PostID WHERE LTP.PostID =$id;");
    $result = $slider_national_news -> fetch_assoc();

  }else{
    header("Location:../index.php");
  }
   
   include('../include/menu_bar.php');

?>

        <!-- partial -->
        <div class="flash-news-banner">
          <div class="container">
            <div class="d-lg-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <span class="badge badge-dark mr-3">Flash news</span>
              </div>
                  <span>
                    <marquee scrollamount="6" style="float:left; width:800px; padding: 9px 0px 0px 0px"><font size="3">
                      <?=$moving_text;?>
                    </font>
                    </marquee></span>
                  
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
            <div class="col-sm-12">
              <div class="card" data-aos="fade-up">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-8">
                      <div>
                        <h1 class="font-weight-600 mb-1">
                          <?=$result['Title'];?>
                        </h1>
                        <p class="fs-13 text-muted mb-0">
                          <span class="mr-2">Date -</span><?=date('d F Y', strtotime($result['CreatedAt']));?>
                        </p>
                        <div class="rotate-img">
                          <img
                            src="<?=$relative_path;?>upload/<?=$result['AttachmentName'];?>"
                            alt="banner"
                            class="img-fluid mt-4 mb-4"
                          />
                        </div>
                        <div class="mb-4 fs-15" style="border: 1px solid #dbdbdb;padding: 22px; border-top: none;">
                          <?=$result['Content'];?>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4" >
                      <h2 class="mb-4 text-primary font-weight-600">
                        Latest news
                      </h2>
                      <?php
                        while ($row = $lates_news->fetch_assoc()) {?>
                          <div class="row" >
                            <a href="single-news.php?id=<?=$row['PostID'];?>" style ="color: #032a63 !important;text-decoration: none;" >
                              <div class="col-sm-12">
                                <div class="border-bottom pb-4 pt-4">
                                  <div class="row">
                                    <div class="col-sm-7">
                                      <h5 class="font-weight-600 mb-1">
                                        <?=$row['Title'] ;?>
                                      </h5>
                                      <p class="fs-13 text-muted mb-0">
                                        <span class="mr-2">Date </span><?=date('d F Y', strtotime($row['CreatedAt']));?>
                                      </p>
                                    </div>
                                    <div class="col-sm-5">
                                      <div class="rotate-img">
                                        <img
                                          src="<?=$relative_path;?>upload/<?=$row['AttachmentName'];?>"
                                          alt="banner"
                                          class="img-fluid"
                                        />
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </a>
                          </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- main-panel ends -->
        <!-- container-scroller ends -->

        <!-- partial:partials/_footer.html -->
        <?php
          include '../include/footer.php';
        ?>

        <!-- partial -->
      </div>
    </div>
    <!-- inject:js -->
    <script src="<?=$relative_path;?>assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <script src="<?=$relative_path;?>assets/vendors/aos/dist/aos.js/aos.js"></script>
    <!-- End plugin js for this page -->
    <!-- Custom js for this page-->
    <script src="<?=$relative_path;?>assets/js/demo.js"></script>
    <script src="<?=$relative_path;?>assets/js/jquery.easeScroll.js"></script>
    <!-- End custom js for this page-->
  </body>

<!-- Mirrored from www.bootstrapdash.com/demo/world-time/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 22 Jan 2023 20:18:52 GMT -->
</html>
