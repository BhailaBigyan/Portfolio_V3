<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify reCAPTCHA
    $recaptcha_secret = "6Leg4F4rAAAAAIlTEjUwaeYl_gAIrr5GyYWJYTIB";
    $recaptcha_response = $_POST['g-recaptcha-response'];
    
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
        die("reCAPTCHA verification failed. Are you a robot?");
    }
    
    // Proceed with email sending if reCAPTCHA passes
    // ... (rest of your PHP code)
}

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check honeypot field
    if (!empty($_POST["_honey"])) {
        http_response_code(403);
        die("No bots allowed!");
    }

    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);
    
    // Validate inputs
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        die("Please fill all fields correctly.");
    }

    // Set recipient (your Gmail)
    $to = "bigyanbhaila98@gmail.com";
    
    // Set subject
    $subject = "New message from $name (Portfolio Contact)";
    
    // Build email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";
    
    // Build headers
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    if (mail($to, $subject, $email_content, $headers)) {
        http_response_code(200);
        echo "Thank you! Your message has been sent.";
    } else {
        http_response_code(500);
        echo "Oops! Something went wrong. Please try again later.";
    }

} else {
    http_response_code(403);
    echo "There was a problem with your submission. Please try again.";
}
?>