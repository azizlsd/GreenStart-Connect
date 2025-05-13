<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<div id="chat-container"
    style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; max-width: 350px; font-family: Arial, sans-serif;">
    <button id="chat-toggle"
        style="background-color: #4CAF50; color: white; border: none; padding: 10px 15px; border-radius: 50px; cursor: pointer;">
        💬 Chat
    </button>

    <div id="chat-box"
        style="display: none; background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 10px;">
        <div id="chat-messages" style="height: 250px; overflow-y: auto; margin-bottom: 10px; font-size: 14px;"></div>
        <input type="text" id="chat-input" placeholder="Type your message..."
            style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
</div>

<script>
    const toggle = document.getElementById('chat-toggle');
    const box = document.getElementById('chat-box');
    const input = document.getElementById('chat-input');
    const messages = document.getElementById('chat-messages');

    toggle.addEventListener('click', () => {
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
    });

    input.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            const msg = input.value.trim();
            if (!msg) return;
            appendMessage('You', msg);
            input.value = '';

            fetch("/GreenStart-Connect-main/GreenStartConnect/chatbot_api.php"
                , {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ question: msg })
                })
             
                .then(res => res.json())
                .then(data => appendMessage('AI', data.answer))
                
                .catch(() => appendMessage('AI', '⚠️ Failed to reach assistant.'));
        }
    });

    function appendMessage(sender, text) {
        const div = document.createElement('div');
        div.innerHTML = `<strong>${sender}:</strong> ${text}`;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }
</script>