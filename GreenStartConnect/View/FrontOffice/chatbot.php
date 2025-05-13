<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$question = strtolower(trim($data['question'] ?? ''));

if (!$question) {
    echo json_encode(['answer' => "Please ask a question."]);
    exit;
}

// SECURITY: Only allow questions about blog content/features
$allowedTopics = ['post', 'comment', 'search', 'register', 'login', 'about', 'contact', 'category', 'feature', 'blog'];

function isRelevant($question, $topics) {
    foreach ($topics as $topic) {
        if (strpos($question, $topic) !== false) return true;
    }
    return false;
}

if (!isRelevant($question, $allowedTopics)) {
    echo json_encode([
        'answer' => "I'm only able to answer questions about this blog’s content, features, or structure. Please ask something relevant."
    ]);
    exit;
}

// --- COHERE API CALL ---
$apiKey = 'F04X9wzcOZZok1cJ4q55y4zVkFnfacCcfAt3qHDo'; // Replace with your key
$endpoint = 'https://api.cohere.ai/v1/chat';

$payload = [
    'message' => $question,
    'chat_history' => [],
    'temperature' => 0.3,
    'preamble' => "You are a helpful assistant for a blog dedicated to startups and entrepreneurship. Only answer questions related to this blog’s content, structure, or features. 
You can talk about topics covered in the blog such as startup tips, founder stories, product launches, funding, and business growth.
Do not answer questions unrelated to startups or the website.",
];

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$cohereResponse = json_decode($response, true);

$reply = $cohereResponse['text'] ?? "Sorry, I couldn’t generate a proper response.";

echo json_encode(['answer' => $reply]);
