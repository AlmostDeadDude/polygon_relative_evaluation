<?php

// Demo-friendly defaults for optional query params.
$Campaign_id = $_GET["campaign"] ?? "DEMO_CAMPAIGN";
$Worker_id = $_GET["worker"] ?? "GUEST";
$Rand_key = $_GET["rand_key"] ?? uniqid("demo_", true);

//job assignment algorithm - demo edition chooses a random job file
$job_files = glob('jobs/job_*.txt');
$total_numb_jobs = count($job_files);
$next_job = null;
$next_it = 1;

if ($total_numb_jobs > 0) {
  $random_job = $job_files[array_rand($job_files)];
  if (preg_match('/job_(\d+)\.txt$/', $random_job, $matches)) {
    $next_job = (int)$matches[1];
  }
}

//if no job was found we return an error message
if ($next_job === null) {
  require_once('header.php');
  echo "<h1>No jobs available</h1>
  <h1>Sorry, but there are no job files to preview at the moment.</h1>
  <h2>Add more files to the jobs folder and reload the page.</h2>
  ";
  require_once('footer.php');
  exit();
}

//if job was assigned we read the appropriate job TXT file line by line
$json_filename = 'jobs/job_' . $next_job . '.txt';

$handle = fopen($json_filename, "r");
$data = array();

$tasks_counter = 0;
$pics_in_task = 3;
$pics_counter = 0;

while (($line = fgets($handle)) !== false) {
  // Skip empty lines
  if (trim($line) === '') {
    continue;
  }

  // Parse JSON from each line
  $json_data = json_decode($line, true);
  if ($json_data === null) {
    echo 'Error parsing JSON: ' . json_last_error_msg() . PHP_EOL;
    continue;
  }

  // Append parsed JSON to data array
  if (!isset($data[$tasks_counter])) {
    $data[$tasks_counter] = array();
  }
  $data[$tasks_counter][$pics_counter] = $json_data;
  $pics_counter++;
  if ($pics_counter == $pics_in_task) {
    $pics_counter = 0;
    $tasks_counter++;
  }
}
fclose($handle);

$tasks_per_job = count($data);

if ($tasks_per_job === 0) {
  require_once('header.php');
  echo "<h1>No polygons to evaluate</h1>
  <h2>The selected job file is empty. Please load another job.</h2>";
  require_once('footer.php');
  exit();
}

// Calculate canvas size based on background image
$bg_image_filename = 'pics/70.png'; // TODO: Update with the actual filename of the background image
$bg_image_info = getimagesize($bg_image_filename);
$bg_image_width = $bg_image_info[0];
$bg_image_height = $bg_image_info[1];

require_once('header.php');

$colors = ['#00008b', '#000000', '#ff6c00'];
$colors_alpha = ['rgba(100,149,237,0.1)', 'rgba(255,255,255,0.1)', 'rgba(255,108,0,0.1)'];
// Iterate through data array
foreach ($data as $jobInd => $job) {
  if ($jobInd == 0) {
    echo '<div class="job-wrapper active" id="job_' . $jobInd . '">';
  } else {
    echo '<div class="job-wrapper" id="job_' . $jobInd . '">';
  }
  foreach ($job as $index => $json_obj) {
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
    echo '<h1>Selection #' . $ID . ': ' . $number_points . ' corners</h1>';
    echo '<canvas id="canvas_' . $jobInd . '_' . $index . '" width="' . $canvas_width . '" height="' . $canvas_height . '" style="background-image:url(' . $bg_image_filename . ');box-shadow :0 0 10px 3px ' . $colors[$index] . ';"></canvas>';
    echo '<script>
            var canvas_' . $jobInd . '_' . $index . ' = document.getElementById("canvas_' . $jobInd . '_' . $index . '");
            var ctx_' . $jobInd . '_' . $index . ' = canvas_' . $jobInd . '_' . $index . '.getContext("2d");
            ctx_' . $jobInd . '_' . $index . '.fillStyle = "' . $colors_alpha[$index] . '";
            ctx_' . $jobInd . '_' . $index . '.lineWidth = "7";
            ctx_' . $jobInd . '_' . $index . '.strokeStyle = "' . $colors[$index] . '";
            // Draw points and lines on canvas
            ';
    foreach ($points as $i => $point) {
      echo 'var x' . $i . ' = ' . $point['x'] . ';
            var y' . $i . ' = ' . $point['y'] . ';
            ctx_' . $jobInd . '_' . $index . '.arc(x' . $i . ', y' . $i . ', 2, 0, 2 * Math.PI);
            ';
    }
    echo 'ctx_' . $jobInd . '_' . $index . '.fill();
            ';
    echo 'ctx_' . $jobInd . '_' . $index . '.beginPath();
            ctx_' . $jobInd . '_' . $index . '.moveTo(x0, y0);
            ';
    for ($i = 1; $i < count($points); $i++) {
      echo 'ctx_' . $jobInd . '_' . $index . '.lineTo(x' . $i . ', y' . $i . ');
            ';
    }
    echo 'ctx_' . $jobInd . '_' . $index . '.lineTo(x0, y0);
            ctx_' . $jobInd . '_' . $index . '.stroke();
            ctx_' . $jobInd . '_' . $index . '.closePath();
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
}

echo '<script>';
echo '
let current_job = 0;
const total_jobs = ' . $tasks_per_job . ';
const userInfo = {
  campaign: "' . $Campaign_id . '",
  worker: "' . $Worker_id . '",
  randKey: "' . $Rand_key . '"
};
const dataInfo = {
  file: "' . $json_filename . '",
  image: "' . $bg_image_filename . '",
  job: "' . $next_job . '",
  iteration: "' . $next_it . '",
};
';
echo '</script>';
?>
<div id="rating-wrapper">
  <div id="rating-container">
    <h2>Please order the selections from the best to the worst</h2>
    <ul id="rating">
      <?php
      $picnames = ['1st', '2nd', '3rd'];
      foreach ($data as $jobInd => $job) {
        if ($jobInd == 0) {
          echo '<div class="job-rating-wrapper active" id="rating_job_' . $jobInd . '">';
        } else {
          echo '<div class="job-rating-wrapper" id="rating_job_' . $jobInd . '">';
        }
        foreach ($job as $index => $json_obj) {
          // Extract data
          $ID = $json_obj[0]['ID'];
          echo '
          <li style="color: ' . $colors[$index] . '; background-color:' . $colors[$index] . '42; box-shadow: 0 0 5px 1px ' . $colors[$index] . ';">
            <button class="arrow-up"><i class="fas fa-long-arrow-alt-up"></i></button>
            <button class="arrow-down"><i class="fas fa-long-arrow-alt-down"></i></button>
            <span>#' . $ID . '</span>
            <picture>
              <source type="image/webp" srcset="pics/icons/' . $picnames[$index] . '.webp">
              <img decoding="async" loading="lazy" src="pics/icons/' . $picnames[$index] . '.png" alt="' . $picnames[$index] . '" />
            </picture>
          </li>
          ';
        }
        echo '</div>';
      }
      echo '</ul>';
      echo '<div id="task_counter">Task <span>1</span>/' . $tasks_per_job . '</div>';
      ?>
      <div id="task_counter_progress">
        <div id="task_counter_bar"></div>
      </div>
      <button id="confirmBtn">Confirm</button>
  </div>
</div>
<br><br>
<?php
require_once('footer.php');
