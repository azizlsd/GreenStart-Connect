<?php
// blog.php
// Handle comment update

// — Bootstrap your app
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/PostController.php';
require_once __DIR__ . '/../controllers/CommentaireController.php';

$postController        = new PostController($pdo);
$commentaireController = new CommentaireController($pdo);

// — Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_comment') {
    $errors = array();
    $id_post = filter_input(INPUT_POST, 'id_post', FILTER_VALIDATE_INT);
    $contenu = trim(isset($_POST['contenu']) ? $_POST['contenu'] : '');

    // Validation
    if (empty($contenu)) {
        $errors[$id_post][] = "Comment cannot be empty.";
    }

    $badWords = array('shit', 'fuck', 'fuck you'); // Add your bad words list
    foreach ($badWords as $word) {
        if (stripos($contenu, $word) !== false) {
            $errors[$id_post][] = "Comment contains inappropriate language.";
            break;
        }
    }

    if (!empty($errors)) {
        $_SESSION['comment_errors'] = $errors;
        header("Location: blog.php");
        exit();
    }

    // If validation passes
    try {
        $commentaireController->store(array(
            'id_post' => $id_post,
            'contenu' => htmlspecialchars($contenu, ENT_QUOTES, 'UTF-8'),
            'action' => 'add_comment'
        ));
        header("Location: blog.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['comment_errors'][$id_post][] = "Error saving comment: " . $e->getMessage();
        header("Location: blog.php");
        exit();
    }
}
if (isset($_GET['fetch_comments'])) {
    $postId = $_GET['fetch_comments'];
    $comments = $commentaireController->getByPost($postId);
    echo json_encode($comments);  // Send the comments back in JSON format
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'rate_comment') {
    $id = (int)(isset($_POST['id_comment']) ? $_POST['id_comment'] : 0);
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    if ($id > 0 && in_array($type, array('like', 'dislike'))) {
        $success = $commentaireController->rate($id, $type);
        if ($success) {
            // Get updated counts to return
            $stmt = $pdo->prepare("SELECT likes, dislikes FROM commentaire WHERE id_comment = ?");
            $stmt->execute(array($id));
            $counts = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(array('success' => true, 'likes' => $counts['likes'], 'dislikes' => $counts['dislikes']));
            exit;
        }
    }
    echo json_encode(array('success' => false));
    exit;
}

// Handle comment update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_comment') {
    if (isset($_POST['id_comment']) && isset($_POST['contenu'])) {
        $id_comment = $_POST['id_comment'];
        $contenu = $_POST['contenu'];

        // Make sure both the ID and content are set
        if (!empty($id_comment) && !empty($contenu)) {
            // Call the update function in the controller
            $commentaireController->update(array(
                'id_comment' => $id_comment,
                'contenu' => $contenu
            ));
        }
    }
    header("Location: blog.php"); // Redirect after the update
    exit;
}

// Handle comment deletion
if (isset($_GET['delete_comment'])) {
    $commentaireController->delete($_GET['delete_comment']);
    header("Location: blog.php");
    exit;
}

// — Fetch all posts via controller
$posts = $postController->getAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Startup - Startup Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="../img/favicon.ico" rel="icon">
<style>
    .error-message {
    font-size: 0.9rem;
    display: none;
    padding: 5px;
    border-radius: 4px;
    background-color: #ffe6e6;
    border-left: 3px solid #ff5252;
}

.comment-input:invalid {
    border-color: #ff5252;
}
.comment-textarea.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.comment-textarea.is-invalid:focus {
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.comment-error {
    font-size: 0.875rem;
    padding: 0.375rem 0.5rem;
    border-radius: 0.25rem;
    background-color: rgba(220, 53, 69, 0.1);
}
/* Error styling */
.comment-textarea.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg...red-icon...%3e%3c/svg%3e");
}

/* Warning styling */
.comment-textarea.has-warning {
    border-color: #ffc107;
    background-image: url("data:image/svg+xml,%3csvg...warning-icon...%3e%3c/svg%3e");
}

