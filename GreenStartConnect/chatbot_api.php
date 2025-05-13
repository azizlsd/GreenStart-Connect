<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$question = strtolower(trim($data['question'] ?? ''));

if (!$question) {
    echo json_encode(['answer' => "Please ask a question."]);
    exit;
}

// SECURITY: Only allow questions about blog content/features
$allowedTopics = [ 'register', 'login', 'about', 'contact', 'category', 'feature', 'startup','freelance' , 'accounts'];




$apiKey = 'F04X9wzcOZZok1cJ4q55y4zVkFnfacCcfAt3qHDo'; 
$endpoint = 'https://api.cohere.ai/v1/chat';

$payload = [
    'message' => $question,
    'chat_history' => [],
    'temperature' => 0.3,
    'preamble' => "You are a helpful assistant for a startup ecommerce website dedicated to startups and entrepreneurship. Only answer questions related to startups and bussines and math. 
You can also talk about topics covered in the blog such as startup tips, founder stories, product launches, funding, and business growth.
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
