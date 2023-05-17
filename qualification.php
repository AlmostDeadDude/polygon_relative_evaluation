<?php
$firstTime = $_GET['firstTime'];
$Campaign_id = $_GET["campaign"];
$Worker_id = $_GET["worker"];
$Rand_key = $_GET["rand_key"];

require_once('header.php');

//data is set for the qualification test
$data = [json_decode('[{"ID":"A","number":6,"number_points":7,"points":[{"number":40,"x":563,"y":529.8515625},{"number":41,"x":477,"y":566.8515625},{"number":42,"x":354,"y":496.8515625},{"number":43,"x":347,"y":362.8515625},{"number":44,"x":447,"y":289.8515625},{"number":45,"x":584,"y":356.8515625},{"number":46,"x":593,"y":362.8515625}],"max_x":593,"min_x":347,"max_y":566.8515625,"min_y":289.8515625}]', true), json_decode('[{"ID":"B","number":12,"number_points":42,"points":[{"number":226,"x":308,"y":240.859375},{"number":227,"x":346,"y":212.859375},{"number":228,"x":387,"y":240.859375},{"number":229,"x":419,"y":258.859375},{"number":230,"x":457,"y":258.859375},{"number":231,"x":484,"y":288.859375},{"number":232,"x":496,"y":314.859375},{"number":233,"x":532,"y":340.859375},{"number":234,"x":540,"y":371.859375},{"number":235,"x":557,"y":405.859375},{"number":236,"x":578,"y":426.859375},{"number":237,"x":602,"y":456.859375},{"number":238,"x":601,"y":469.859375},{"number":239,"x":579,"y":478.859375},{"number":240,"x":612,"y":527.859375},{"number":241,"x":593,"y":543.859375},{"number":242,"x":550,"y":545.859375},{"number":243,"x":527,"y":564.859375},{"number":244,"x":506,"y":583.859375},{"number":245,"x":480,"y":567.859375},{"number":246,"x":467,"y":526.859375},{"number":247,"x":464,"y":492.859375},{"number":248,"x":439,"y":502.859375},{"number":249,"x":425,"y":540.859375},{"number":250,"x":392,"y":559.859375},{"number":251,"x":364,"y":556.859375},{"number":252,"x":349,"y":540.859375},{"number":253,"x":328,"y":510.859375},{"number":254,"x":312,"y":539.859375},{"number":255,"x":302,"y":558.859375},{"number":256,"x":276,"y":530.859375},{"number":257,"x":247,"y":524.859375},{"number":258,"x":263,"y":493.859375},{"number":259,"x":261,"y":471.859375},{"number":260,"x":237,"y":462.859375},{"number":261,"x":224,"y":452.859375},{"number":262,"x":258,"y":425.859375},{"number":263,"x":274,"y":412.859375},{"number":264,"x":297,"y":389.859375},{"number":265,"x":321,"y":351.859375},{"number":266,"x":320,"y":292.859375},{"number":267,"x":284,"y":290.859375}],"max_x":612,"min_x":224,"max_y":583.859375,"min_y":212.859375}]', true), json_decode('[{"ID":"C","number":5,"number_points":79,"points":[{"number":235,"x":327,"y":225.859375},{"number":236,"x":374,"y":240.859375},{"number":237,"x":396,"y":260.859375},{"number":238,"x":444,"y":244.859375},{"number":239,"x":473,"y":259.859375},{"number":240,"x":481,"y":278.859375},{"number":241,"x":497,"y":313.859375},{"number":242,"x":524,"y":333.859375},{"number":243,"x":533,"y":356.859375},{"number":244,"x":532,"y":366.859375},{"number":245,"x":554,"y":404.859375},{"number":246,"x":573,"y":428.859375},{"number":247,"x":605,"y":445.859375},{"number":248,"x":609,"y":465.859375},{"number":249,"x":587,"y":479.859375},{"number":250,"x":586,"y":490.859375},{"number":251,"x":608,"y":507.859375},{"number":252,"x":614,"y":535.859375},{"number":253,"x":598,"y":548.859375},{"number":254,"x":572,"y":552.859375},{"number":255,"x":556,"y":556.859375},{"number":256,"x":535,"y":577.859375},{"number":257,"x":518,"y":586.859375},{"number":258,"x":492,"y":586.859375},{"number":259,"x":485,"y":573.859375},{"number":260,"x":472,"y":560.859375},{"number":261,"x":472,"y":541.859375},{"number":262,"x":471,"y":523.859375},{"number":263,"x":460,"y":515.859375},{"number":264,"x":445,"y":535.859375},{"number":265,"x":438,"y":543.859375},{"number":266,"x":423,"y":551.859375},{"number":267,"x":405,"y":561.859375},{"number":268,"x":397,"y":568.859375},{"number":269,"x":381,"y":565.859375},{"number":270,"x":357,"y":561.859375},{"number":271,"x":340,"y":542.859375},{"number":272,"x":333,"y":527.859375},{"number":273,"x":326,"y":546.859375},{"number":274,"x":323,"y":552.859375},{"number":275,"x":309,"y":562.859375},{"number":276,"x":284,"y":549.859375},{"number":277,"x":255,"y":542.859375},{"number":278,"x":242,"y":525.859375},{"number":279,"x":242,"y":503.859375},{"number":280,"x":254,"y":495.859375},{"number":281,"x":259,"y":489.859375},{"number":282,"x":259,"y":480.859375},{"number":283,"x":239,"y":470.859375},{"number":284,"x":217,"y":467.859375},{"number":285,"x":194,"y":465.859375},{"number":286,"x":177,"y":479.859375},{"number":287,"x":170,"y":477.859375},{"number":288,"x":168,"y":233.859375},{"number":289,"x":174,"y":226.859375},{"number":290,"x":198,"y":225.859375},{"number":291,"x":215,"y":261.859375},{"number":292,"x":234,"y":284.859375},{"number":293,"x":240,"y":323.859375},{"number":294,"x":232,"y":360.859375},{"number":295,"x":211,"y":391.859375},{"number":296,"x":208,"y":415.859375},{"number":297,"x":225,"y":435.859375},{"number":298,"x":240,"y":435.859375},{"number":299,"x":250,"y":430.859375},{"number":300,"x":260,"y":412.859375},{"number":301,"x":280,"y":403.859375},{"number":302,"x":292,"y":390.859375},{"number":303,"x":297,"y":372.859375},{"number":304,"x":323,"y":364.859375},{"number":305,"x":316,"y":348.859375},{"number":306,"x":308,"y":328.859375},{"number":307,"x":307,"y":309.859375},{"number":308,"x":295,"y":299.859375},{"number":309,"x":285,"y":285.859375},{"number":310,"x":284,"y":259.859375},{"number":311,"x":289,"y":244.859375},{"number":312,"x":303,"y":231.859375},{"number":313,"x":330,"y":224.859375}],"max_x":614,"min_x":168,"max_y":586.859375,"min_y":224.859375}]', true)];

