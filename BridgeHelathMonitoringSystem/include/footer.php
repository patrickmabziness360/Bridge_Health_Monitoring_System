<footer>
  <div class="footer-top">
    <div class="container">
      <div class="row">
        <div class="col-sm-5">
          <img src="<?=$relative_path;?>assets/images/lita.jpg" alt="" style="width: 95px;border-radius: 50%;" 
            />
          <h5 class="font-weight-normal mt-4 mb-5">
            Motivated by the clarion call to fight poverty created by corruption, graft, nepotism and impunity in Malawi
          </h5>
          <ul class="social-media mb-3">
            <li>
              <a href="#">
                <i class="mdi mdi-facebook"></i>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="mdi mdi-youtube"></i>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="mdi mdi-twitter"></i>
              </a>
            </li>
          </ul>
        </div>
        <div class="col-sm-4">
          <h3 class="font-weight-bold mb-3">RECENT POSTS</h3>
          <?php
             while ($row = $recent_posts->fetch_assoc()) {?>
              <div class="row">
                <div class="col-sm-12">
                  <div class="footer-border-bottom pb-2">
                    <a href="<?=$relative_path;?>single-news.php?id=<?=$row['PostID'];?>" style="color: white;">
                      <div class="row">
                        <div class="col-3">
                          <img
                            src="<?=$relative_path;?>upload/<?=$row['AttachmentName'];?>"
                            alt="thumb"
                            class="img-fluid"
                          />
                        </div>
                        <div class="col-9">
                          <h5 class="font-weight-600">
                            <?= $row['Title'] ;?>
                          </h5>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
          <?php } ?>
        </div>
        <div class="col-sm-3">
          <h3 class="font-weight-bold mb-3">CATEGORIES</h3>
          <div class="footer-border-bottom pb-2">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 font-weight-600">Magazine</h5>
              <!--<div class="count">1</div>-->
            </div>
          </div>
          <div class="footer-border-bottom pb-2 pt-2">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 font-weight-600">Business</h5>
              <!--<div class="count">1</div>-->
            </div>
          </div>
          <div class="footer-border-bottom pb-2 pt-2">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 font-weight-600">Sports</h5>
              <!--<div class="count">1</div>-->
            </div>
          </div>
          <div class="footer-border-bottom pb-2 pt-2">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 font-weight-600">Arts</h5>
              <!--<div class="count">1</div>-->
            </div>
          </div>
          <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 font-weight-600">Politics</h5>
              <!--<div class="count">1</div>-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="d-sm-flex justify-content-between align-items-center">
            <div class="fs-14 font-weight-600">
              © 2023 @ lita. All rights reserved.
            </div>
            <div class="fs-14 font-weight-600">
              Free <a href="#" target="_blank" class="text-white">Citizen Participation – Citizen Power</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>