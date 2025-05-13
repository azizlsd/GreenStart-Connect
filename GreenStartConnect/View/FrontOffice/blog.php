<?php
// blog.php
// Handle comment update

// — Bootstrap your app
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../controller/PostController.php';
require_once __DIR__ . '/../../controller/CommentaireController.php';

$postController        = new PostController();
$commentaireController = new CommentaireController();


$posts = $posts ?? $postController->getAll();
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

    <meta charset="utf-8">
    <title>GreenStart Connect</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link rel="icon" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/logoweb.png" type="image/x-icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/assets/css/style.css" rel="stylesheet">
</head>

<body>
<!-- Spinner Start -->

    <!-- Spinner End -->


    <?php include __DIR__ . '/navbar.php'; ?>
    <!-- Blog Start -->
<div class="container-fluid  wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        
        <div class="row g-5">
        
            
            <!-- Comment Search -->
            <input type="text" class="form-control " id="commentSearch" placeholder="Search comments...">
            
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
                                    <?php if (!empty($post['imagePath']) && file_exists(__DIR__ . '../../' . $post['imagePath'])): ?>
                                        <img class="img-fluid" src="View/<?= htmlspecialchars($post['imagePath']) ?>" alt="">
                                    <?php else: ?>
                                        <img class="img-fluid" src="../images/default.jpg" alt="Default image">
                                    <?php endif; ?>
                                    <a class="position-absolute top-0 start-0 bg-primary text-white rounded-end mt-5 py-2 px-4" href="#">
                                        <?= htmlspecialchars($post['type']) ?>
                                    </a>
                                </div>
                                <div class="p-4">
                                    <div class="d-flex mb-3">
                                        <small><i class="far fa-calendar-alt text-primary me-2"></i><?= $post['date_creation'] ?></small>
                                    </div>
                                    <h4 class="mb-3"><?= htmlspecialchars($post['questions']) ?></h4>

                                    <!-- Fetch comments for the post -->
                                    <?php $comments = $commentaireController->getByPost($post['id_post']); ?>

                                    <!-- Comment Form -->
    <form method="post" action="/GreenStart-Connect-main/GreenStartConnect/index.php?action=addcomment" class="comment-form" novalidate>
    <input type="hidden" name="action" value="add_comment">
    <input type="hidden" name="id_post" value="<?= $post['id_post'] ?>">
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
    
    document.querySelectorAll('.comment-form').forEach(form => {
        const textarea = form.querySelector('.comment-textarea');
        const errorDiv = form.querySelector('.comment-error');
        const warningDiv = form.querySelector('.bad-word-warning');
        
        // Real-time validation
        textarea.addEventListener('input', function() {
            const comment = textarea.value.trim();
            
            // Clear previous messages
            errorDiv.style.display = 'none';
            warningDiv.style.display = 'none';
            textarea.classList.remove('is-invalid', 'has-warning');
            
            // Check for bad words first
            const detectedBadWords = checkForBadWords(comment);
            if (detectedBadWords.length > 0) {
                showWarning(textarea, warningDiv, 
                    `⚠️ Warning: Your comment contains restricted words (${detectedBadWords.join(', ')})`);
            }
        });
        
        form.addEventListener('submit', function(e) {
            const comment = textarea.value.trim();
            let isValid = true;
            
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
        return BAD_WORDS.filter(word => 
            new RegExp(`\\b${word}\\b`, 'i').test(text)
        );
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
                                                 data-likes="<?= $c['likes'] ?? 0 ?>"
                                                 data-dislikes="<?= $c['dislikes'] ?? 0 ?>"
                                                 data-date="<?= strtotime($c['date_reponse']) ?>"
                                                 data-content="<?= strtolower(htmlspecialchars($c['contenu'])) ?>">

                                                <?php if (isset($_GET['edit_comment']) && $_GET['edit_comment'] == $c['id_comment']): ?>
                                                    <!-- Edit Form -->
                                                    <form method="post" action="blog.php">
                                                        <input type="hidden" name="action" value="update_comment">
                                                        <input type="hidden" name="id_comment" value="<?= $c['id_comment'] ?>">
                                                        <textarea class="form-control" name="contenu" required><?= htmlspecialchars($c['contenu']) ?></textarea>
                                                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                                                    </form>
                                                <?php else: ?>
                                                    <p class="mb-1"><?= htmlspecialchars($c['contenu']) ?></p>
                                                    <small class="text-muted"><?= $c['date_reponse'] ?></small><br>

                                                    <!-- Like & Dislike Buttons -->
                                                    <div class="mt-2">
                                                        <button id="like-<?= $c['id_comment'] ?>" class="btn btn-outline-success btn-sm me-1"
                                                                onclick="rateComment(<?= $c['id_comment'] ?>, 'like')">
                                                            👍 <span class="like-count"><?= $c['likes'] ?? 0 ?></span>
                                                        </button>
                                                        <button id="dislike-<?= $c['id_comment'] ?>" class="btn btn-outline-danger btn-sm me-2"
                                                                onclick="rateComment(<?= $c['id_comment'] ?>, 'dislike')">
                                                            👎 <span class="dislike-count"><?= $c['dislikes'] ?? 0 ?></span>
                                                        </button>
                                                    </div>

                                                    <!-- Edit Button -->
                                                    <button type="button" class="btn btn-sm btn-primary mt-2"
                                                            data-bs-toggle="modal" data-bs-target="#editCommentModal"
                                                            data-id="<?= $c['id_comment'] ?>" data-contenu="<?= htmlspecialchars($c['contenu']) ?>">
                                                        Edit
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <a href="/GreenStart-Connect-main/GreenStartConnect/index.php?action=deletecomment&id=<?= $c['id_comment'] ?>" 
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
                <form id="editCommentForm" method="post" action="/GreenStart-Connect-main/GreenStartConnect/index.php?action=updatecomment">
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
        const editButtons = document.querySelectorAll('[data-bs-toggle="modal"]');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const commentId = this.getAttribute('data-id');
                const commentContent = this.getAttribute('data-contenu');

                // Set the modal input values dynamically
                document.getElementById('commentId').value = commentId;
                document.getElementById('commentContent').value = commentContent;
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('commentSearch');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();

            document.querySelectorAll('.comment-item').forEach(comment => {
                const content = comment.textContent.toLowerCase();
                comment.style.display = content.includes(query) ? 'block' : 'none';
            });
        });
    }
});

    // Sorting functionality
    document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('sortComments').addEventListener('change', function () {
    const option = this.value;
    const container = document.getElementById('commentsContainer');
    const allComments = Array.from(container.querySelectorAll('.comment-box'));

    // Debug
    console.log('Sorting option:', option, allComments);

    allComments.sort((a, b) => {
      const dateA = parseInt(a.getAttribute('data-date'));
      const dateB = parseInt(b.getAttribute('data-date'));
      const likesA = parseInt(a.getAttribute('data-likes'));
      const likesB = parseInt(b.getAttribute('data-likes'));
      const dislikesA = parseInt(a.getAttribute('data-dislikes'));
      const dislikesB = parseInt(b.getAttribute('data-dislikes'));

      switch (option) {
        case 'newest': return dateB - dateA;
        case 'oldest': return dateA - dateB;
        case 'most_liked': return likesB - likesA;
        case 'most_disliked': return dislikesB - dislikesA;
        default: return 0;
      }
    });

    // Reorder in DOM
    allComments.forEach(comment => container.appendChild(comment));
  });
});


