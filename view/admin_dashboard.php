<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/PostController.php';
$errors = array();

$controller = new PostController($pdo);

// Handle form submissions
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $questions = isset($_POST['questions']) ? trim($_POST['questions']) : '';
    $type = isset($_POST['type']) ? trim($_POST['type']) : '';

    // Validation
    $errors = array();
    if ($questions === '') {
        $errors[] = "The 'Questions' field is required.";
    }
    if ($type === '') {
        $errors[] = "The 'Type' field is required.";
    }

    if (empty($errors)) {
        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($fileInfo, $_FILES['image']['tmp_name']);
            finfo_close($fileInfo);

            if (in_array($mime, $allowedTypes)) {
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $imagePath = 'uploads/' . $filename;
                
                if (!move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../' . $imagePath)) {
                    $errors[] = "Failed to upload image.";
                }
            } else {
                $errors[] = "Invalid file type. Only JPG, PNG, GIF allowed.";
            }
        }

        // Handle existing image for updates
        if (isset($_POST['existing_image']) && !empty($_POST['existing_image'])) {
            if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
                @unlink(__DIR__ . '/../' . $_POST['existing_image']);
                $imagePath = null;
            } else {
                $imagePath = $_POST['existing_image'];
            }
        }

        if (empty($errors)) {
            $data = array(
                'questions' => $questions,
                'date_creation' => isset($_POST['date_creation']) ? $_POST['date_creation'] : date('Y-m-d H:i:s'),
                'id_user' => 1,
                'type' => $type,
                'imagePath' => $imagePath
            );

            try {
                if ($_POST['action'] === 'add') {
                    if ($controller->store($data)) {
                        header("Location: admin_dashboard.php?success=added");
                        exit;
                    } else {
                        $errors[] = "Failed to save post. Please try again.";
                    }
                } elseif ($_POST['action'] === 'update') {
                    if ($controller->update($_POST['id_post'], $data)) {
                        header("Location: admin_dashboard.php?success=updated");
                        exit;
                    } else {
                        $errors[] = "Failed to update post. Please try again.";
                    }
                }
            } catch (Exception $e) {
                $errors[] = "An error occurred: " . $e->getMessage();
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $post = $controller->getById($_GET['delete']);
    if ($post) {
        if (!empty($post['imagePath'])) {
            @unlink(__DIR__ . '/../' . $post['imagePath']);
        }
        if ($controller->delete($_GET['delete'])) {
            header("Location: admin_dashboard.php?success=deleted");
            exit;
        }
    }
    $errors[] = "Failed to delete post.";
}

// Handle edit
$editing = null;
if (isset($_GET['edit'])) {
    $editing = $controller->getById($_GET['edit']);
}

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type_filter = isset($_GET['type_filter']) ? trim($_GET['type_filter']) : '';
$date_filter = isset($_GET['date_filter']) ? trim($_GET['date_filter']) : '';

// Build WHERE conditions
$where = array();
$params = array();

if (!empty($search)) {
    $where[] = "(questions LIKE ? OR type LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($type_filter)) {
    $where[] = "type = ?";
    $params[] = $type_filter;
}

if (!empty($date_filter)) {
    $where[] = "DATE(date_creation) = ?";
    $params[] = $date_filter;
}

// Get all posts with filters
$whereClause = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);
$posts = $controller->index($whereClause, $params);

