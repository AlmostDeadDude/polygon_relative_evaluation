<?php
require_once('header.php');

echo '<div id="vcodeContainer">';
echo '<h2>Thanks for checking out the polygon evaluation demo!</h2>';
echo '<p>Your rankings are not stored anywhere &mdash; refresh or load another job to keep exploring different samples.</p><br>';
echo '<p><strong>Original workflow recap:</strong></p>';
echo '<ul style="text-align: left;">
<li>Workers completed a short qualification step followed by 5 evaluation tasks plus a hidden control polygon.</li>
<li>Each submission was logged together with anonymized campaign metadata and later aggregated for research.</li>
<li>Upon success, the backend generated a proof code so contributors could claim their reward.</li>
</ul>';
echo '<p>The portfolio version keeps only the user interface. Storage, payouts, and moderation were intentionally removed to make the demo safe to share publicly.</p>';
echo '</div>
<button id="copyVcodeBtn">Try another job</button>';

require_once('footer.php');
