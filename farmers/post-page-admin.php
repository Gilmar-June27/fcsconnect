<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:../login.php');
   exit();
}

if (isset($_POST['add_post'])) {
   $title = mysqli_real_escape_string($conn, $_POST['title']);
   $content = mysqli_real_escape_string($conn, $_POST['content']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'images/'.$image;

   if ($image_size > 0 && $image_size <= 2000000) {
      $upload_image = true;
   } elseif ($image_size > 2000000) {
      $message[] = 'Image size is too large';
      $upload_image = false;
   } else {
      $upload_image = false;
   }

   if ($upload_image) {
      move_uploaded_file($image_tmp_name, $image_folder);
   } else {
      $image = ''; // No image or failed to upload image
   }

   $status = 'approved'; // Automatically approve for simplicity; you can set logic here

   $add_post_query = mysqli_query($conn, "INSERT INTO `posts` (title, content, image, admin_id, status) VALUES ('$title', '$content', '$image', '$admin_id', '$status')") or die('Query failed');

   if ($add_post_query) {
      $message[] = 'Post added successfully!';
   } else {
      $message[] = 'Post could not be added!';
   }
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `posts` WHERE id = '$delete_id'") or die('Query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   if ($fetch_delete_image['image']) {
      unlink('images/'.$fetch_delete_image['image']);
   }
   mysqli_query($conn, "DELETE FROM `posts` WHERE id = '$delete_id'") or die('Query failed');
   header('location:post-page-admin.php');
   exit();
}

if (isset($_POST['update_post'])) {
   $update_id = $_POST['update_id'];
   $update_title = $_POST['update_title'];
   $update_content = $_POST['update_content'];

   mysqli_query($conn, "UPDATE `posts` SET title = '$update_title', content = '$update_content' WHERE id = '$update_id'") or die('Query failed');

   // Handle image update
   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'images/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if (!empty($update_image)) {
      if ($update_image_size > 2000000) {
         $message[] = 'Image file size is too large';
      } else {
         mysqli_query($conn, "UPDATE `posts` SET image = '$update_image' WHERE id = '$update_id'") or die('Query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         if ($update_old_image) {
            unlink('images/'.$update_old_image);
         }
      }
   }

   header('location:post-page-admin.php');
   exit();
}
?>

<?php @include("admin_header.php") ?>
<?php @include("admin_navbar.php") ?>
<!-- Display messages -->
<?php if (isset($message)): ?>
    <?php foreach ($message as $msg): ?>
        <div class='message-box' style='background: #efdece; color: orange;'>
            <span><?= $msg; ?></span>
            <i class='fas fa-times close-btn'></i>
        </div>
    <?php endforeach; ?>
<?php elseif (isset($message_add)): ?>
    <?php foreach ($message_add as $msg_add): ?>
        <div class='message-box' style='background: #cfedd6; color: green;'>
            <span><?= $msg_add; ?></span>
            <i class='fas fa-times close-btn'></i>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Section Title -->
<div class="container section-title aos-init aos-animate" data-aos="fade-up">
        <p><span>Manage</span> <span class="description-title">Post</span></p>
    </div><!-- End Section Title -->
<!-- Post CRUD section starts -->
<section class="container my-5">
    
    <form action="" method="post" enctype="multipart/form-data" class="bg-light p-4 rounded shadow-sm">
        <h3 class="mb-4">Add Post</h3>
        <div class="mb-3">
            <label for="title" class="form-label">Post Title:</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Enter post title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Post Content:</label>
            <textarea id="content" name="content" class="form-control" placeholder="Enter post content" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Post Image (optional):</label>
            <input type="file" id="image" name="image" accept="image/jpg, image/jpeg, image/png" class="form-control">
        </div>
        <button type="submit" name="add_post" class="btn btn-primary">Add Post</button>
    </form>
</section>
<!-- Post CRUD section ends -->

<!-- Show posts -->
<section class="container my-5">
    <div class="row">
        <?php
            $select_posts = mysqli_query($conn, "SELECT * FROM `posts` WHERE admin_id = '$admin_id'") or die('Query failed');
            if (mysqli_num_rows($select_posts) > 0) {
                while ($fetch_posts = mysqli_fetch_assoc($select_posts)) {
                    $post_image = $fetch_posts['image'] ? 'images/'.$fetch_posts['image'] : 'images/default.jpg';
                    $image = $fetch_posts['image'];
        ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <!-- <img src="<?php echo $post_image; ?>" class="card-img-top" alt="" style="height:50vh"> -->
                <?php if (!empty($image)) { ?>
                                    <img src="images/<?php echo htmlspecialchars($image); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($fetch_posts['title']); ?>" style="height:50vh">
                                <?php } ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $fetch_posts['title']; ?></h5>
                    <p class="card-text"><?php echo $fetch_posts['content']; ?></p>
                    <p class="card-text text-muted"><?php echo $fetch_posts['created_at']; ?></p>
                    <div class="d-flex justify-content-between">
                        <?php if ($fetch_posts['status'] == 'processing'): ?>
                            <span class="badge bg-warning text-dark">Processing</span>
                        <?php else: ?>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editPostModal<?php echo $fetch_posts['id']; ?>">Update</button>
                        <?php endif; ?>
                        <?php if ($fetch_posts['status'] == 'approved'): ?>
                            <a href="post-page-admin.php?delete=<?php echo $fetch_posts['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this post?');">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
                }
            } else {
                echo '<p class="btn btn-outline-danger m-auto">No posts added yet!</p>';
            }
        ?>
    </div>
</section>

<!-- Edit Post Form Modals -->
<?php
$select_posts = mysqli_query($conn, "SELECT * FROM `posts` WHERE admin_id = '$admin_id'") or die('Query failed');
while ($fetch_posts = mysqli_fetch_assoc($select_posts)) {
    $post_id = $fetch_posts['id'];
?>
<div class="modal fade" id="editPostModal<?php echo $post_id; ?>" tabindex="-1" aria-labelledby="editPostModalLabel<?php echo $post_id; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel<?php echo $post_id; ?>">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="update_id" value="<?php echo $fetch_posts['id']; ?>">
                    <input type="hidden" name="update_old_image" value="<?php echo $fetch_posts['image']; ?>">
                    <div class="mb-3">
                        <label for="update_title" class="form-label">Post Title</label>
                        <input type="text" name="update_title" class="form-control" value="<?php echo $fetch_posts['title']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_content" class="form-label">Post Content</label>
                        <textarea name="update_content" class="form-control" required><?php echo $fetch_posts['content']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="update_image" class="form-label">Post Image</label>
                        <input type="file" name="update_image" class="form-control" accept="image/jpg, image/jpeg, image/png">
                        <input type="hidden" name="update_old_image" value="<?php echo $fetch_posts['image']; ?>">
                    </div>
                    <button type="submit" name="update_post" class="btn btn-primary">Update Post</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
}
?>

<?php @include("admin_footer.php") ?>