.bad-word-warning {
    font-size: 0.875rem;
    padding: 0.375rem 0.5rem;
    border-radius: 0.25rem;
    background-color: rgba(255, 193, 7, 0.1);
    color: #856404;
    border-left: 3px solid #ffc107;
}

.comment-error {
    font-size: 0.875rem;
    padding: 0.375rem 0.5rem;
    border-radius: 0.25rem;
    background-color: rgba(220, 53, 69, 0.1);
    color: #721c24;
    border-left: 3px solid #dc3545;
}
</style>
    <!-- Google Web Fonts -->
   <!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Icon Font Stylesheets -->
<link href="../view/lib/font-awesome/css/all.min.css" rel="stylesheet">
<link href="../view/lib/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Libraries Stylesheets -->
<link href="../view/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="../view/lib/animate/animate.min.css" rel="stylesheet">

<!-- Bootstrap CSS -->
<link href="../view/css/bootstrap.min.css" rel="stylesheet">

<!-- Main CSS -->
<link href="../view/css/style.css" rel="stylesheet">

</head>

<body>
<!-- Spinner Start -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner"></div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>123 Street, New York, USA</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+012 345 6789</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>info@example.com</small>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-twitter fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-facebook-f fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-linkedin-in fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-instagram fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href=""><i class="fab fa-youtube fw-normal"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="index.html" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Startup</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.html" class="nav-item nav-link">Home</a>
                    <a href="about.html" class="nav-item nav-link">About</a>
                    <a href="service.html" class="nav-item nav-link">Services</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Blog</a>
                        <div class="dropdown-menu m-0">
                            <a href="blog.html" class="dropdown-item active">Blog Grid</a>
                            <a href="detail.html" class="dropdown-item">Blog Detail</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="price.html" class="dropdown-item">Pricing Plan</a>
                            <a href="feature.html" class="dropdown-item">Our features</a>
                            <a href="team.html" class="dropdown-item">Team Members</a>
                            <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                            <a href="quote.html" class="dropdown-item">Free Quote</a>
                        </div>
                    </div>
                    <a href="contact.html" class="nav-item nav-link">Contact</a>
                </div>
                <butaton type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></butaton>
                <a href="https://htmlcodex.com/startup-company-website-template" class="btn btn-primary py-2 px-4 ms-3">Download Pro Version</a>
            </div>
        </nav>

        <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
            <div class="row py-5">
                <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-4 text-white animated zoomIn">Blog Grid</h1>
                    <a href="" class="h5 text-white">Home</a>
                    <i class="far fa-circle text-white px-2"></i>
                    <a href="" class="h5 text-white">Blog Grid</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog Start -->
