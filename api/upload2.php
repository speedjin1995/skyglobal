<?php

$rawData = file_get_contents('php://input');
$requestData = json_decode($rawData, true);

// Check if the POST request contains base64-encoded images
if ($requestData['images'] != null) {
    $base64Images = $requestData['images'];

    // Specify the directory where you want to save the uploaded images
    $uploadDir = '../purchases/';

    // Create the uploads directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $uploadedFilenames = array(); // To store the filenames of uploaded images

    // Loop through the base64 images and save them to the server
    foreach ($base64Images as $key => $base64Image) {
        // Generate a unique filename for each image (e.g., using a timestamp)
        $filename2 = uniqid() . '.jpg';
        $filename = $uploadDir . $filename2;

        // Decode the base64 data and save it as a file
        $decodedImage = base64_decode($base64Image);
        file_put_contents($filename, $decodedImage);

        // Add the filename to the array
        $uploadedFilenames[] = $filename2;
    }

    // Respond with a success message and the uploaded filenames
    echo json_encode(['message' => 'Images uploaded successfully', 'filenames' => $uploadedFilenames]);
} 
else {
    
    echo json_encode(['error' => 'No images provided']);
}
?>