// Calculate canvas size based on background image
$bg_image_filename = 'pics/70.png'; // TODO: Update with the actual filename of the background image
$bg_image_info = getimagesize($bg_image_filename);
$bg_image_width = $bg_image_info[0];
$bg_image_height = $bg_image_info[1];

$colors = ['#00008b', '#000000', '#ff6c00'];
$colors_alpha = ['rgba(100,149,237,0.1)', 'rgba(255,255,255,0.1)', 'rgba(255,108,0,0.1)'];
// Iterate through data array
echo '<div class="job-wrapper active" id="job_quali">';
foreach ($data as $index => $json_obj) {
  // Extract data
  $points = $json_obj[0]['points'];
  $number_points = $json_obj[0]['number_points'];
  $ID = $json_obj[0]['ID'];
  // $max_x = $json_obj[0]['max_x'];
  // $min_x = $json_obj[0]['min_x'];
  // $max_y = $json_obj[0]['max_y'];
  // $min_y = $json_obj[0]['min_y'];

  // Calculate canvas size
  $canvas_width = $bg_image_width; //$max_x - $min_x;
  $canvas_height = $bg_image_height; //$max_y - $min_y;

  // Output canvas and points
  echo '<div class="task-wrapper" id="task_' . $ID . '">';
  echo '<div class="canvas-wrapper">';
  echo '<h1>Task ' . $ID . ': ' . $number_points . ' corners</h1>';
  echo '<canvas id="canvas_' . $index . '" width="' . $canvas_width . '" height="' . $canvas_height . '" style="background-image:url(' . $bg_image_filename . ');box-shadow :0 0 10px 3px ' . $colors[$index] . ';"></canvas>';
  echo '<script>
          var canvas_' . $index . ' = document.getElementById("canvas_' . $index . '");
          var ctx_' . $index . ' = canvas_' . $index . '.getContext("2d");
          ctx_' . $index . '.fillStyle = "' . $colors_alpha[$index] . '";
          ctx_' . $index . '.lineWidth = "7";
          ctx_' . $index . '.strokeStyle = "' . $colors[$index] . '";
          // Draw points and lines on canvas
          ';
  foreach ($points as $i => $point) {
    echo 'var x' . $i . ' = ' . $point['x'] . ';
          var y' . $i . ' = ' . $point['y'] . ';
          ctx_' . $index . '.arc(x' . $i . ', y' . $i . ', 2, 0, 2 * Math.PI);
          ';
  }
  echo 'ctx_' . $index . '.fill();
          ';
  echo 'ctx_' . $index . '.beginPath();
          ctx_' . $index . '.moveTo(x0, y0);
          ';
  for ($i = 1; $i < count($points); $i++) {
    echo 'ctx_' . $index . '.lineTo(x' . $i . ', y' . $i . ');
          ';
  }
  echo 'ctx_' . $index . '.lineTo(x0, y0);
          ctx_' . $index . '.stroke();
          ctx_' . $index . '.closePath();
          ';
  echo '</script>';
  echo '</div>';
  echo '
    <div class="placeholder">
    </div>
  ';
  echo '</div>';
}
echo '</div>';
echo '<script>';
echo '
        const userInfo = {
        campaign: "' . $Campaign_id . '",
        worker: "' . $Worker_id . '",
        random: "' . $Rand_key . '"
        };
        ';
