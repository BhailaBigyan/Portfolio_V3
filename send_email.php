<?php
// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    header('Allow: POST');
    die("Error: Only POST requests are allowed.");
}

// Honeypot check
if (!empty($_POST["_honey"])) {
    http_response_code(403);
    die("No bots allowed!");
}

// Verify reCAPTCHA
$recaptcha_secret = "6Leg4F4rAAAAAIlTEjUwaeYl_gAIrr5GyYWJYTIB"; // Replace with your key
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

$recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
$recaptcha_data = [
    'secret' => $recaptcha_secret,
    'response' => $recaptcha_response,
    'remoteip' => $_SERVER['REMOTE_ADDR']
];

$recaptcha_options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($recaptcha_data)
    ]
];

$recaptcha_context = stream_context_create($recaptcha_options);
$recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
$recaptcha_json = json_decode($recaptcha_result);

if (!$recaptcha_json->success) {
    http_response_code(400);
    die("reCAPTCHA verification failed.");
}

// Sanitize inputs
$name = strip_tags(trim($_POST["name"] ?? ''));
$email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
$message = trim($_POST["message"] ?? '');

// Validate
if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die("Please fill all fields correctly.");
}

// Send email
$to = "bigyanbhaila98@gmail.com";
$subject = "New message from $name";
$headers = "From: $name <$email>\r\nReply-To: $email";

if (mail($to, $subject, $message, $headers)) {
    http_response_code(200);
    echo "Thank you! Your message has been sent.";
} else {
    http_response_code(500);
    echo "Error: Message could not be sent.";
}
?>