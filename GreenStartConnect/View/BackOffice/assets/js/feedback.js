document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const feedbackTableBody = document.getElementById('feedback-table-body');
    const addFeedbackBtn = document.getElementById('add-feedback-btn');
    const feedbackModal = document.getElementById('feedback-modal');
    const confirmModal = document.getElementById('confirm-modal');
    const closeBtn = document.querySelector('.close-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const feedbackForm = document.getElementById('feedback-form');
    const userIdSelect = document.getElementById('user-id');
    const feedbackSearch = document.getElementById('feedback-search');
    const searchBtn = document.getElementById('search-btn');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const pageInfo = document.getElementById('page-info');
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    const confirmCancelBtn = document.getElementById('confirm-cancel');
    const typeFilterSelect = document.getElementById('type-filter');

    
    // State
    let currentPage = 1;
    const itemsPerPage = 10;
    let currentSearch = '';
    let currentType = '';
    let feedbackToDelete = null;
    
    // Initialize
    loadUsers();
    loadFeedbacks();
    
    // Event Listeners
    addFeedbackBtn.addEventListener('click', openAddModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    feedbackForm.addEventListener('submit', handleFormSubmit);
    searchBtn.addEventListener('click', handleSearch);
    feedbackSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') handleSearch();
    });
    prevPageBtn.addEventListener('click', goToPrevPage);
    nextPageBtn.addEventListener('click', goToNextPage);
    confirmDeleteBtn.addEventListener('click', confirmDelete);
    confirmCancelBtn.addEventListener('click', closeConfirmModal);
    typeFilterSelect.addEventListener('change', handleSearch);

    
    // Modal click outside
    window.addEventListener('click', function(event) {
        if (event.target === feedbackModal) {
            closeModal();
        }
        if (event.target === confirmModal) {
            closeConfirmModal();
        }
    });
    
    // API Functions
    async function getAllFeedbacks(page = 1, limit = 10, search = '') {
         const selectElement = document.getElementById('type-filter');
        try {
            const response = await fetch(`/GreenStart-Connect-main/GreenStartConnect/Controller/feedbacks.php?page=${page}&limit=${limit}&search=${encodeURIComponent(search)}&type=${encodeURIComponent(selectElement.value)}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error fetching feedbacks:', error);
            showError('Failed to load feedbacks');
            throw error;
        }
    }
    
    async function getFeedbackById(id) {
        try {
            const response = await fetch(`/GreenStart-Connect-main/GreenStartConnect/Controller/feedbacks.php?id=${id}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error fetching feedback:', error);
            showError('Failed to load feedback details');
            throw error;
        }
    }
    
    async function createFeedback(feedback) {
        try {
            const response = await fetch('/GreenStart-Connect-main/GreenStartConnect/Controller/feedbacks.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(feedback)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error creating feedback:', error);
            showError('Failed to create feedback');
            throw error;
        }
    }
    
    async function updateFeedback(id, feedback) {
        try {
            const response = await fetch(`/GreenStart-Connect-main/GreenStartConnect/Controller/feedbacks.php?id=${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(feedback)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error updating feedback:', error);
            showError('Failed to update feedback');
            throw error;
        }
    }
    
    async function deleteFeedback(id) {
        try {
            const response = await fetch(`/GreenStart-Connect-main/GreenStartConnect/Controller/feedbacks.php?id=${id}`, {
                method: 'DELETE'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error deleting feedback:', error);
            showError('Failed to delete feedback');
            throw error;
        }
    }
    
    async function getAllUsers() {
        try {
            const response = await fetch('/GreenStart-Connect-main/GreenStartConnect/Controller/users.php');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
             console.log(response)
            return await response.json();
        } catch (error) {
            console.error('Error fetching users:', error);
            showError('Failed to load users');
            throw error;
        }
    }
    
    // UI Functions
    async function loadUsers() {
        try {
            const users = await getAllUsers();
            userIdSelect.innerHTML = '<option value="">Select a user</option>';
            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.nom} (${user.adresse})`;
                userIdSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }
    
    async function loadFeedbacks() {
        try {
            const { data, total, page, totalPages } = await getAllFeedbacks(
                currentPage, 
                itemsPerPage, 
                currentSearch
            );
            
            renderFeedbacks(data);
            updatePagination(total, page, totalPages);
        } catch (error) {
            console.error('Error loading feedbacks:', error);
        }
    }
    
    function renderFeedbacks(feedbacks) {
        feedbackTableBody.innerHTML = '';
        
        if (!feedbacks || feedbacks.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="6" class="no-data">No feedbacks found</td>`;
            feedbackTableBody.appendChild(row);
            return;
        }
        
        feedbacks.forEach(feedback => {
            const row = document.createElement('tr');
            
            // Format date
            const date = new Date(feedback.created_at);
            const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
            
            // Determine type badge
            let typeBadge = '';
            switch(feedback.type) {
                case 'complaint':
                    typeBadge = `<span class=" badge-red">Complaint</span>`;
                    break;
                case 'suggestion':
                    typeBadge = `<span class=" badge-blue">Suggestion</span>`;
                    break;
                case 'praise':
                    typeBadge = `<span class=" badge-green">Praise</span>`;
                    break;
                case 'question':
                    typeBadge = `<span class=" badge-orange">Question</span>`;
                    break;
                default:
                    typeBadge = `<span  >${feedback.type}</span>`;
            }
            
            row.innerHTML = `
                <td>${feedback.id}</td>
                <td>${feedback.user_name || 'Unknown'}</td>
                <td>${typeBadge}</td>
                <td>${feedback.content.substring(0, 50)}${feedback.content.length > 50 ? '...' : ''}</td>
                <td>${formattedDate}</td>
                <td class="action-btns">
                    <button class="btn btn-secondary btn-sm edit-btn" data-id="${feedback.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="${feedback.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            feedbackTableBody.appendChild(row);
        });
        
        // Add event listeners to action buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                openEditModalf(id);
            });
        });
        
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                openConfirmModal(id);
            });
        });
    }
    
    function updatePagination(total, page, totalPages) {
        pageInfo.textContent = `Page ${page} of ${totalPages}`;
        prevPageBtn.disabled = page <= 1;
        nextPageBtn.disabled = page >= totalPages;
    }
    
    function openAddModal() {
        document.getElementById('modal-title').textContent = 'Add New Feedback';
        document.getElementById('feedback-id').value = '';
        feedbackForm.reset();
        feedbackModal.style.display = 'block';
    }
    
    async function openEditModalf(id) {
        try {
            const feedback = await getFeedbackById(id);
            if (!feedback) {
                showError('Feedback not found');
                return;
            }
            console.log(feedback)
            document.getElementById('modal-title').textContent = 'Edit Feedback';
            document.getElementById('feedback-id').value = feedback.id;
            document.getElementById('user-id').value = feedback.user_id;
            document.getElementById('feedback-type').value = feedback.type;
            document.getElementById('feedback-content').value = feedback.content;
            
            feedbackModal.style.display = 'block';
        } catch (error) {
            console.error('Error opening edit modal:', error);
        }
    }
    
    function openConfirmModal(id) {
        feedbackToDelete = id;
        confirmModal.style.display = 'block';
    }
    
    function closeModal() {
        feedbackModal.style.display = 'none';
    }
    
    function closeConfirmModal() {
        feedbackToDelete = null;
        confirmModal.style.display = 'none';
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = document.getElementById('feedback-id').value;
        const userId = document.getElementById('user-id').value;
        const type = document.getElementById('feedback-type').value;
        const content = document.getElementById('feedback-content').value;
        
        const feedbackData = {
            user_id: parseInt(userId),
            type,
            content
        };
        
        try {
            if (id) {
                // Update existing feedback
                await updateFeedback(parseInt(id), feedbackData);
                showSuccess('Feedback updated successfully');
            } else {
                // Create new feedback
                await createFeedback(feedbackData);
                showSuccess('Feedback created successfully');
            }
            
            closeModal();
            loadFeedbacks();
        } catch (error) {
            console.error('Error saving feedback:', error);
        }
    }
    
    async function confirmDelete() {
        if (!feedbackToDelete) return;
        
        try {
            await deleteFeedback(feedbackToDelete);
            showSuccess('Feedback deleted successfully');
            closeConfirmModal();
            loadFeedbacks();
        } catch (error) {
            console.error('Error deleting feedback:', error);
        }
    }
    
    function handleSearch() {
        currentSearch = feedbackSearch.value.trim();
        currentType = typeFilterSelect.value.trim
        currentPage = 1;
        loadFeedbacks();
    }
    
    function goToPrevPage() {
        if (currentPage > 1) {
            currentPage--;
            loadFeedbacks();
        }
    }
    
    function goToNextPage() {
        currentPage++;
        loadFeedbacks();
    }
    
    function showSuccess(message) {
        // In a real app, you might use a toast notification library
        alert(message);
    }
    
    function showError(message) {
        // In a real app, you might use a toast notification library
        alert(`Error: ${message}`);
    }
    // Add this to your existing JavaScript
