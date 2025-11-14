<?php
require_once('header.php');
?>

<div id="failContainer">
    <h1>Demo mode active</h1>
    <h1>The portfolio build no longer enforces hidden control tasks or proof code distribution.</h1>
    <p>In production, this page appeared when a worker failed the hidden control polygon that was mixed into every batch. It prevented low-quality answers from reaching the final dataset and automatically released the job back to the queue.</p>
    <p>For the public demo we keep this page purely for documentation. Feel free to head back to the main task and load another random job.</p>
    <button class="toTaskBtn" onclick="window.location.href='index.php';">Return to demo</button>
</div>

<?php
require_once('footer.php');
?>