<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
        
        <div class="row g-5">
            <button class="add-course-btn" onclick="window.location.href='admin_dashboard.php'"
                    style="background-color: rgb(207,21,182); color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                <i class="fa fa-blog"></i> Manage Posts
            </button>
            
            <!-- Comment Search -->
            <input type="text" class="form-control mb-3" id="commentSearch" placeholder="Search comments...">
            
            <!-- Sort Dropdown -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <select id="sortComments" class="form-select w-25 ms-3">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="most_liked">Most Liked</option>
                    <option value="most_disliked">Most Disliked</option>
                </select>
            </div>

            <!-- Blog list Start -->
            <div class="col-lg-8">
                <div class="row g-5">
                    <?php foreach ($posts as $post): ?>
                        <div class="col-md-6 wow slideInUp" data-wow-delay="0.1s">
                            <div class="blog-item bg-light rounded overflow-hidden">
                                <div class="blog-img position-relative overflow-hidden">
                                    <?php if (!empty($post['imagePath']) && file_exists(__DIR__ . '/../uploads/' . $post['imagePath'])): ?>
                                        <img class="img-fluid" src="../uploads/<?php echo htmlspecialchars($post['imagePath']); ?>" alt="">
                                    <?php else: ?>
                                        <img class="img-fluid" src="../images/default.jpg" alt="Default image">
                                    <?php endif; ?>
                                    <a class="position-absolute top-0 start-0 bg-primary text-white rounded-end mt-5 py-2 px-4" href="#">
                                        <?php echo htmlspecialchars($post['type']); ?>
                                    </a>
                                </div>
                                <div class="p-4">
                                    <div class="d-flex mb-3">
                                        <small><i class="far fa-calendar-alt text-primary me-2"></i><?php echo $post['date_creation']; ?></small>
                                    </div>
                                    <h4 class="mb-3"><?php echo htmlspecialchars($post['questions']); ?></h4>

                                    <!-- Fetch comments for the post -->
                                    <?php $comments = $commentaireController->getByPost($post['id_post']); ?>

                                    <!-- Comment Form -->
                                    <form method="post" action="blog.php" class="comment-form" novalidate>
    <input type="hidden" name="action" value="add_comment">
    <input type="hidden" name="id_post" value="<?php echo $post['id_post']; ?>">
    <textarea name="contenu" class="form-control mb-2 comment-textarea" 
              placeholder="Write a comment..."></textarea>
    <div class="comment-error text-danger mb-2" style="display:none;"></div>
    <div class="bad-word-warning text-warning mb-2" style="display:none;"></div>
    <button type="submit" class="btn btn-sm btn-primary">Submit Comment</button>
</form>

<!-- Error Messages -->
<?php if (isset($_SESSION['comment_errors'][$post['id_post']])): ?>
    <div class="alert alert-danger mt-2">
        <?php 
        foreach ($_SESSION['comment_errors'][$post['id_post']] as $error) {
            echo htmlspecialchars($error) . '<br>';
        }
        unset($_SESSION['comment_errors'][$post['id_post']]);
        ?>
    </div>
