<?php

$_SESSION['user_email'] = "Charlotte.Harrison@taylors.edu.my";
// Supabase Credentials
$supabaseUrl = "https://rbborpwwkrfhkcqvacyz.supabase.co"; // Replace with your Supabase URL
$supabaseAnonKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJiYm9ycHd3a3JmaGtjcXZhY3l6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDAwMzU2OTQsImV4cCI6MjA1NTYxMTY5NH0.pMLuryar6iAlkd110WblQtz8T_XdrKOpZEQHksHpuuM"; // Replace with your Supabase Anon Key
$bucketName = "profile-pictures"; // Your Supabase Storage bucket name

// Construct the base URL for accessing public storage files
$baseStorageUrl = "$supabaseUrl/storage/v1/object/public/$bucketName/";

// Function to get a user's profile picture URL
function getProfilePictureUrl($email) {
    global $baseStorageUrl;
    return $baseStorageUrl . $email . ".png"; // Profile picture path
}

// Function to check if profile picture exists
function checkProfilePictureExists($email) {
    global $supabaseUrl, $bucketName, $supabaseAnonKey;

    $filePath = $email . ".png"; // Expected file path in storage
    $headers = [
        "apikey: $supabaseAnonKey",
        "Authorization: Bearer $supabaseAnonKey",
        "Content-Type: application/json"
    ];

    // Make a HEAD request to check if the file exists
    $ch = curl_init("$supabaseUrl/storage/v1/object/public/$bucketName/$filePath");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_NOBODY, true); // Only check if file exists

    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode === 200; // Returns true if file exists, false otherwise
}

// Get logged-in user's email from session
$userEmail = $_SESSION['user_email'] ?? null;

// Determine profile picture URL
// Determine profile picture URL
if ($userEmail) {
  $profilePicUrl = getProfilePictureUrl($userEmail);

  // Check if the profile picture exists
  if (checkProfilePictureExists($userEmail)) {
      $_SESSION['profile_picture'] = trim($profilePicUrl);
  } else {
      $_SESSION['profile_picture'] = "picture/profile.png";
  }
} else {
  $_SESSION['profile_picture'] = "picture/profile.png";
}
?>
