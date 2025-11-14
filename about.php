<?php
$firstTime = $_GET['firstTime'] ?? 'true';
$Campaign_id = $_GET["campaign"] ?? "DEMO_CAMPAIGN";
$Worker_id = $_GET["worker"] ?? "GUEST";
$Rand_key = $_GET["rand_key"] ?? uniqid("demo_", true);

require_once('header.php');
echo '
<div style="max-width: 1200px; text-align: justify;">
<h2>Introduction</h2>
<p>Welcome to the polygon evaluation demo. You will be presented with a series of 3 images containing selections around trees, and your job is to evaluate how well each selection matches the actual outline. Every visit loads a random prepared job and nothing you do on this page is stored or sent anywhere &mdash; the goal here is simply to showcase the user experience.</p><br>
<h2>How the Production System Worked</h2>
<p>This project originally powered paid crowdsourcing campaigns that compared different tree extractions. Each contributor received a randomized batch of tasks, a hidden control polygon, and a qualification gate that verified understanding of the basics before payouts were unlocked.</p>
<ul>
  <li><strong>Task distribution:</strong> workers were assigned the next available job and their rankings were persisted for later aggregation.</li>
  <li><strong>Quality gates:</strong> a short qualification test plus one hidden control ensured that careless submissions could be filtered out.</li>
  <li><strong>Payouts:</strong> after finishing the batch, contributors received a unique verification code to redeem inside the crowdsourcing platform.</li>
</ul>
<p>The portfolio version keeps the visual and interaction layer intact while disabling storage, payouts, and moderation logic. You can refresh the page at any time to see different random jobs drawn from the archived dataset.</p>
<h2>Task Instructions</h2>
<div id="placementP"><p style="min-width: 315px;">To complete the task, evaluate the selections against each other and place them in the corresponding position in the list using arrows on the left side. The selections will be colored blue, black and orange to make them easier to distinguish.</p>
<img src="pics/examples/placement.png" alt="placement" style="margin-left: 20px;">
</div>
<p>After that, submit your results and, once all tasks are complete, you will see a short thank-you message with an option to load another random job. No personal data or submissions are persisted.</p>
<p>It\'s important to evaluate the selection based on how well it matches the tree outline, and not based on any other factors. To help you with this, we have provided some examples, which you can refer to as a guide when evaluating the pictures. Keep in mind that real tasks may contain any possible combination of selections, such as 3 bad selections, or 3 near-perfect selections, etc., so it may be difficult to decide on the correct placement. Take your time and look at the details to make the optimal decision.</p><br>
<h2>Examples</h2>
<div class="img-wrapper">
  <div class="img-subwrapper">
    <span>Here you can clearly see that the black selection is very precise and detailed. The orange selection is much less detailed, but follows the tree outline reasonably well. The blue selection in this case is very bad.</span>
    <img src="pics/examples/ex1.png" alt="ex1">
  </div>
  <div class="img-subwrapper">
    <span>In this case, the blue selection seems to be the best. The black one is not too bad, but it covers some areas that do not belong to the tree and also lacks some small details. The orange selection makes some sense, but is clearly the worst of the three.</span>
    <img src="pics/examples/ex2.png" alt="ex2">
  </div>
</div>
<h2>Conclusion</h2>
<p style="margin-bottom: 50px;">Thank you for trying the demo! The original research version of this project helped improve automatic tree selection algorithms for applications in agriculture, forestry, and urban planning. This portfolio build keeps the same interaction flow so you can experience it without the production backend.</p>
</div>
';
if ($firstTime == 'true') {
  echo '<button class="toTaskBtn" onclick="startTask()">Start task</button>';
  echo '<script>
    function startTask() {
        window.location.href = "index.php";
    }
    </script>';
}
require_once('footer.php');
