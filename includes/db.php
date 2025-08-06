<?php
$conn = new mysqli(
  'localhost',
  'root',
  '',
  'forum'
);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>