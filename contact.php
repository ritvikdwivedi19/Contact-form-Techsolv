<?php
// Validate form fields
if (isset($_POST['full_name'], $_POST['phone_number'], $_POST['email'], $_POST['subject'], $_POST['message']))
{
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    if (empty($full_name) || empty($phone_number) || empty($email) || empty($subject) || empty($message)) {
        die("All fields are mandatory.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }
// Database connection
    $servername = "localhost";
    $username = "mysql";
    $password = "123456";
    $dbname = "mysql";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
// Insert data into database
    $stmt = $conn->prepare("INSERT INTO contact_form (full_name, phone_number, email, subject, message, ip_address, timestamp) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssss", $full_name, $phone_number, $email, $subject, $message, $_SERVER['REMOTE_ADDR']);
    $stmt->execute();
    $stmt->close();
// Send email notification
    $to = "test@techsolvitservice.com";
    $subject = "New Contact Form Submission";
    $message = "Name: $full_name\nPhone: $phone_number\nEmail: $email\nSubject: $subject\nMessage: $message";
    $headers = "From: $email";
    mail($to, $subject, $message, $headers);
    $conn->close();
    echo "Form submitted successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
</head>
<body>
    <h1>Contact Us</h1>
    <form action="examole.php" method="post">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required><br><br>
        <label for="phone_number">Phone Number:</label>
        <input type="tel" id="phone_number" name="phone_number" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" required><br><br>
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" required></textarea><br><br> 
        <input type="submit" value="Submit">
    </form>
</body>
</html>