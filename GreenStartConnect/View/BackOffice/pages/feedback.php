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


    <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/css/feedback.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* Additional styles for response functionality */
        .response-section {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .response-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge-solution { background-color: #4CAF50; color: white; }
        .badge-information { background-color: #2196F3; color: white; }
        .badge-rejection { background-color: #F44336; color: white; }
        .badge-follow_up { background-color: #FF9800; color: white; }
        .badge-public { background-color: #4CAF50; color: white; }
        .badge-private { background-color: #9E9E9E; color: white; }
        .response-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .response-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .response-actions {
            margin-top: 5px;
        }
        .tab-container {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-bottom: none;
            margin-right: 5px;
            border-radius: 5px 5px 0 0;
        }
        .tab.active {
            background-color: #fff;
            border-bottom: 1px solid #fff;
            margin-bottom: -1px;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        /* Add to your existing styles */
.stats-container {
    padding: 20px;
}

.stats-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.stats-card {
    flex: 1;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 20px;
}

.stats-card h3 {
    margin-top: 0;
    color: #333;
    font-size: 18px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.chart-container {
    position: relative;
    height: 250px;
    margin-bottom: 15px;
}

.stats-numbers {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.stat-label {
    font-weight: 500;
    color: #555;
}

.stat-value {
    font-weight: 600;
    color: #333;
}
    </style>
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
    <div class="feedback-container">
        <h1><i class="fas fa-comment-dots"></i> Feedback Management</h1>
        
        <div class="tab-container">
            <div class="tab active" data-tab="feedback">Feedbacks</div>
            <div class="tab" data-tab="response">Responses</div>
            <div class="tab" data-tab="stats">Statistics</div>

        </div>
        
        <!-- Feedback Tab Content -->
        <div id="feedback-tab" class="tab-content active">
            <div class="feedback-controls">
                <button id="add-feedback-btn" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Feedback
                </button>
                <div class="form-group">
                    <select id="type-filter" class="form-select">
                        <option value="">-- Filter by type --</option>
                        <option value="complaint">Complaint</option>
                        <option value="suggestion">Suggestion</option>
                        <option value="praise">Praise</option>
                        <option value="question">Question</option>
                    </select>
                </div>
                <div class="search-box">
                    <input type="text" id="feedback-search" placeholder="Search feedback...">
                    <button id="search-btn" class="btn btn-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <div class="feedback-table-container">
                <table id="feedback-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Content</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="feedback-table-body">
                        <!-- Feedback items will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div class="pagination">
                <button id="prev-page" class="btn btn-secondary" disabled>
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <span id="page-info">Page 1 of 1</span>
                <button id="next-page" class="btn btn-secondary" disabled>
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        <!-- Statistics Tab Content -->
<div id="stats-tab" class="tab-content">
    <div class="stats-container">
        <div class="stats-row">
            <div class="stats-card">
                <h3><i class="fas fa-comments"></i> Feedback Overview</h3>
                <div class="chart-container">
                    <canvas id="feedbackTypeChart"></canvas>
                </div>
                <div class="stats-numbers">
                    <div><span class="stat-label">Total:</span> <span id="total-feedbacks" class="stat-value">0</span></div>
                    <div><span class="stat-label">Complaints:</span> <span id="total-complaints" class="stat-value">0</span></div>
                    <div><span class="stat-label">Suggestions:</span> <span id="total-suggestions" class="stat-value">0</span></div>
                    <div><span class="stat-label">Praise:</span> <span id="total-praise" class="stat-value">0</span></div>
                    <div><span class="stat-label">Questions:</span> <span id="total-questions" class="stat-value">0</span></div>
                </div>
            </div>
            
            <div class="stats-card">
                <h3><i class="fas fa-reply"></i> Response Overview</h3>
                <div class="chart-container">
                    <canvas id="responseTypeChart"></canvas>
                </div>
                <div class="stats-numbers">
                    <div><span class="stat-label">Total:</span> <span id="total-responses" class="stat-value">0</span></div>
                    <div><span class="stat-label">Solutions:</span> <span id="total-solutions" class="stat-value">0</span></div>
                    <div><span class="stat-label">Information:</span> <span id="total-information" class="stat-value">0</span></div>
                    <div><span class="stat-label">Rejections:</span> <span id="total-rejections" class="stat-value">0</span></div>
                    <div><span class="stat-label">Follow-ups:</span> <span id="total-followups" class="stat-value">0</span></div>
                </div>
            </div>
        </div>
        
        <div class="stats-row">
            <div class="stats-card">
                <h3><i class="fas fa-calendar-alt"></i> Feedback Timeline</h3>
                <div class="chart-container">
                    <canvas id="feedbackTimelineChart"></canvas>
                </div>
            </div>
            
            <div class="stats-card">
                <h3><i class="fas fa-chart-pie"></i> Feedback vs Response</h3>
                <div class="chart-container">
                    <canvas id="feedbackResponseRatioChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
        
        <!-- Response Tab Content -->
        <div id="response-tab" class="tab-content">
            <div class="feedback-controls">
                <button id="add-response-btn" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Response
                </button>
                <div class="form-group">
                    <select id="response-type-filter" class="form-select">
                        <option value="">-- Filter by type --</option>
                        <option value="solution">Solution</option>
                        <option value="information">Information</option>
                        <option value="rejection">Rejection</option>
                        <option value="follow_up">Follow Up</option>
                    </select>
                </div>
                <div class="form-group">
                    <select id="feedback-id-filter" class="form-select">
                        <option value="">-- Filter by feedback --</option>
                        <!-- Will be populated by JS -->
                    </select>
                </div>
                <div class="form-group">
                    <select id="public-filter" class="form-select">
                        <option value="">-- All visibility --</option>
                        <option value="1">Public</option>
                        <option value="0">Private</option>
                    </select>
                </div>
                <div class="search-box">
                    <input type="text" id="response-search" placeholder="Search responses...">
                    <button id="response-search-btn" class="btn btn-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <div class="feedback-table-container">
                <table id="response-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Feedback ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Visibility</th>
                            <th>Content</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="response-table-body">
                        <!-- Response items will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div class="pagination">
                <button id="response-prev-page" class="btn btn-secondary" disabled>
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <span id="response-page-info">Page 1 of 1</span>
                <button id="response-next-page" class="btn btn-secondary" disabled>
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Feedback Modal (hidden by default) -->
    <div id="feedback-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2 id="modal-title">Add New Feedback</h2>
            <form id="feedback-form">
                <input type="hidden" id="feedback-id">
                
                <div class="form-group">
                    <label for="user-id">User:</label>
                    <select id="user-id" required>
                        <option value="">Select a user</option>
                        <!-- Users will be loaded from database -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="feedback-type">Type:</label>
                    <select id="feedback-type" required>
                        <option value="">Select a type</option>
                        <option value="complaint">Complaint</option>
                        <option value="suggestion">Suggestion</option>
                        <option value="praise">Praise</option>
                        <option value="question">Question</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="feedback-content">Content:</label>
                    <textarea id="feedback-content" rows="5" required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" id="cancel-btn" class="btn btn-secondary">Cancel</button>
                    <button type="submit" id="save-btn" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Response Modal (hidden by default) -->
    <div id="response-modal" class="modal">
        <div class="modal-content">
            <span class="response-close-btn">&times;</span>
            <h2 id="response-modal-title">Add New Response</h2>
            <form id="response-form">
                <input type="hidden" id="response-id">
                
                <div class="form-group">
                    <label for="response-feedback-id">Feedback:</label>
                    <select id="response-feedback-id" required>
                        <option value="">Select a feedback</option>
                        <!-- Feedbacks will be loaded from database -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="response-user-id">User:</label>
                    <select id="response-user-id" required>
                        <option value="">Select a user</option>
                        <!-- Users will be loaded from database -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="response-type">Type:</label>
                    <select id="response-type" required>
                        <option value="">Select a type</option>
                        <option value="solution">Solution</option>
                        <option value="information">Information</option>
                        <option value="rejection">Rejection</option>
                        <option value="follow_up">Follow Up</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="response-content">Content:</label>
                    <textarea id="response-content" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="is-public">
                        <input type="checkbox" id="is-public"> Make this response public
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="button" id="response-cancel-btn" class="btn btn-secondary">Cancel</button>
                    <button type="submit" id="response-save-btn" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div id="confirm-modal" class="modal">
        <div class="modal-content confirm-modal">
            <p id="confirm-message">Are you sure you want to delete this item?</p>
            <div class="confirm-actions">
                <button id="confirm-cancel" class="btn btn-secondary">Cancel</button>
                <button id="confirm-delete" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>

    <!-- View Feedback Responses Modal -->
    <div id="view-responses-modal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <span class="view-responses-close-btn">&times;</span>
            <h2>Responses for Feedback #<span id="feedback-id-header"></span></h2>
            <div id="feedback-content-display" class="response-details"></div>
            <div id="responses-list">
                <!-- Responses will be loaded here -->
            </div>
            <div class="form-actions" style="margin-top: 20px;">
                <button type="button" id="add-response-to-feedback-btn" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Response
                </button>
                <button type="button" id="close-responses-btn" class="btn btn-secondary">Close</button>
            </div>
        </div>
          </div>
        </div>
    </div>

    <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/feedback.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/response.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs and content
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class ²to clicked tab and corresponding content
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab') + '-tab';
                document.getElementById(tabId).classList.add('active');
            });
        });

        // You would integrate the response JavaScript code here
        // The full implementation would be similar to your feedback.js
        // but adapted for responses as shown in the previous example
    </script>
</body>
</html>