// Get statistics
$stats = array();
$stats['total_posts'] = $pdo->query("SELECT COUNT(*) FROM post")->fetchColumn();
$stats['today_posts'] = $pdo->query("SELECT COUNT(*) FROM post WHERE DATE(date_creation) = CURDATE()")->fetchColumn();
$stats['with_images'] = $pdo->query("SELECT COUNT(*) FROM post WHERE imagePath IS NOT NULL AND imagePath != ''")->fetchColumn();
$latest_post = $pdo->query("SELECT questions, date_creation FROM post ORDER BY date_creation DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$stats['latest_post'] = $latest_post ? $latest_post : array('questions' => '', 'date_creation' => '');

// Get unique types for filter dropdown
$uniqueTypes = $pdo->query("SELECT DISTINCT type FROM post WHERE type IS NOT NULL ORDER BY type")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html><?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/PostController.php';

// Ensure uploads directory exists
if (!file_exists(__DIR__ . '/../uploads')) {
    mkdir(__DIR__ . '/../uploads', 0755, true);
}

$errors = array();
$controller = new PostController($pdo);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questions = isset($_POST['questions']) ? trim($_POST['questions']) : '';
    $type = isset($_POST['type']) ? trim($_POST['type']) : '';

    // Validation
    if ($questions === '') {
        $errors[] = "The 'Questions' field is required.";
    }
    if ($type === '') {
        $errors[] = "The 'Type' field is required.";
    }

    if (empty($errors)) {
        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($fileInfo, $_FILES['image']['tmp_name']);
            finfo_close($fileInfo);

            if (in_array($mime, $allowedTypes)) {
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $imagePath = 'uploads/' . $filename;
                
                if (!move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../' . $imagePath)) {
                    $errors[] = "Failed to upload image.";
                }
            } else {
                $errors[] = "Invalid file type. Only JPG, PNG, GIF allowed.";
            }
        }

        if (empty($errors)) {
            $data = array(
                'questions' => $questions,
                'date_creation' => isset($_POST['date_creation']) ? $_POST['date_creation'] : date('Y-m-d H:i:s'),
                'id_user' => 1,
                'type' => $type,
                'imagePath' => $imagePath
            );

            try {
                if ($_POST['action'] === 'add') {
                    if ($controller->store($data)) {
                        header("Location: admin_dashboard.php?success=added");
                        exit;
                    } else {
                        $errors[] = "Failed to save post. Please try again.";
                    }
                } elseif ($_POST['action'] === 'update') {
                    if ($controller->update($_POST['id_post'], $data)) {
                        header("Location: admin_dashboard.php?success=updated");
                        exit;
                    } else {
                        $errors[] = "Failed to update post. Please try again.";
                    }
                }
            } catch (Exception $e) {
                $errors[] = "An error occurred: " . $e->getMessage();
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $post = $controller->getById($_GET['delete']);
    if ($post) {
        // Delete associated image if exists
        if (!empty($post['imagePath'])) {
            @unlink(__DIR__ . '/../' . $post['imagePath']);
        }
        
        if ($controller->delete($_GET['delete'])) {
            header("Location: admin_dashboard.php?success=deleted");
            exit;
        } else {
            $errors[] = "Failed to delete post.";
        }
    } else {
        $errors[] = "Post not found.";
    }
}

// Handle edit
$editing = null;
if (isset($_GET['edit'])) {
    $editing = $controller->getById($_GET['edit']);
}

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type_filter = isset($_GET['type_filter']) ? trim($_GET['type_filter']) : '';
$date_filter = isset($_GET['date_filter']) ? trim($_GET['date_filter']) : '';

// Build WHERE conditions
$where = array();
$params = array();

if (!empty($search)) {
    $where[] = "(questions LIKE ? OR type LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($type_filter)) {
    $where[] = "type = ?";
    $params[] = $type_filter;
}

if (!empty($date_filter)) {
    $where[] = "DATE(date_creation) = ?";
    $params[] = $date_filter;
}

// Get all posts with filters
$whereClause = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);
$posts = $controller->index($whereClause, $params);

// Get statistics
$stats = array();
$stats['total_posts'] = $pdo->query("SELECT COUNT(*) FROM post")->fetchColumn();
$stats['today_posts'] = $pdo->query("SELECT COUNT(*) FROM post WHERE DATE(date_creation) = CURDATE()")->fetchColumn();
$stats['with_images'] = $pdo->query("SELECT COUNT(*) FROM post WHERE imagePath IS NOT NULL AND imagePath != ''")->fetchColumn();
$latest_post = $pdo->query("SELECT questions, date_creation FROM post ORDER BY date_creation DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$stats['latest_post'] = $latest_post ? $latest_post : array('questions' => '', 'date_creation' => '');

// Get unique types for filter dropdown
$uniqueTypes = $pdo->query("SELECT DISTINCT type FROM post WHERE type IS NOT NULL ORDER BY type")->fetchAll(PDO::FETCH_COLUMN);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Blog</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .stat-card { margin-bottom: 20px; border-radius: 4px; border: 1px solid #ddd; }
        .stat-card .panel-heading { font-weight: bold; padding: 10px 15px; }
        .stat-card .panel-body { padding: 15px; }
        .stat-value { font-size: 24px; font-weight: bold; margin: 5px 0; }
        .stat-label { color: #666; font-size: 12px; }
        .table { margin-top: 20px; }
        .search-form { margin-bottom: 20px; background: #f5f5f5; padding: 15px; border-radius: 4px; }
        .panel-primary { border-color: #337ab7; }
        .panel-primary .panel-heading { background: #337ab7; color: white; }
        .panel-success { border-color: #5cb85c; }
        .panel-success .panel-heading { background: #5cb85c; color: white; }
        .panel-info { border-color: #5bc0de; }
        .panel-info .panel-heading { background: #5bc0de; color: white; }
        .panel-warning { border-color: #f0ad4e; }
        .panel-warning .panel-heading { background: #f0ad4e; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Blog Admin Dashboard</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <?php
            $successMessages = array(
                'added' => 'Post added successfully!',
                'updated' => 'Post updated successfully!',
                'deleted' => 'Post deleted successfully!'
            );
            $message = isset($successMessages[$_GET['success']]) ? $successMessages[$_GET['success']] : 'Action completed successfully';
            ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary stat-card">
                    <div class="panel-heading">Total Posts</div>
                    <div class="panel-body">
                        <div class="stat-value"><?php echo $stats['total_posts']; ?></div>
                        <div class="stat-label">All Time</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="panel panel-success stat-card">
                    <div class="panel-heading">Today's Posts</div>
                    <div class="panel-body">
                        <div class="stat-value"><?php echo $stats['today_posts']; ?></div>
                        <div class="stat-label">Created Today</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="panel panel-info stat-card">
                    <div class="panel-heading">Posts with Images</div>
                    <div class="panel-body">
                        <div class="stat-value"><?php echo $stats['with_images']; ?></div>
                        <div class="stat-label">Visual Content</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="panel panel-warning stat-card">
                    <div class="panel-heading">Latest Post</div>
                    <div class="panel-body">
                        <div class="stat-value"><?php echo !empty($stats['latest_post']['date_creation']) ? date('M j', strtotime($stats['latest_post']['date_creation'])) : 'None'; ?></div>
                        <div class="stat-label" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo !empty($stats['latest_post']['questions']) ? htmlspecialchars(substr($stats['latest_post']['questions'], 0, 30)) . (strlen($stats['latest_post']['questions']) > 30 ? '...' : '') : 'N/A'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Form -->
        <!-- Replace your search form with this: -->
<div class="search-form">
    <form method="GET" class="form-inline">
        <div class="form-group" style="margin-right: 10px;">
            <input type="text" name="search" class="form-control" placeholder="Search questions..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>
        <div class="form-group" style="margin-right: 10px;">
            <select name="type_filter" class="form-control">
                <option value="">All Types</option>
                <?php foreach ($uniqueTypes as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>" 
                        <?php echo (isset($_GET['type_filter']) && $_GET['type_filter'] === $type) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($type); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin-right: 10px;">
            <input type="date" name="date_filter" class="form-control" 
                   value="<?php echo isset($_GET['date_filter']) ? htmlspecialchars($_GET['date_filter']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary" style="margin-right: 10px;">Filter</button>
        <a href="admin_dashboard.php" class="btn btn-default">Reset</a>
    </form>
</div>

        <!-- Post Form -->
        <form method="POST" enctype="multipart/form-data" id="postForm" class="panel panel-default" style="margin-bottom: 20px;">
    <div class="panel-heading">
        <?php echo $editing ? 'Edit Post' : 'Add New Post'; ?>
    </div>
    <div class="panel-body">
        <input type="hidden" name="action" value="<?php echo $editing ? 'update' : 'add'; ?>">
        <?php if ($editing): ?>
            <input type="hidden" name="id_post" value="<?php echo $editing['id_post']; ?>">
            <input type="hidden" name="existing_image" value="<?php echo isset($editing['imagePath']) ? htmlspecialchars($editing['imagePath']) : ''; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="questions">Questions</label>
            <input class="form-control" type="text" name="questions" id="questions" placeholder="Enter question text"
                   value="<?php echo isset($editing['questions']) ? htmlspecialchars($editing['questions']) : ''; ?>" required>
            <small class="text-danger" id="questionsError" style="display: none;">Please enter a question.</small>
        </div>

        <div class="form-group">
            <label for="type">Type</label>
            <input class="form-control" type="text" name="type" id="type" placeholder="Enter post type"
                   value="<?php echo isset($editing['type']) ? htmlspecialchars($editing['type']) : ''; ?>" required>
            <small class="text-danger" id="typeError" style="display: none;">Please enter a type.</small>
        </div>

        <div class="form-group">
            <label for="image">Image</label>
            <?php if ($editing && !empty($editing['imagePath'])): ?>
                <div class="mb-2">
                    <img src="../<?php echo htmlspecialchars($editing['imagePath']); ?>" alt="Current Image" width="100" class="img-thumbnail">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remove_image" id="remove_image" value="1"> 
                            Remove current image
                        </label>
                    </div>
                </div>
            <?php endif; ?>
            <input class="form-control" type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif">
            <small class="help-block">Only JPG, PNG, GIF files allowed (max 2MB)</small>
        </div>

        <button class="btn btn-primary" type="submit" name="submit"><?php echo $editing ? 'Update' : 'Add'; ?> Post</button>
        <?php if ($editing): ?>
            <a class="btn btn-default" href="admin_dashboard.php">Cancel</a>
        <?php endif; ?>
    </div>
</form>

        <!-- Posts Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Posts List
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Questions</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($posts)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No posts found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?php echo $post['id_post']; ?></td>
                                    <td><?php echo htmlspecialchars($post['questions']); ?></td>
                                    <td><?php echo htmlspecialchars($post['type']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($post['date_creation'])); ?></td>
                                    <td>
                                        <?php if (!empty($post['imagePath'])): ?>
                                            <img src="../<?php echo htmlspecialchars($post['imagePath']); ?>" width="50">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="admin_dashboard.php?edit=<?php echo $post['id_post']; ?>" class="btn btn-xs btn-warning">Edit</a>
                                        <a href="admin_dashboard.php?delete=<?php echo $post['id_post']; ?>" class="btn btn-xs btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('postForm').addEventListener('submit', function (e) {
        var isValid = true;

        // Clear errors
        document.getElementById('questionsError').style.display = 'none';
        document.getElementById('typeError').style.display = 'none';

        var questions = document.getElementById('questions').value.trim();
        var type = document.getElementById('type').value.trim();

        if (questions === '') {
            document.getElementById('questionsError').style.display = 'block';
            isValid = false;
        }

        if (type === '') {
            document.getElementById('typeError').style.display = 'block';
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Add confirmation for delete links
    var deleteLinks = document.querySelectorAll('.btn-danger');
    for (var i = 0; i < deleteLinks.length; i++) {
        deleteLinks[i].addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this post?')) {
                e.preventDefault();
            }
        });
    }
    </script>
    <script>
document.getElementById('postForm').addEventListener('submit', function(e) {
    var isValid = true;
    
    // Clear previous errors
    document.getElementById('questionsError').style.display = 'none';
    document.getElementById('typeError').style.display = 'none';

    // Validate questions
    var questions = document.getElementById('questions').value.trim();
    if (questions === '') {
        document.getElementById('questionsError').style.display = 'block';
        isValid = false;
    }

    // Validate type
    var type = document.getElementById('type').value.trim();
    if (type === '') {
        document.getElementById('typeError').style.display = 'block';
        isValid = false;
    }

    // Validate image if adding new post
    <?php if (!$editing): ?>
    var imageInput = document.getElementById('image');
    if (imageInput.files.length === 0) {
        // Image is optional, so we don't fail validation here
    }
    <?php endif; ?>

    if (!isValid) {
        e.preventDefault();
    }
});
</script>

</body>
</html>