<?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const BAD_WORDS = ['shit', 'fuck', 'shitty', 'curse']; // Your list here
    
    document.querySelectorAll('.comment-form').forEach(function(form) {
        var textarea = form.querySelector('.comment-textarea');
        var errorDiv = form.querySelector('.comment-error');
        var warningDiv = form.querySelector('.bad-word-warning');
        
        // Real-time validation
        textarea.addEventListener('input', function() {
            var comment = textarea.value.trim();
            
            // Clear previous messages
            errorDiv.style.display = 'none';
            warningDiv.style.display = 'none';
            textarea.classList.remove('is-invalid', 'has-warning');
            
            // Check for bad words first
            var detectedBadWords = checkForBadWords(comment);
            if (detectedBadWords.length > 0) {
                showWarning(textarea, warningDiv, 
                    '⚠️ Warning: Your comment contains restricted words (' + detectedBadWords.join(', ') + ')');
            }
        });
        
        form.addEventListener('submit', function(e) {
            var comment = textarea.value.trim();
            var isValid = true;
            
            // Reset states
            errorDiv.style.display = 'none';
            warningDiv.style.display = 'none';
            textarea.classList.remove('is-invalid', 'has-warning');
            
            // Validate empty comment
            if (comment === '') {
                showError(textarea, errorDiv, "⚠️ Please write a comment before submitting");
                isValid = false;
            }
            // Validate bad words on submit
            else if (checkForBadWords(comment).length > 0) {
                showError(textarea, errorDiv, "❌ Your comment cannot contain inappropriate language");
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
    
    function checkForBadWords(text) {
        return BAD_WORDS.filter(function(word) { 
            return new RegExp('\\b' + word + '\\b', 'i').test(text);
        });
    }
    
    function showError(element, errorDiv, message) {
        element.classList.add('is-invalid');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }
    
    function showWarning(element, warningDiv, message) {
        element.classList.add('has-warning');
        warningDiv.textContent = message;
        warningDiv.style.display = 'block';
    }
});
</script>

                                    <!-- Comments List -->
                                    <hr>
                                    <strong>Comments:</strong>
                                    <div id="commentsContainer">
                                        <?php foreach ($comments as $c): ?>
                                            <div class="bg-white p-2 mt-2 border rounded comment-box"
                                                 data-likes="<?php echo isset($c['likes']) ? $c['likes'] : 0; ?>"
                                                 data-dislikes="<?php echo isset($c['dislikes']) ? $c['dislikes'] : 0; ?>"
                                                 data-date="<?php echo strtotime($c['date_reponse']); ?>"
                                                 data-content="<?php echo strtolower(htmlspecialchars($c['contenu'])); ?>">

                                                <?php if (isset($_GET['edit_comment']) && $_GET['edit_comment'] == $c['id_comment']): ?>
                                                    <!-- Edit Form -->
                                                    <form method="post" action="blog.php">
                                                        <input type="hidden" name="action" value="update_comment">
                                                        <input type="hidden" name="id_comment" value="<?php echo $c['id_comment']; ?>">
                                                        <textarea class="form-control" name="contenu" required><?php echo htmlspecialchars($c['contenu']); ?></textarea>
                                                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                                                    </form>
                                                <?php else: ?>
                                                    <p class="mb-1"><?php echo htmlspecialchars($c['contenu']); ?></p>
                                                    <small class="text-muted"><?php echo $c['date_reponse']; ?></small><br>

                                                    <!-- Like & Dislike Buttons -->
                                                    <div class="mt-2">
                                                        <button id="like-<?php echo $c['id_comment']; ?>" class="btn btn-outline-success btn-sm me-1"
                                                                onclick="rateComment(<?php echo $c['id_comment']; ?>, 'like')">
                                                            👍 <span class="like-count"><?php echo isset($c['likes']) ? $c['likes'] : 0; ?></span>
                                                        </button>
                                                        <button id="dislike-<?php echo $c['id_comment']; ?>" class="btn btn-outline-danger btn-sm me-2"
                                                                onclick="rateComment(<?php echo $c['id_comment']; ?>, 'dislike')">
                                                            👎 <span class="dislike-count"><?php echo isset($c['dislikes']) ? $c['dislikes'] : 0; ?></span>
                                                        </button>
                                                    </div>

                                                    <!-- Edit Button -->
                                                    <button type="button" class="btn btn-sm btn-primary mt-2"
                                                            data-bs-toggle="modal" data-bs-target="#editCommentModal"
                                                            data-id="<?php echo $c['id_comment']; ?>" data-contenu="<?php echo htmlspecialchars($c['contenu']); ?>">
                                                        Edit
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <a href="blog.php?delete_comment=<?php echo $c['id_comment']; ?>" 
                                                       class="btn btn-sm btn-danger mt-2" 
                                                       onclick="return confirm('Delete this comment?')">
                                                        Delete
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Blog list End -->

        </div>
    </div>
</div>

<!-- Modal for editing comment -->
<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCommentForm" method="post" action="blog.php">
                    <input type="hidden" name="action" value="update_comment">
                    <input type="hidden" name="id_comment" id="commentId" value="">
                    <textarea class="form-control" name="contenu" id="commentContent" required></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="editCommentForm" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle the edit button click and populate the modal fields
    document.addEventListener('DOMContentLoaded', function () {
        var editButtons = document.querySelectorAll('[data-bs-toggle="modal"]');

        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var commentId = this.getAttribute('data-id');
                var commentContent = this.getAttribute('data-contenu');

                // Set the modal input values dynamically
                document.getElementById('commentId').value = commentId;
                document.getElementById('commentContent').value = commentContent;
            });
        });
    });

    // Search functionality
    document.getElementById('commentSearch').addEventListener('input', function () {
        var term = this.value.toLowerCase();
        var boxes = document.querySelectorAll('.comment-box');

        boxes.forEach(function(box) {
            var text = box.getAttribute('data-content'); // Search based on content
            box.style.display = text.indexOf(term) !== -1 ? 'block' : 'none';
        });
    });

    // Sorting functionality
    document.getElementById('sortComments').addEventListener('change', function () {
    var option = this.value;
    var container = document.getElementById('commentsContainer');
    var allComments = Array.prototype.slice.call(container.querySelectorAll('.comment-box'));

    console.log('Sorting option:', option); // Debug log

    // Sort the comments based on selected option
    allComments.sort(function(a, b) {
        var dateA = parseInt(a.getAttribute('data-date'));
        var dateB = parseInt(b.getAttribute('data-date'));
        var likesA = parseInt(a.getAttribute('data-likes'));
        var likesB = parseInt(b.getAttribute('data-likes'));
        var dislikesA = parseInt(a.getAttribute('data-dislikes'));
        var dislikesB = parseInt(b.getAttribute('data-dislikes'));

        switch (option) {
            case 'newest': 
                return dateB - dateA;  // Sort by date descending
            case 'oldest': 
                return dateA - dateB;  // Sort by date ascending
            case 'most_liked': 
                return likesB - likesA;  // Sort by likes descending
            case 'most_disliked': 
                return dislikesB - dislikesA;  // Sort by dislikes descending
            default:
                return 0;
        }
    });

    // Re-order the comments in the DOM
    allComments.forEach(function(comment) { container.appendChild(comment); });

    // Debug log to check the order after sorting
    console.log('Sorted comments:', allComments);
});

