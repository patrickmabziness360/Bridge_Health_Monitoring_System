<?php
include '../common/access.php';
include '../common/db_connect.php';

global $postid;
if (isset($_POST['editpost'])) {
  $_SESSION["postid"] = $_GET['qwert'];
 // if(isset($_SESSION['postid']) && !empty($_SESSION['postid'])) {
  if(isset($_GET['qwert'])){
    $postid = $_GET['qwert'];
    $sql = "SELECT o.*, i.* FROM bridge.tblBridge o INNER JOIN bridge.tblBridgeImages i ON o.BridgeID = i.BridgeID WHERE o.BridgeID =?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $param_postid);   
    // Set parameters
    $param_postid =$postid;

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
      if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $pagetitle = "Edit Bridge";
                $location_error = $bridgename_error = '';
                $location = $row["location"];
                }
        }
    
            
      } 
      else {
        echo '<script language="javascript">';
        echo 'alert("its null Added");';
        echo '</script>';
      }
  
  
  }else {
    $pagetitle = "Add Bridge";
    $location_error = $location = "";
    
} 


if (isset($_POST['saveChanges'])) {

    $location = isset($_POST['location'])? mysqli_real_escape_string($conn,$_POST['location']): '';
    $bridgename = isset($_POST['bridgename'])? mysqli_real_escape_string($conn,$_POST['bridgename']): '';
    
    ///validate all variables//
    if (!empty($location) && !empty($bridgename)) {

        if (empty($_FILES['attachment']['name']) && empty($_FILES['attachment']['type'])) {

          $attachment_error = "Please select file!";
            
        }
        else{
             //////Add records to database//////
            $insert_data = $conn->query("INSERT INTO bridge.tblBridge ( Location, Name) VALUES ('$location','$bridgename');");
            //////Get the PostID///////
            $post_id = $conn->query("SELECT BridgeID FROM bridge.tblBridge ORDER BY CreatedAt DESC LIMIT 1;");
            $post_id = $post_id->fetch_assoc()['BridgeID'];
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
                    $sql2 = $conn->query("INSERT INTO bridge.tblBridgeImages (BridgeID, AttachmentName) VALUES ($post_id,'$filename');");
                  if ($sql2) {
                      header("Location: bridges.php?value=1");
                  }else{
                    ////Revert back//////
                    $delete_news = $conn->query("DELETE FROM bridge.tblBridge WHERE BridgeID = $post_id");
                    $news_upload_error = "An error occured. Try again";
                  }
                }
       
            }else{
                echo 'FILE ERROR';exit();
            }
          }
    }else{
      $location_error = empty($location)?'Cannot be blank!':'';
      $bridgename_error = empty($bridgename)?'Cannot be blank!':'';
    }
  }
   //// Update a post//////

   if (isset($_POST['makeUpdate'])) {
    $national = isset($_POST['national']) ? mysqli_real_escape_string($conn, $_POST['national']) : '';
    $location = isset($_POST['location']) ? mysqli_real_escape_string($conn, $_POST['location']) : '';
    $post_category = isset($_POST['post_category']) ? mysqli_real_escape_string($conn, $_POST['post_category']) : '';
    $bridgename = isset($_POST['bridgename']) ? mysqli_real_escape_string($conn, $_POST['bridgename']) : '';
    $focus = isset($_POST['focus']) ? mysqli_real_escape_string($conn, $_POST['focus']) : '';
    $title = isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
    $content = isset($_POST['content']) ? mysqli_real_escape_string($conn, $_POST['content']) : '';
    $editor = $_SESSION['username'];
   
    // Prepare an update location, Editor, Title, Subtitle, PostCategory, Content, Focus, IsNational
    $sql = "UPDATE bridge.tblPosts SET location='$location',Editor='$editor',Title='$title',Subtitle='$bridgename',PostCategory='$post_category',Content='$content',Focus='$focus',IsNational='$national' WHERE PostID=?";
       
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
            <form action="add-bridge.php" method="post" onsubmit="onSubmitForm()" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card-style mb-30">
                    <!-- end input -->
                    <div class="row">
                      
                      <div class="col-md-6">
                        <div class="input-style-2">
                          <label>
                              <h5>Bridge Name</h5>
                          </label>
                          <input name="bridgename" id="bridgename" type="text" placeholder="Bridge Name" value="<?=!empty($bridgename)?$bridgename:'';?>"/>

                          <span class="icon"> <i class="lni lni-pencil"></i> </span>
                        </div>
                        <?php
                          if (!empty($subtitle_error)) {?>
                            <div class="text-danger" style="margin-top: -20px;margin-bottom: 20px"><b><?=$subtitle_error;?></b></div>
                        <?php } ?>
                      </div>
                      <div class="col-md-6">
                        <div class="input-style-2">
                          <label><h5>Location</h5></label>
                          <input name="location" id="location" type="text" placeholder="location Name" value="<?=!empty($location)?$location:'';?>" />
                          <span class="icon"> <i class="lni lni-pencil"></i> </span>
                        </div>
                        <?php if (!empty($location_error)) {?>
                          <div class="text-danger" style="margin-top: -20px;margin-bottom: 20px"><b><?=$location_error;?></b></div>
                        <?php }?>
                      </div>

                      <div class="col-md-6">
                        <div class="input-style-2">
                          <label><h5>Bridge Image</h5></label>
                          <input type="file" placeholder="" name="attachment" />
                          <span class="icon"> <i class="lni lni-file"></i> </span>
                        </div>
                        <?php if(!empty($attachment_error)) {?>
                          <div class="text-danger" style="margin-top: -20px;margin-bottom: 20px"><b><?=$attachment_error;?></b></div>
                        <?php } ?>
                      </div>

                    
                    <!-- end input -->
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
                      <!-- end card -->
                </div>
                
              </div>
            </form>
          </div>
        </div>
        <!-- end container -->
      </section>
      <!-- ========== table components end ========== -->

      <!-- ========== footer start =========== -->
      
      <!-- ========== footer end =========== -->
    </main>
    <!-- ======== main-wrapper end =========== -->

    
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
</html>
