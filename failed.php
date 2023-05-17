<?php
// Redirect to the same page without the query parameters to prevent further file deletions - weak stuff but good enough for testing campaigns
if (isset($_GET['file'])) {
    $filename = $_GET['file'];
    //if such field exists - delete the file
    unlink('results/job_' . $filename . '.txt'); //delete result file for job assignment to be faster
    unlink('user_info/job_' . $filename . '.txt'); //delete user_info *not necessary but why not

    header('Location: failed.php');
    exit();
}

require_once('header.php');
?>

<div id="failContainer">
    <h1>😕</h1>
    <h1>We're sorry, but you did not pass the hidden control task and thus are not eligible for the VCODE at this time.</h1>
</div>

<?php
require_once('footer.php');
?>