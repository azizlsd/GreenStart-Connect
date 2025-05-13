<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../controller/PostController.php';

$controller = new PostController();

$editing = $editing ?? null;
$posts = $posts ?? $controller->index();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Evenements * GreenStart Connect Dashboard</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description"
    content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords"
    content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/logoweb.png"
    type="image/x-icon">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
    id="main-font-link">
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet"
    href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/tabler-icons.min.css">
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/feather.css">
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/fontawesome.css">
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/material.css">
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/css/style.css"
    id="main-style-link">
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/css/style-preset.css">
  <!-- [Page specific CSS] start -->
  <link rel="stylesheet"
    href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/css/plugins/datepicker-bs5.min.css">

</head>
<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>
  <!-- [ Sidebar Menu ] end -->
  <?php include __DIR__ . '/../layouts/header.php'; ?>
  <div class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
              <h2>Post list</h2>
              </div>
             
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->

      <!-- [ Main Content ] start -->
      <div class="row">
        <!-- [ Form Validation ] start -->
        <div class="col-sm-12">
          <div class="card" style = "margin-top: -20px;">
           
            <div class="card-body">
    <form method="POST" action=<?= $editing ? "/GreenStart-Connect-main/GreenStartConnect/index.php?action=updatePosts" : "/GreenStart-Connect-main/GreenStartConnect/index.php?action=ManagePosts" ?> enctype="multipart/form-data" >
        <input type="hidden" name="action" value="<?= $editing ? 'update' : 'add' ?>">
        <?php if ($editing): ?>
            <input type="hidden" name="id_post" value="<?= $editing['id_post'] ?>">
            <input type="hidden" name="existing_image" value="<?= $editing['imagePath'] ?>">
        <?php endif; ?>
        <div class="mb-2">
            <input class="form-control" type="text" name="questions" placeholder="Questions" required
                   value="<?= $editing['questions'] ?? '' ?>">
        </div>
       
        <div class="mb-2">
            <input class="form-control" type="text" name="type" placeholder="Type" value="<?= $editing['type'] ?? '' ?>">
        </div>
        <div class="mb-2">
            <?php if ($editing && $editing['imagePath']): ?>
                <img src="View/<?= $editing['imagePath'] ?>" alt="Post Image" width="100">
            <?php endif; ?>
            <input class="form-control" type="file" name="image">
        </div>
        <button class="btn btn-primary" type="submit"><?= $editing ? 'Update' : 'Add' ?> Post</button>
        <?php if ($editing): ?>
            <a class="btn btn-secondary" href="index.php?action=ManagePosts">Cancel</a>
        <?php endif; ?>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Questions</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
    <tr>
        <td><?= $post['id_post'] ?></td>
        <td><?= htmlspecialchars($post['questions'] ?? 'N/A') ?></td> <!-- Default value if 'questions' is missing -->
        <td><?= htmlspecialchars($post['type'] ?? 'N/A') ?></td> <!-- Default value if 'type' is missing -->
        <td>
            <a href="index.php?action=editPosts&id=<?= $post['id_post'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="index.php?action=deletePosts&id=<?= $post['id_post'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
<?php endforeach; ?>

        </tbody>
    </table>
    </div>
          </div>
        </div>
        <!-- [ Form Validation ] end -->
      </div>
      <!-- [ Main Content ] end -->
    </div>
    </section>
    <!-- [ Main Content ] end -->
  </div>
  </div>
  <!-- [ Main Content ] end -->
  <footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
      <div class="row">
        <div class="col-sm my-1">
          <p class="m-0">GreenStart Connect &#9829; crafted by WeBoo

        </div>
        <div class="col-auto my-1">
          <ul class="list-inline footer-link mb-0">
            <li class="list-inline-item"><a href="../index.html">Home</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer> <!-- Required Js -->
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/popper.min.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/simplebar.min.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/bootstrap.min.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/fonts/custom-font.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/pcoded.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/feather.min.js"></script>





  <script>layout_change('light');</script>




  <script>change_box_container('false');</script>



  <script>layout_rtl_change('false');</script>


  <script>preset_change("preset-1");</script>


  <script>font_change("Public-Sans");</script>




</body>
</html>
