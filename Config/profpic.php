<?php
session_start();
$userEmail = $_SESSION['id'];
$supabaseUrl = "https://rbborpwwkrfhkcqvacyz.supabase.co";
$supabaseAnonKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJiYm9ycHd3a3JmaGtjcXZhY3l6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDAwMzU2OTQsImV4cCI6MjA1NTYxMTY5NH0.pMLuryar6iAlkd110WblQtz8T_XdrKOpZEQHksHpuuM"; // Replace with your Supabase Anon Key
$bucketName = "profile-pictures";
$baseStorageUrl = "$supabaseUrl/storage/v1/object/public/$bucketName/";
function getProfilePictureUrl($email)
{
  global $baseStorageUrl;
  return $baseStorageUrl . $email . ".png";
}
function checkProfilePictureExists($email)
{
  global $supabaseUrl, $bucketName, $supabaseAnonKey;

  $filePath = $email . ".png";
  $headers = [
    "apikey: $supabaseAnonKey",
    "Authorization: Bearer $supabaseAnonKey",
    "Content-Type: application/json"
  ];
  $ch = curl_init("$supabaseUrl/storage/v1/object/public/$bucketName/$filePath");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_NOBODY, true);

  curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  return $httpCode === 200;
}
if ($userEmail) {
  $profilePicUrl = getProfilePictureUrl($userEmail);
  if (checkProfilePictureExists($userEmail)) {
    $_SESSION['profile_picture'] = trim($profilePicUrl);
  } else {
    $_SESSION['profile_picture'] = "picture/profile.png";
  }
} else {
  $_SESSION['profile_picture'] = "picture/profile.png";
}