let feedbackTypeChart, responseTypeChart, feedbackTimelineChart, feedbackResponseRatioChart;

async function loadStatistics() {
    try {
        const statsUrl = '/GreenStart-Connect-main/GreenStartConnect/Controller/feedback_stats.php';
        console.log('Fetching statistics from:', statsUrl);
        
        const response = await fetch(statsUrl);
        
        // First, check if the response is OK (status 200-299)
        if (!response.ok) {
            const text = await response.text();
            console.error('Server responded with error:', text);
            throw new Error(`Server error: ${response.status} ${response.statusText}`);
        }
        
        // Then try to parse as JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Received non-JSON response:', text);
            throw new Error('Response is not JSON');
        }
        
        const stats = await response.json();
        console.log('Received stats data:', stats);
        
        updateStatsNumbers(stats);
        createCharts(stats);
        
    } catch (error) {
        console.error('Error loading statistics:', error);
        showError('Failed to load statistics: ' + error.message);
    }
}

function updateStatsNumbers(stats) {
    // Check if elements exist before trying to update them
    const elements = {
        'total-feedbacks': stats.feedback.total,
        'total-complaints': stats.feedback.types.complaint,
        'total-suggestions': stats.feedback.types.suggestion,
        'total-praise': stats.feedback.types.praise,
        'total-questions': stats.feedback.types.question,
        'total-responses': stats.response.total,
        'total-solutions': stats.response.types.solution,
        'total-information': stats.response.types.information,
        'total-rejections': stats.response.types.rejection,
        'total-followups': stats.response.types.follow_up
    };

    for (const [id, value] of Object.entries(elements)) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    }
}