</script>


          <!-- Sidebar Start -->
          <div class="col-lg-4">
                    <!-- Recent Post Start -->
                    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
                        <div class="section-title section-title-sm position-relative pb-3 mb-4">
                            <h3 class="mb-0">Recent Post</h3>
                        </div>
                        <div class="d-flex rounded overflow-hidden mb-3">
                            <a href="" class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                            </a>
                        </div>
                        <div class="d-flex rounded overflow-hidden mb-3">
                            <a href="" class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                            </a>
                        </div>
                        <div class="d-flex rounded overflow-hidden mb-3">
                            <a href="" class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                            </a>
                        </div>
                        <div class="d-flex rounded overflow-hidden mb-3">
                            <a href="" class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                            </a>
                        </div>
                        <div class="d-flex rounded overflow-hidden mb-3">
=                            <a href="" class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                            </a>
                        </div>
                        <div class="d-flex rounded overflow-hidden mb-3">
                            <a href="" class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                            </a>
                        </div>
                    </div>
                    <!-- Recent Post End -->
    
                    <!-- Image Start -->
                    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
                    </div>
                    <!-- Image End -->
    
                    <!-- Tags Start -->
                    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
                        <div class="section-title section-title-sm position-relative pb-3 mb-4">
                            <h3 class="mb-0">Tag Cloud</h3>
                        </div>
                        <div class="d-flex flex-wrap m-n1">
                            <a href="" class="btn btn-light m-1">Design</a>
                            <a href="" class="btn btn-light m-1">Development</a>
                            <a href="" class="btn btn-light m-1">Marketing</a>
                            <a href="" class="btn btn-light m-1">SEO</a>
                            <a href="" class="btn btn-light m-1">Writing</a>
                            <a href="" class="btn btn-light m-1">Consulting</a>
                            <a href="" class="btn btn-light m-1">Design</a>
                            <a href="" class="btn btn-light m-1">Development</a>
                            <a href="" class="btn btn-light m-1">Marketing</a>
                            <a href="" class="btn btn-light m-1">SEO</a>
                            <a href="" class="btn btn-light m-1">Writing</a>
                            <a href="" class="btn btn-light m-1">Consulting</a>
                        </div>
                    </div>
                    <!-- Tags End -->
    
                    <!-- Plain Text Start -->
                    <div class="wow slideInUp" data-wow-delay="0.1s">
                        <div class="section-title section-title-sm position-relative pb-3 mb-4">
                            <h3 class="mb-0">Plain Text</h3>
                        </div>
                        <div class="bg-light text-center" style="padding: 30px;">
                            <p>Vero sea et accusam justo dolor accusam lorem consetetur, dolores sit amet sit dolor clita kasd justo, diam accusam no sea ut tempor magna takimata, amet sit et diam dolor ipsum amet diam</p>
                            <a href="" class="btn btn-primary py-2 px-4">Read More</a>
                        </div>
                    </div>
                    <!-- Plain Text End -->
                </div>
                <!-- Sidebar End -->
          <!-- Sidebar End -->

        </div>
      </div>
    </div>
    <!-- Blog End -->

