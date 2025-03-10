<?php
  $user="postgres.rbborpwwkrfhkcqvacyz";
  $password="tint2025";
  $host="aws-0-ap-southeast-1.pooler.supabase.com";
  $port="6543";
  $dbname="postgres";
  

  try {
      $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
      $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
  }
?>