echo '</script>';
?>
<div id="rating-wrapper">
  <div id="rating-container">
    <h2>Please order the selections from the best to the worst</h2>
    <ul id="rating">
      <div class="job-rating-wrapper active" id="rating_job_quali">
        <?php
        $picnames = ['1st', '2nd', '3rd'];
        foreach ($data as $index => $json_obj) {
          // Extract data
          $ID = $json_obj[0]['ID'];
          echo '
      <li style="color: ' . $colors[$index] . '; background-color:' . $colors[$index] . '42; box-shadow: 0 0 5px 1px ' . $colors[$index] . ';">
        <button class="arrow-up"><i class="fas fa-long-arrow-alt-up"></i></button>
        <button class="arrow-down"><i class="fas fa-long-arrow-alt-down"></i></button>
        <span>Task ' . $ID . '</span>
        <picture>
          <source type="image/webp" srcset="pics/icons/' . $picnames[$index] . '.webp">
          <img decoding="async" loading="lazy" src="pics/icons/' . $picnames[$index] . '.png" alt="' . $picnames[$index] . '" />
        </picture>
      </li>
      ';
        } ?>
      </div>
    </ul>
    <button id="confirmBtn">Confirm</button>
  </div>
</div>
<br><br>
<?php
require_once('footer.php');
