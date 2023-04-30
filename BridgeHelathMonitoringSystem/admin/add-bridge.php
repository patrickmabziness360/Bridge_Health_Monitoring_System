<?php
include '../common/access.php';
include '../common/db_connect.php';

global $postid;
if (isset($_POST['editpost'])) {
  $_SESSION["postid"] = $_GET['qwert'];
 // if(isset($_SESSION['postid']) && !empty($_SESSION['postid'])) {
  if(isset($_GET['qwert'])){
    $postid = $_GET['qwert'];
    $sql = "SELECT o.*, i.* FROM lita.tblposts o INNER JOIN lita.tblpostfiles i ON o.PostID = i.PostID WHERE o.PostID =?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $param_postid);   
    // Set parameters
    $param_postid =$postid;

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
      if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $pagetitle = "Edit News Item";
                $national_error = $author_error = $category_error = $title_error = $subtitle_error = $attachment_error = $content_error = $focus_error = '';
                $author = $row["Author"];
                $title = $row["Title"];
                $subtitle = $row["Subtitle"];
                $post_category = $row["PostCategory"];
                $national = $row["IsNational"];
                $focus = $row["Focus"];
                $content = $row["Content"];
            }
        }
    
            
      } 
      else {
        echo '<script language="javascript">';
        echo 'alert("its null Added");';
        echo '</script>';
      }
  
  
  }else {
    $pagetitle = "Add News Item";
    $national_error = $author_error = $category_error = $title_error = $subtitle_error = $attachment_error = $content_error = $focus_error = '';
    $author = $title = $subtitle = $post_category = $national = $focus = $content = "";
    
} 






// $national_error = $author_error = $category_error = $title_error = $subtitle_error = $attachment_error = $content_error = $focus_error = '';
// $author = $title = $subtitle = $post_category = $national = $focus = $content = "";

