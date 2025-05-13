(function ($) {
    "use strict";

    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();

    new WOW().init();

    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.navbar').addClass('sticky-top shadow-sm');
        } else {
            $('.navbar').removeClass('sticky-top shadow-sm');
        }
    });

    const $dropdown = $(".dropdown");
    const $dropdownToggle = $(".dropdown-toggle");
    const $dropdownMenu = $(".dropdown-menu");
    const showClass = "show";

    $(window).on("load resize", function () {
        if (this.matchMedia("(min-width: 992px)").matches) {
            $dropdown.hover(
                function () {
                    const $this = $(this);
                    $this.addClass(showClass);
                    $this.find($dropdownToggle).attr("aria-expanded", "true");
                    $this.find($dropdownMenu).addClass(showClass);
                },
                function () {
                    const $this = $(this);
                    $this.removeClass(showClass);
                    $this.find($dropdownToggle).attr("aria-expanded", "false");
                    $this.find($dropdownMenu).removeClass(showClass);
                }
            );
        } else {
            $dropdown.off("mouseenter mouseleave");
        }
    });

    $('[data-toggle="counter-up"]').counterUp({
        delay: 10,
        time: 2000
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });

    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        dots: true,
        loop: true,
        center: true,
        responsive: {
            0: { items: 1 },
            576: { items: 1 },
            768: { items: 2 },
            992: { items: 3 }
        }
    });

    $('.vendor-carousel').owlCarousel({
        loop: true,
        margin: 45,
        dots: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0: { items: 2 },
            576: { items: 4 },
            768: { items: 6 },
            992: { items: 8 }
        }
    });

    async function loadFeedbacks(page = 1, limit = 6, search = '') {
        try {
            const params = new URLSearchParams({
                page: page,
                limit: limit,
                search: search
            });

            const response = await fetch(`/GreenStart-Connect-main/GreenStartConnect/Controller/feedbacks.php?${params}`);
            const result = await response.json();

            const feedbackSection = document.getElementById('feedback-section');
            feedbackSection.innerHTML = '';

            result.data.forEach(feedback => {
                const card = document.createElement('div');
                card.className = 'col-md-4 mb-4';
                card.innerHTML = `
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">${feedback.type}</h5>
                            <p class="card-text">${feedback.content}</p>
                            <small class="text-muted">By ${feedback.user_name || 'Anonymous'}, ${feedback.user_address || 'Unknown'}<br>On ${feedback.created_at}</small>
                        </div>
                    </div>
                `;
                feedbackSection.appendChild(card);
            });

        } catch (error) {
            console.error('Failed to load feedbacks:', error);
        }
    }

    document.getElementById('feedback-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const feedbackType = formData.get('type');
    const feedbackContent = formData.get('content');
    
    // Validate fields
    if (!feedbackType || !feedbackContent.trim()) {
        alert("Please fill all the fields");
        return;
    }

    try {
        // Check for bad words using API
        const apiKey = 'xx1hqpszd2A3D4Lub8swrA==HgVYs5bii1Jz9NkV'; // <-- Replace with your real API Key
        const apiUrl = `https://api.api-ninjas.com/v1/profanityfilter?text=${encodeURIComponent(feedbackContent)}`;

        const badWordsResponse = await fetch(apiUrl, {
            method: 'GET',
            headers: { 'X-Api-Key': apiKey }
        });

        if (!badWordsResponse.ok) {
            console.error("Error:", badWordsResponse.status, await badWordsResponse.text());
            alert("Error checking feedback. Please try again.");
            return;
        }

        const badWordsResult = await badWordsResponse.json();

        if (badWordsResult && badWordsResult.has_profanity) {
            alert("Please remove inappropriate language from your feedback.");
            return;
        }


    const feedback = {
        user_id: formData.get('user_id'),
        type: feedbackType,
        content: feedbackContent
    };

    
        const response = await fetch('/GreenStart-Connect-main/GreenStartConnect/Controller/post_feedback.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(feedback)
        });

        if (response.status === 401) {
           
            return;
        }

        const result = await response.json();
        $('#successModal').modal('show');
        loadFeedbacks();
        this.reset();

    } catch (error) {
        console.error('Failed to submit feedback:', error);
    }
});




    document.addEventListener('DOMContentLoaded', () => {
        
                document.getElementById('form-feedback-card').style.display = 'inline';
        loadFeedbacks();
        
       
    });

})(jQuery);
