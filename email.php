<?php
header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // print("api call");

    $subject = "VOUP - Registration Submissions";
$body = $_POST['name'];
$userEmail = $_POST['email'];
$dob = $_POST['dob'];

// $email = 'vineetkaimau@gmail.com';
$email="goldeneye.voice1981@gmail.com"; //where you want to send email

    if (isset($_FILES['payment_photo'])) {
        $file = $_FILES['payment_photo'];
        // Check for errors
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Specify the directory where you want to save the file
            $uploadDirectory = "uploads/";

            // Generate a unique filename (you may want to improve this logic)
            $filename = uniqid() . '_' . $file['name'];

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($file['tmp_name'], $uploadDirectory . $filename)) {
                $imagePath="https://thegoldeneye.org/uploads/" . $filename;
              
                   $emailBody = array(
        'subject' => $subject,
        'sender' => array(
            'name' => 'Golden Eye',
            'email' => 'no-reply@crux.centre'
        ),
        'to' => array(
            array('email' => $email)
        ),
        'bcc'=>array(array('email'=>'satyam.mse@gmail.com')),
        // 'textContent'=>"<h1> Registration Form submitted<br><br><img src='$imagePath'>"
        'textContent' => "<h1>New Registration Submitted</h1><br><br>Name: $body\nEmail: $userEmail\nDate of Birth: $dob\nGender: $sex\nAddress: $address\nState: $state\nPincode: $pincode\nMobile: $mobile\nAlternate: $alternate\nSinging Experience: $singing_experience\n<h1>Payment Image:</h1> <br><br><img src='$imagePath'  width='500'>",
    );

    // create an HTTP request to send email
    $url = 'https://api.brevo.com/v3/smtp/email';
    $headers = array(
        'Content-Type: application/json',
        'api-key: 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'
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailBody));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);

    if ($response === false) {
        $error = array(
            "status" => 0,
            "message" => "Email error",
            "error" => curl_error($ch),
            "body" => $emailBody
        );
        error_log(json_encode($error));
        echo json_encode($error);
    } else {
           echo '<script>alert("response submitted successfully. we will contact you soon.")</script>';
        $responseData = array("status" => 1, "message" => "Email sent successfully", "data" => $response);
        echo json_encode($responseData);
    }

    curl_close($ch);
                
                // echo json_encode(['status' => 'success', 'message' => 'File uploaded successfully',"path"=>"https://thegoldeneye.org/uploads/" . $filename,"check"=>$input]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error saving the file']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'File upload error']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
    }
} else {
    // Handle non-POST requests if needed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
echo '<script>javascript:history.go(-1)</script>'
?>