<!-- Vendor Start -->
<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5 mb-5">
            <div class="bg-white">
                <div class="owl-carousel vendor-carousel">
                   
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor End -->
    

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6 footer-about">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-4">
                        <a href="index.html" class="navbar-brand">
                            <h1 class="m-0 text-white"><i class="fa fa-user-tie me-2"></i>Startup</h1>
                        </a>
                        <p class="mt-3 mb-4">Lorem diam sit erat dolor elitr et, diam lorem justo amet clita stet eos sit. Elitr dolor duo lorem, elitr clita ipsum sea. Diam amet erat lorem stet eos. Diam amet et kasd eos duo.</p>
                        <form action="">
                            <div class="input-group">
                                <input type="text" class="form-control border-white p-3" placeholder="Your Email">
                                <button class="btn btn-dark">Sign Up</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Get In Touch</h3>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <p class="mb-0">123 Street, New York, USA</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-primary me-2"></i>
                                <p class="mb-0">info@example.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <p class="mb-0">+012 345 67890</p>
                            </div>
                            <div class="d-flex mt-4">
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                                <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Quick Links</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our Services</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Meet The Team</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog</a>
                                <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Popular Links</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our Services</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Meet The Team</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog</a>
                                <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-white" style="background: #061429;">
        <div class="container text-center">
            <div class="row justify-content-end">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex align-items-center justify-content-center" style="height: 75px;">
                        <p class="mb-0">&copy; <a class="text-white border-bottom" href="#">Your Site Name</a>. All Rights Reserved. 
						
						<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
						Designed by <a class="text-white border-bottom" href="https://htmlcodex.com">HTML Codex</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

    <script>
function rateComment(commentId, type) {
    const key = `rated-${commentId}`;
    const rated = localStorage.getItem(key);

    if (rated) {
        alert("You already rated this comment.");
        return;
    }

    fetch('blog.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=rate_comment&type=${type}&id_comment=${commentId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`#like-${commentId} .like-count`).textContent = data.likes;
            document.querySelector(`#dislike-${commentId} .dislike-count`).textContent = data.dislikes;

            // Mark as rated
            localStorage.setItem(key, type);
        } else {
            alert("Failed to rate comment.");
        }
    })
    .catch(err => console.error('Rating error:', err));
}
</script>

<script>
document.getElementById('commentSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const commentBoxes = document.querySelectorAll('.comment-box');

    commentBoxes.forEach(box => {
        const text = box.textContent.toLowerCase();
        box.style.display = text.includes(searchTerm) ? 'block' : 'none';
    });
});
</script>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../view/lib/wow/wow.min.js"></script>
    <script src="../view/lib/easing/easing.min.js"></script>
    <script src="../view/lib/waypoints/waypoints.min.js"></script>
    <script src="../view/lib/counterup/counterup.min.js"></script>
    <script src="../view/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../view/js/wow.min.js"></script>
<script src="../view/js/easing.min.js"></script>
<script src="../view/js/waypoints.min.js"></script>
<script src="../view/js/counterup.min.js"></script>
<script src="../view/js/owl.carousel.min.js"></script>
<script src="../view/js/bootstrap.bundle.min.js"></script>
<script src="../view/js/main.js"></script>
    <!-- Template Javascript -->
       <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    
</body>
</html>