function createCharts(stats) {
    // Destroy existing charts if they exist
    [feedbackTypeChart, responseTypeChart, feedbackTimelineChart, feedbackResponseRatioChart].forEach(chart => {
        if (chart) chart.destroy();
    });

    try {
        // Feedback Type Chart (Doughnut)
        const feedbackTypeCtx = document.getElementById('feedbackTypeChart')?.getContext('2d');
        if (feedbackTypeCtx) {
            feedbackTypeChart = new Chart(feedbackTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Complaints', 'Suggestions', 'Praise', 'Questions'],
                    datasets: [{
                        data: [
                            stats.feedback.types.complaint,
                            stats.feedback.types.suggestion,
                            stats.feedback.types.praise,
                            stats.feedback.types.question
                        ],
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#4BC0C0',
                            '#FFCE56'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Response Type Chart (Pie)
        const responseTypeCtx = document.getElementById('responseTypeChart')?.getContext('2d');
        if (responseTypeCtx) {
            responseTypeChart = new Chart(responseTypeCtx, {
                type: 'pie',
                data: {
                    labels: ['Solutions', 'Information', 'Rejections', 'Follow-ups'],
                    datasets: [{
                        data: [
                            stats.response.types.solution,
                            stats.response.types.information,
                            stats.response.types.rejection,
                            stats.response.types.follow_up
                        ],
                        backgroundColor: [
                            '#4CAF50',
                            '#2196F3',
                            '#F44336',
                            '#FF9800'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Feedback Timeline Chart (Line)
        const timelineCtx = document.getElementById('feedbackTimelineChart')?.getContext('2d');
        if (timelineCtx) {
            feedbackTimelineChart = new Chart(timelineCtx, {
                type: 'line',
                data: {
                    labels: stats.timeline.labels,
                    datasets: [{
                        label: 'Feedbacks',
                        data: stats.timeline.data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Feedback vs Response Ratio Chart (Bar)
        const ratioCtx = document.getElementById('feedbackResponseRatioChart')?.getContext('2d');
        if (ratioCtx) {
            feedbackResponseRatioChart = new Chart(ratioCtx, {
                type: 'bar',
                data: {
                    labels: ['Feedbacks', 'Responses'],
                    datasets: [{
                        label: 'Count',
                        data: [stats.feedback.total, stats.response.total],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    } catch (chartError) {
        console.error('Error creating charts:', chartError);
        showError('Failed to create charts: ' + chartError.message);
    }
}

// Add event listener for the stats tab - only once
document.querySelector('[data-tab="stats"]')?.addEventListener('click', loadStatistics);
    // async function  filterByType() {
    //     const selectElement = document.getElementById('type-filter');
    //     this.currentType = selectElement.value;

    
    //     try {
    //         const response = await fetch(`/GreenStart-Connect-main/GreenStartConnect/Controller/feedbacks.php?page=${currentPage}&limit=${itemsPerPage}&search=${encodeURIComponent(this.currentSearch)}&type=${encodeURIComponent(this.currentType)}`);
    //         if (!response.ok) {
    //             throw new Error(`HTTP error! status: ${response.status}`);
    //         }
    //         renderFeedbacks(data);
    //         return await response.json();
    //     } catch (error) {
    //         console.error('Error fetching feedbacks:', error);
    //         showError('Failed to load feedbacks');
    //         throw error;
    //     }
    // }
});

