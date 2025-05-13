document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const responseTableBody = document.getElementById('response-table-body');
    const addResponseBtn = document.getElementById('add-response-btn');
    const responseModal = document.getElementById('response-modal');
    const confirmModal = document.getElementById('confirm-modal');
    const closeBtn = document.querySelector('.close-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const responseForm = document.getElementById('response-form');
    const userIdSelect = document.getElementById('user-id');
    const feedbackIdSelect = document.getElementById('feedback-id');
    const responseSearch = document.getElementById('response-search');
    const searchBtn = document.getElementById('search-btn');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const pageInfo = document.getElementById('page-info');
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    const confirmCancelBtn = document.getElementById('confirm-cancel');
    const typeFilterSelect = document.getElementById('type-filter');
    const publicFilterSelect = document.getElementById('public-filter');

    // State
    let currentPage = 1;
    const itemsPerPage = 10;
    let currentSearch = '';
    let currentType = '';
    let currentPublic = '';
    let currentFeedbackId = '';
    let responseToDelete = null;
    
    // Initialize
    loadUsers();
    loadFeedbacks();
    loadResponses();
    
    // Event Listeners
    addResponseBtn.addEventListener('click', openAddModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    responseForm.addEventListener('submit', handleFormSubmit);
    searchBtn.addEventListener('click', handleSearch);
    responseSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') handleSearch();
    });
    prevPageBtn.addEventListener('click', goToPrevPage);
    nextPageBtn.addEventListener('click', goToNextPage);
    confirmDeleteBtn.addEventListener('click', confirmDelete);
    confirmCancelBtn.addEventListener('click', closeConfirmModal);
    typeFilterSelect.addEventListener('change', handleSearch);
    publicFilterSelect.addEventListener('change', handleSearch);
    feedbackIdSelect.addEventListener('change', handleSearch);

    // Modal click outside
    window.addEventListener('click', function(event) {
        if (event.target === responseModal) {
            closeModal();
        }
        if (event.target === confirmModal) {
            closeConfirmModal();
        }
    });
    
    // API Functions
    async function getAllResponses(page = 1, limit = 10, search = '', type = '', isPublic = '', feedbackId = '') {
        try {
            let url = `/GreenStart-Connect-main/GreenStartConnect/Controller/responses.php?page=${page}&limit=${limit}`;
            if (search) url += `&search=${encodeURIComponent(search)}`;
            if (type) url += `&type=${encodeURIComponent(type)}`;
            if (isPublic !== '') url += `&is_public=${encodeURIComponent(isPublic)}`;
            if (feedbackId) url += `&feedback_id=${encodeURIComponent(feedbackId)}`;
    
            const response = await fetch(url);
            
            // First check if the response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Invalid response: ${text.substring(0, 100)}`);
            }
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || 'Unknown error');
            }
            
            return data;
        } catch (error) {
            console.error('Error fetching responses:', error);
            showError(`Failed to load responses: ${error.message}`);
            throw error;
        }
    }
    
    async function getResponseById(id) {
        try {
            const response = await fetch(`/GreenStart-Connect-main/GreenStartConnect/Controller/responses.php?id=${id}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error fetching response:', error);
            showError('Failed to load response details');
            throw error;
        }
    }
    
    async function createResponse(responseData) {
    try {
        console.log('Sending data:', responseData); // Add this line
        const response = await fetch('/GreenStart-Connect-main/GreenStartConnect/Controller/responses.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(responseData)
        });
        
        if (!response.ok) {
            const errorData = await response.json(); // Try to get error details
            console.error('Server error details:', errorData);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('Error creating response:', error);
        showError('Failed to create response');
        throw error;
    }
}
    
    async function updateResponse(id, responseData) {
        try {
            const response = await fetch(`/GreenStart-Connect-main/GreenStartConnect/Controller/responses.php?id=${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseData)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error updating response:', error);
            showError('Failed to update response');
            throw error;
        }
    }
    
    async function deleteResponse(id) {
        try {
            const response = await fetch(`/GreenStart-Connect-main/GreenStartConnect/Controller/responses.php?id=${id}`, {
                method: 'DELETE'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error deleting response:', error);
            showError('Failed to delete response');
            throw error;
        }
    }
    
    async function getAllUsers() {
        try {
            const response = await fetch('/GreenStart-Connect-main/GreenStartConnect/Controller/users.php');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error fetching users:', error);
            showError('Failed to load users');
            throw error;
        }
    }
    
    async function getAllFeedbacks() {
        try {
            const response = await fetch('/GreenStart-Connect-main/GreenStartConnect/Controller/feedbacks.php');
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
            const feedbacks = await getAllFeedbacks();
            feedbackIdSelect.innerHTML = '<option value="">Select a feedback</option>';
            feedbacks.data.forEach(feedback => {
                const option = document.createElement('option');
                option.value = feedback.id;
                option.textContent = `#${feedback.id} - ${feedback.content.substring(0, 30)}...`;
                feedbackIdSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading feedbacks:', error);
        }
    }
    
    async function loadResponses() {
        try {
            const { data, total, page, totalPages } = await getAllResponses(
                currentPage, 
                itemsPerPage, 
                currentSearch,
                currentType,
                currentPublic,
                currentFeedbackId
            );
            
            renderResponses(data);
            updatePagination(total, page, totalPages);
        } catch (error) {
            console.error('Error loading responses:', error);
        }
    }
    
    function renderResponses(responses) {
        responseTableBody.innerHTML = '';
        
        if (!responses || responses.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="7" class="no-data">No responses found</td>`;
            responseTableBody.appendChild(row);
            return;
        }
        
        responses.forEach(response => {
            const row = document.createElement('tr');
            
            // Format date
            const date = new Date(response.created_at);
            const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
            
            // Determine type badge
            let typeBadge = '';
            switch(response.response_type) {
                case 'solution':
                    typeBadge = `<span class="badge-green">Solution</span>`;
                    break;
                case 'information':
                    typeBadge = `<span class="badge-blue">Information</span>`;
                    break;
                case 'rejection':
                    typeBadge = `<span class="badge-red">Rejection</span>`;
                    break;
                case 'follow_up':
                    typeBadge = `<span class="badge-orange">Follow Up</span>`;
                    break;
                default:
                    typeBadge = `<span class="badge">${response.response_type}</span>`;
            }
            
            // Public/private badge
            const publicBadge = response.is_public 
                ? `<span class="badge-green">Public</span>`
                : `<span class="badge-gray">Private</span>`;
            
            row.innerHTML = `
                <td>${response.id}</td>
                <td>#${response.feedback_id}</td>
                <td>${response.user_name || 'Unknown'}</td>
                <td>${typeBadge}</td>
                <td>${publicBadge}</td>
                <td>${response.content.substring(0, 50)}${response.content.length > 50 ? '...' : ''}</td>
                <td>${formattedDate}</td>
                <td class="action-btns">
                    <button class="btn btn-secondary btn-sm response-edit-btn" data-id="${response.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="${response.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            responseTableBody.appendChild(row);
        });
        
        // Add event listeners to action buttons
        document.querySelectorAll('.response-edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                openEditModal(id);
                  loadFeedbacksForDropdown();
    loadUsersForDropdown();
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
        document.getElementById('modal-title').textContent = 'Add New Response';
        document.getElementById('response-id').value = '';
        responseForm.reset();
        responseModal.style.display = 'block';
    }
    
    async function openEditModal(id) {
        try {
            const response = await getResponseById(id);
             console.log(response)
            if (!response) {
                showError('Response not found');
                return;
            }
            
            document.getElementById('modal-title').textContent = 'Edit Response';
            document.getElementById('response-id').value = response.id;
            document.getElementById('response-feedback-id').value = response.feedback_id;
            document.getElementById('response-user-id').value = response.user_id;
            document.getElementById('response-type').value = response.response_type;
            document.getElementById('response-content').value = response.content;
            document.getElementById('is-public').checked = response.is_public;
            
            responseModal.style.display = 'block';
        } catch (error) {
            console.error('Error opening edit modal:', error);
        }
    }
    
    function openConfirmModal(id) {
        responseToDelete = id;
        confirmModal.style.display = 'block';
    }
    
    function closeModal() {
        responseModal.style.display = 'none';
    }
    
    function closeConfirmModal() {
        responseToDelete = null;
        confirmModal.style.display = 'none';
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = document.getElementById('response-id').value;
        const feedbackId = document.getElementById('response-feedback-id').value;
        const userId = document.getElementById('response-user-id').value;
        const responseType = document.getElementById('response-type').value;
        const content = document.getElementById('response-content').value;
        const isPublic = document.getElementById('is-public').checked ? 1 : 0;
        
        // Validate required fields
        if (!feedbackId || !userId || !responseType || !content) {
            showError('Please fill all required fields');
            return;
        }
        
        const responseData = {
            feedback_id: parseInt(feedbackId),
            user_id: parseInt(userId),
            response_type: responseType,
            content: content,
            is_public: isPublic
        };
        
        console.log('Submitting:', responseData); // Debug log
        
        try {
            if (id) {
                // Update existing response
                await updateResponse(parseInt(id), responseData);
                showSuccess('Response updated successfully');
            } else {
                // Create new response
                await createResponse(responseData);
                showSuccess('Response created successfully');
            }
            
            closeModal();
            loadResponses();
        } catch (error) {
            console.error('Error saving response:', error);
        }
    }
    
    async function confirmDelete() {
        if (!responseToDelete) return;
        
        try {
            await deleteResponse(responseToDelete);
            showSuccess('Response deleted successfully');
            closeConfirmModal();
            loadResponses();
        } catch (error) {
            console.error('Error deleting response:', error);
        }
    }
    
    function handleSearch() {
        currentSearch = responseSearch.value.trim();
        currentType = typeFilterSelect.value;
        currentPublic = publicFilterSelect.value;
        currentFeedbackId = feedbackIdSelect.value;
        currentPage = 1;
        loadResponses();
    }
    
    function goToPrevPage() {
        if (currentPage > 1) {
            currentPage--;
            loadResponses();
        }
    }
    
    function goToNextPage() {
        currentPage++;
        loadResponses();
    }
    
    function showSuccess(message) {
        // In a real app, you might use a toast notification library
        alert(message);
    }
    
    function showError(message) {
        // In a real app, you might use a toast notification library
        alert(`Error: ${message}`);
    }
    // In response.js
async function loadFeedbacksForDropdown() {
    try {
        const response = await fetch('/GreenStart-Connect-main/GreenStartConnect/Controller/feedbacks.php?limit=1000');
        if (!response.ok) throw new Error('Failed to load feedbacks');
        const { data } = await response.json();
        
        const select = document.getElementById('response-feedback-id');
        select.innerHTML = '<option value="">Select a feedback</option>';
        
        data.forEach(feedback => {
            const option = document.createElement('option');
            option.value = feedback.id;
            option.textContent = `#${feedback.id} - ${feedback.content.substring(0, 50)}...`;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading feedbacks:', error);
        showError('Failed to load feedbacks');
    }
}

async function loadUsersForDropdown() {
    try {
        const response = await fetch('/GreenStart-Connect-main/GreenStartConnect/Controller/users.php');
        if (!response.ok) throw new Error('Failed to load users');
        const users = await response.json();
        
        const select = document.getElementById('response-user-id');
        select.innerHTML = '<option value="">Select a user</option>';
        
        users.forEach(user => {
            const option = document.createElement('option');
            option.value = user.id;
            option.textContent = `${user.nom} (${user.email || user.adresse})`;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading users:', error);
        showError('Failed to load users');
    }
}

function openResponseModal() {
    loadFeedbacksForDropdown();
    loadUsersForDropdown();
    document.getElementById('response-modal').style.display = 'block';
}

// Add event listener for your "Add Response" button
document.getElementById('add-response-btn').addEventListener('click', openResponseModal);
});