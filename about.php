<?php
$firstTime = $_GET['firstTime'];
$Campaign_id = $_GET["campaign"];
$Worker_id = $_GET["worker"];
$Rand_key = $_GET["rand_key"];

@require_once('header.php');
echo '
<div style="max-width: 1200px; text-align: justify;">
<h2>Introduction</h2>
<p>Welcome to our tree contour evaluation task. In this task, you will be presented with a series of 3 images containing a selection of trees, and your job is to evaluate how well the selection polygon matches the actual tree outline. To do this, you will be asked to order the selections from best to worst. Your ratings will help us to improve the accuracy of our automatic tree selection algorithms in the future.</p><br>
<h2>Task Instructions</h2>
<div id="placementP"><p style="min-width: 315px;">To complete the task, evaluate the selections against each other and place them in the corresponding position in the list using arrows on the left side. The selections will be colored blue, black and orange to make them easier to distinguish.</p>
<img src="pics/examples/placement.png" alt="placement" style="margin-left: 20px;">
</div>
<p>After that, submit your results and after completing all the tasks you will receive a unique VCODE to claim your payment. </p>
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
<p style="margin-bottom: 50px;">Thank you for helping with our research! Your contributions will help us to improve our automatic tree selection algorithms, which will have a wide range of applications in fields such as agriculture, forestry, and urban planning. We appreciate your time and effort, and wish you good luck with the task.</p>
</div>
';
if ($firstTime == 'true') {
  echo '<button class="toTaskBtn" onclick="startTask()">Start task</button>';
  echo '<script>
    function startTask() {
        window.location.href = "index.php?campaign=' . $Campaign_id . '&worker=' . $Worker_id . '&rand_key=' . $Rand_key . '";
    }
    </script>';
}
@require_once('footer.php');