</script>
    <!-- Chatbot Widget -->
    <div id="chatbot" style="position: fixed; bottom: 20px; right: 20px; width: 300px; font-family: sans-serif; z-index: 9999;">
  <div style="background: #333; color: white; padding: 10px; border-radius: 10px 10px 0 0;">Ask Me About This Blog</div>
  <div id="chatlog" style="height: 200px; background: #f9f9f9; overflow-y: auto; padding: 10px; border: 1px solid #ccc;"></div>
  <input type="text" id="chatInput" placeholder="Type your question..." style="width: 100%; padding: 10px; border: 1px solid #ccc; border-top: none;" />
</div>


<script>
  const input = document.getElementById("chatInput");
  const chatlog = document.getElementById("chatlog");

  input.addEventListener("keypress", async (e) => {
    if (e.key === "Enter") {
      const question = input.value.trim();
      if (!question) return;
      chatlog.innerHTML += `<div><strong>You:</strong> ${question}</div>`;
      input.value = "";

      const res = await fetch("/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/chatbot.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ question }),
      });

      const data = await res.json();
      chatlog.innerHTML += `<div><strong>Bot:</strong> ${data.answer}</div>`;
      chatlog.scrollTop = chatlog.scrollHeight;
    }
  });
</script>

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

    fetch(`/GreenStart-Connect-main/GreenStartConnect/index.php?action=ratecomment&type=${type}&id_comment=${commentId}`, {
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
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/counterup/counterup.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../js/wow.min.js"></script>
<script src="../js/easing.min.js"></script>
<script src="../js/waypoints.min.js"></script>
<script src="../js/counterup.min.js"></script>
<script src="../js/owl.carousel.min.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>
    <!-- Template Javascript -->
       <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    
</body>
</html>
