<?php 

// First, we check if the form was submitted using the POST method.
// This prevents users from accessing the script directly.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    echo "hello";
    // --- 1. SET YOUR EMAIL ADDRESS ---
    // This is the email address where you want to receive the form submissions.
    $recipient_email = "ankitdey153@gmail.com"; // <-- IMPORTANT: CHANGE THIS!

    // --- 2. RETRIEVE AND SANITIZE FORM DATA ---
    // We use htmlspecialchars() and trim() to clean the data and prevent security issues.
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"]));

    // --- 3. VALIDATE THE INPUTS ---
    // Check if the required fields are empty or if the email format is invalid.
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // If validation fails, send an error response and stop the script.
        http_response_code(400); // Bad Request
        echo "Please fill out all fields and provide a valid email address.";
        exit;
    }

    // --- 4. CONSTRUCT THE EMAIL ---
    // Subject line for the email you will receive.
    $subject = "New Contact Form Submission from " . $name;

    // The body of the email. We'll format it nicely with HTML.
    $email_body = "<html><body style='font-family: Arial, sans-serif;'>";
    $email_body .= "<h2 style='color: #333;'>New Message from Your Website</h2>";
    $email_body .= "<p><strong>From:</strong> " . $name . "</p>";
    $email_body .= "<p><strong>Email:</strong> " . $email . "</p>";
    $email_body .= "<h3 style='color: #333;'>Message:</h3>";
    // Using nl2br() to preserve line breaks from the textarea.
    $email_body .= "<div style='border: 1px solid #ddd; padding: 15px; border-radius: 5px; background-color: #f9f9f9;'>" . nl2br($message) . "</div>";
    $email_body .= "</body></html>";

    // --- 5. SET THE EMAIL HEADERS ---
    // Headers are essential for the email to be delivered and displayed correctly.
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // The 'From' header. This is what you will see as the sender.
    $headers .= 'From: <noreply@yourdomain.com>' . "\r\n";
    // The 'Reply-To' header is set to the user's email. This is crucial!
    // It allows you to hit "Reply" in your email client to respond to the user directly.
    $headers .= 'Reply-To: ' . $email . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();

    // --- 6. SEND THE EMAIL ---
    // The mail() function attempts to send the email.
    // It returns 'true' if successful and 'false' if it fails.
    if (mail($recipient_email, $subject, $email_body, $headers)) {
        // Success: Send a positive response to the user.
        http_response_code(200); // OK
        echo "Thank you! Your message has been sent successfully.";
    } else {
        // Failure: Send a server error response.
        http_response_code(500); // Internal Server Error
        echo "Oops! Something went wrong and we couldn't send your message.";
    }

} else {
    // If the script is accessed without a POST request, show an error.
    http_response_code(403); // Forbidden
    echo "There was a problem with your submission. Please try again.";
}


?> 