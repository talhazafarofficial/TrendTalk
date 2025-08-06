<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include('../includes/header.php');
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/CSS/style.css">


<div class="container" style="max-width:800px; margin:40px auto;">
  <h2>About Trendtalk</h2>
  <p>Welcome to Trendtalk – a modern platform where voices meet, opinions matter, and trends ignite conversation.</p>
  <p>At Trendtalk, we believe in the power of dialogue. Our platform is built to provide people with a safe, open, and respectful environment to discuss trending topics from around the world. Whether it's current affairs, technology, culture, entertainment, or social issues – you’ll find a space to express your point of view (POV) and connect with others who care.</p>
  <h3>What You Can Do on Trendtalk:</h3>
  <ul>
    <li><strong>Explore Hot Topics:</strong> Stay updated with the latest trending discussions.</li>
    <li><strong>Share Your POV:</strong> Post your opinions and ideas on topics that matter to you.</li>
    <li><strong>Join the Conversation:</strong> Reply to others, engage in healthy debates, and broaden your perspective.</li>
    <li><strong>Personal Dashboard:</strong> Manage your profile, view your comments, and track your activity.</li>
    <li><strong>Help Center:</strong> New here? Our Help Center guides you on how to get started and stay safe while engaging.</li>
  </ul>
  <h3>Why Trendtalk?</h3>
  <p>Unlike noisy social media feeds, Trendtalk is focused. We’ve designed this platform to be clean, simple, and meaningful – so your voice doesn’t get lost. It's not just about speaking up; it's about being heard.</p>
  <p>Whether you're a casual reader or an active debater, Trendtalk is your space to talk trends that matter.</p>
</div>

<?php include('../includes/footer.php'); ?>