if (isset($_POST['saveChanges'])) {

    $national = isset($_POST['national'])? mysqli_real_escape_string($conn,$_POST['national']): '';
    $author = isset($_POST['author'])? mysqli_real_escape_string($conn,$_POST['author']): '';
    $post_category = isset($_POST['post_category'])? mysqli_real_escape_string($conn,$_POST['post_category']): '';
    $subtitle = isset($_POST['subtitle'])? mysqli_real_escape_string($conn,$_POST['subtitle']): '';
    $focus = isset($_POST['focus'])? mysqli_real_escape_string($conn,$_POST['focus']): '';
    $title = isset($_POST['title'])? mysqli_real_escape_string($conn,$_POST['title']): '';
    $content = isset($_POST['content'])? mysqli_real_escape_string($conn,$_POST['content']): '';
    $editor = $_SESSION['username'];

    ///validate all variables//
    if (!empty($national) && !empty($author) && !empty($post_category) && !empty($focus) && !empty($title) && !empty($subtitle) && !empty($content)) {

        if (empty($_FILES['attachment']['name']) && empty($_FILES['attachment']['type'])) {

          $attachment_error = "Please select file!";
            
        }
        else{
             //////Add records to database//////
            $insert_data = $conn->query("INSERT INTO lita.tblPosts (CompanyID, Author, Editor, Title, Subtitle, PostCategory, Content, Focus, IsNational) VALUES (1, '$author','$editor','$title','$subtitle','$post_category','$content','$focus','$national');");
            //////Get the PostID///////
            $post_id = $conn->query("SELECT PostID FROM lita.tblPosts ORDER BY CreatedAt DESC LIMIT 1;");
            $post_id = $post_id->fetch_assoc()['PostID'];
            /////upload image//////////
            $name = $_FILES['attachment']['name'];
            $target_dir = "../upload/";
            $target_file = $target_dir . basename($_FILES["attachment"]["name"]);

            // Select file type
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Valid file extensions
            $extensions_arr = array("jpg", "jpeg", "png", "gif");

            // //get file info
            $filename = basename($_FILES["attachment"]["name"]);
            $fileType = pathinfo($filename, PATHINFO_EXTENSION);
            // $allowType = array('jpg', 'png', 'jpeg', 'gif');
            // if (in_array($fileType, $allowType)) {
            $attachment = $_FILES['attachment']['tmp_name'];
            $imageContent = addslashes(file_get_contents($attachment));

           // Check extension
            if (in_array($imageFileType, $extensions_arr)) {
                // Upload file
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_dir . $name)) {
                    // Insert record
                    $sql2 = $conn->query("INSERT INTO lita.tblPostFiles (PostID, AttachmentName) VALUES ($post_id,'$filename');");
                  if ($sql2) {
                      header("Location: news.php?value=1");
                  }else{
                    ////Revert back//////
                    $delete_news = $conn->query("DELETE FROM lita.tblPosts WHERE PostID = $post_id");
                    $news_upload_error = "An error occured. Try again";
                  }
                }
       
            }else{
                echo 'FILE ERROR';exit();
            }
          }
    }else{
      $national_error = empty($national)?'Please select!':'';
      $author_error = empty($author)?'Cannot be blank!':'';
      $category_error = empty($post_category)?'Please select!':'';
      $title_error = empty($title)?'Cannot be blank!':'';
      $subtitle_error = empty($subtitle)?'Cannot be blank!':'';
      $content_error = empty($content)?'Cannot be blank!':'';
      $focus_error = empty($focus)?'Please select!':'';
    }
  }
   //// Update a post//////

   if (isset($_POST['makeUpdate'])) {
    $national = isset($_POST['national']) ? mysqli_real_escape_string($conn, $_POST['national']) : '';
    $author = isset($_POST['author']) ? mysqli_real_escape_string($conn, $_POST['author']) : '';
    $post_category = isset($_POST['post_category']) ? mysqli_real_escape_string($conn, $_POST['post_category']) : '';
    $subtitle = isset($_POST['subtitle']) ? mysqli_real_escape_string($conn, $_POST['subtitle']) : '';
    $focus = isset($_POST['focus']) ? mysqli_real_escape_string($conn, $_POST['focus']) : '';
    $title = isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
    $content = isset($_POST['content']) ? mysqli_real_escape_string($conn, $_POST['content']) : '';
    $editor = $_SESSION['username'];
   
    // Prepare an update Author, Editor, Title, Subtitle, PostCategory, Content, Focus, IsNational
    $sql = "UPDATE lita.tblPosts SET Author='$author',Editor='$editor',Title='$title',Subtitle='$subtitle',PostCategory='$post_category',Content='$content',Focus='$focus',IsNational='$national' WHERE PostID=?";
       
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_postid);
          
        // Set parameters
        $param_postid = $_SESSION["postid"] ;
       
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Post updated successfully';
            header("Location: news.php?value=1");
    
        } else {
            echo "Something went wrong. Please try again later";
        }
          
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Something went wrong. Please try again later";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from demo.plainadmin.com/datatables.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Jan 2023 17:56:06 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
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
      <!-- ========== header end ========== -->

      <!-- ========== table components start ========== -->
      <section class="table-components">
        <div class="container-fluid">
          <!-- ========== title-wrapper start ========== -->
          <div class="title-wrapper pt-30">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="title mb-30">
                  <h2><?php echo $pagetitle ?></h2>
                </div>
              </div>
              <!-- end col -->
            </div>
            <!-- end row -->
          </div>
          <!-- ========== title-wrapper end ========== -->
          <div class="tabs-wrapper">
            <form action="add-news.php" method="post" onsubmit="onSubmitForm()" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card-style mb-30">
                    <!-- end input -->
                    <div class="row">
                      <div class="col-md-6">
                        <div class="input-style-2">
                          <label><h5>Title</h5></label>
                          <input name="title" id="title" type="text" placeholder="Enter title" value="<?=!empty($title)?$title:'';?>"  />
                          <span class="icon"> <i class="lni lni-pencil"></i> </span>
                        </div>
                        <?php
                          if (!empty($title_error)) {?>
                            <div class="text-danger" style="margin-top: -20px;margin-bottom: 20px"><b><?=$title_error;?></b></div>
                          <?php } ?>
                      </div>
                      <div class="col-md-6">
                        <div class="input-style-2">
                          <label>
                              <h5>SubTitle</h5>
                          </label>
                          <input name="subtitle" id="subtitle" type="text" placeholder="Enter  subtitle" value="<?=!empty($subtitle)?$subtitle:'';?>"/>

                          <span class="icon"> <i class="lni lni-pencil"></i> </span>
                        </div>
                        <?php
                          if (!empty($subtitle_error)) {?>
                            <div class="text-danger" style="margin-top: -20px;margin-bottom: 20px"><b><?=$subtitle_error;?></b></div>
                        <?php } ?>
                      </div>
                      <div class="col-md-6">
                        <div class="input-style-2">
                          <label><h5>Author</h5></label>
                          <input name="author" id="author" type="text" placeholder="Author Name" value="<?=!empty($author)?$author:'';?>" />
                          <span class="icon"> <i class="lni lni-pencil"></i> </span>
                        </div>
                        <?php if (!empty($author_error)) {?>
                          <div class="text-danger" style="margin-top: -20px;margin-bottom: 20px"><b><?=$author_error;?></b></div>
                        <?php }?>
                      </div>
                      <div class="col-md-6">
                        <div class="select-style-1">
                          <label><b>Category</b></label>
                          <div class="select-position">
                            <select name="post_category" class=" light-bg">
                                <option selected disabled hidden>--Select--</option>
                                <option value="politics" <?php if($post_category=='politics'){echo 'selected';};?>>Politics </option>
                                <option value="finance" <?php if($post_category=='finance'){echo 'selected';};?> >Finance</option>
                                <option value="health care" <?php if($post_category=='health care'){echo 'selected';};?> >Health Care </option>
                                <option value="technology" <?php if($post_category=='technology'){echo 'selected';};?> >Technology</option>
                                <option value="sport" <?php if($post_category=='sport'){echo 'selected';};?> >Sport</option>
                                <option value="national" <?php if($post_category=='national'){echo 'selected';};?>>National</option>
                            </select>
                          </div>
                        </div>
                        <?php if(!empty($category_error)) { ?> 
                          <div class="text-danger" style="margin-top: -20px;margin-bottom: 20px"><b><?=$category_error;?></b></div>
                        <?php } ?>
                      </div>
                      <div class="col-md-6">
                        <div class="input-style-2">
                          <label><h5>Attachment</h5></label>
                          <input type="file" placeholder="" name="attachment" />
                          <span class="icon"> <i class="lni lni-file"></i> </span>
                        </div>
                        <?php if(!empty($attachment_error)) {?>
                          <div class="text-danger" style="margin-top: -20px;margin-bottom: 20px"><b><?=$attachment_error;?></b></div>
                        <?php } ?>
                      </div>
                      <div class="col-md-3">
                        <div class="form-check radio-style radio-success mb-20">
                            <input name="national" class="form-check-input" type="radio" value="YES" id="radio-3" <?php if($national=='YES'){echo 'checked';};?> />
                            <label class="form-check-label" for="radio-3">
                                <b>National news</b></label>
                        </div>
                        <div class="form-check radio-style radio-success mb-20">
                            <input name="national" class="form-check-input" type="radio" value="NO" id="radio-3" <?php if($national=='NO'){echo 'checked';};?> />
                            <label class="form-check-label" for="radio-3">
                                <b>Internation news</b></label>
                        </div>
                        <?php if(!empty($national_error)) {?>
                          <div class="text-danger" style="margin-top: -20px;margin-bottom: 20px"><b><?=$national_error;?></b></div>
                        <?php } ?>
                      </div>
                      <div class="col-md-3">
                        <div class="form-check radio-style radio-success mb-20">
                              <input name="focus" class="form-check-input" type="radio"
                                  value="General" id="radio-3" <?php if($focus=='General'){echo 'checked';};?> />
                              <label class="form-check-label" for="radio-3">
                                  <b>General</b></label>
                          </div>
                          <div class="form-check radio-style radio-success mb-20">
                              <input name="focus" class="form-check-input" type="radio" value="FlashNews" id="radio-3" <?php if($focus=='FlashNews'){echo 'checked';};?> />
                              <label class="form-check-label" for="radio-3">
                                  <b>Flash news</b></label>
                          </div>
                          <div class="form-check radio-style radio-success mb-20">
                              <input name="focus" class="form-check-input" type="radio"
                                  value="SportLight" id="radio-3" <?php if($focus=='SportLight'){echo 'checked';};?> />
                              <label class="form-check-label" for="radio-3">
                                  <b>SpotLight</b></label>
                          </div>
                          <?php if (!empty($focus_error)) {?>
                            <div class="text-danger" style="margin-top: -20px;margin-bottom: 20px"><b><?=$focus_error;?></b></div>
                          <?php } ?>
                      </div>
                      
                    </div>
                    <!-- end input -->
                  </div>
                      <!-- end card -->
                </div>
                <div class="form-editor-wrapper">
                  <div class="row">
                      <div class="col-12">
                          <div class="card-style mb-30">
                              <div class="title d-flex justify-content-between align-items-center">
                                  <h6 class="mb-30">Content</h6>
                              </div>
                              <div value="content" id="quill-toolbar">
                                  <span class="ql-formats">
                                      <select class="ql-font"></select>
                                      <select class="ql-size"></select>
                                  </span>
                                  <span class="ql-formats">
                                      <button class="ql-bold"></button>
                                      <button class="ql-italic"></button>
                                      <button class="ql-underline"></button>
                                      <button class="ql-strike"></button>
                                  </span>
                                  <span class="ql-formats">
                                      <select class="ql-color"></select>
                                      <select class="ql-background"></select>
                                  </span>
                                  <span class="ql-formats">
                                      <button class="ql-script" value="sub"></button>
                                      <button class="ql-script" value="super"></button>
                                  </span>
                                  <span class="ql-formats">
                                      <button class="ql-header" value="1"></button>
                                      <button class="ql-header" value="2"></button>
                                      <button class="ql-blockquote"></button>
                                      <button class="ql-code-block"></button>
                                  </span>
                                  <span class="ql-formats">
                                      <button class="ql-list" value="ordered"></button>
                                      <button class="ql-list" value="bullet"></button>
                                      <button class="ql-indent" value="-1"></button>
                                      <button class="ql-indent" value="+1"></button>
                                  </span>
                                  <span class="ql-formats">
                                      <button class="ql-direction" value="rtl"></button>
                                      <select class="ql-align"></select>
                                  </span>
                                  <span class="ql-formats">
                                      <button class="ql-link"></button>
                                      <button class="ql-image #quill-bubble-editor">
                                      </button>
                                      <button class="ql-video"></button>
                                  </span>
                                  <span class="ql-formats">
                                      <button class="ql-clean"></button>
                                  </span>
                              </div>

                              <input name="content" id="content" type="hidden" />
                              <div id="quill-editor">
                                  <?= strip_tags($content) ?>
                              </div>
                              <div class="text-danger"><b><?=$content_error;?></b></div>
                          </div>
                      </div>
                  <!-- end row -->
                  </div>
                  <div class="col-md-4">
                    <?php if(isset($_POST['editpost'])) : ?>
                    
                      <button type="submit" name="makeUpdate" class="btn primary-btn">Update News
                      </button>
                 
                  <?php else : ?>
                     <button type="submit" name="saveChanges" class="btn primary-btn">Submit News
                      </button>
                  <?php endif; ?>
                     
                  </div>
                </div>
              </div>
            </form>
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
    <button class="option-btn">
      <i class="lni lni-cog"></i>
    </button>
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
        <a
          href="https://plainadmin.com/pro"
          target="_blank"
          rel="nofollow"
          class="main-btn primary-btn btn-hover"
        >
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
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const editor = new Quill("#quill-editor", {
            modules: {
                toolbar: "#quill-toolbar",
            },
            placeholder: "Type something",
            theme: "snow",
        });

    })
    var container = document.getElementById('quill-editor');
    //form subumission  from the quii area
    function onSubmitForm() {

        var html = document.getElementById("quill-editor").children[0].innerHTML;
        document.getElementById("content").value = html;

    }
    </script>
  </body>
</html>
