<?php

//get the microworkers usual staff
$Campaign_id = $_GET["campaign"];
$Worker_id = $_GET["worker"];
$Rand_key = $_GET["rand_key"];
$My_secret_key = "2a0f6d1b74e582f9ee21c8e899bb014163431b1491255737db06bb587703cfcf";
// string we will hash to produce VCODE
$String_final = $Campaign_id . $Worker_id . $Rand_key . $My_secret_key;
$vcode_for_proof = "mw-" . hash("sha256", $String_final);

//job assignment algorithm
//first count how many files are in the jobs folder
$total_numb_jobs = count(glob('jobs/*'));
//set up the desired number of iterations
$total_numb_it = 1;
//default values = error codes for later
$next_job = 10000;
$next_it = 10000;
//check the files in the results folder and assign the next available job
for ($it = 1; $it <= $total_numb_it; $it++) {                       //for each iteration
  for ($in = 1; $in <= $total_numb_jobs; $in++) {                   //for each job
    $file_to_check1 = "results/job_" . $in . '_' . $it . ".txt";    //folder to check for results
    $existing = file_exists($file_to_check1);                       //check if file exists
    if ($existing) {                                                //if it does exist
      $no_of_lines = count(file($file_to_check1));                  //count the number of lines - basically check if empty
      $last_mod = filemtime($file_to_check1);                       //get the last modification time
      $cur_time = time();                                           //get the current time
      $time_since_last_mod = ($cur_time - $last_mod) / 60;          //calculate the time since last modification in minutes
      if ($no_of_lines < 1 and $time_since_last_mod > 20) {         //if the file is empty and it has been more than 20 minutes since last modification
        $next_job = $in;                                            //accept the job number
        $next_it = $it;                                             //and the iteration number
        unlink($file_to_check1);                                    //delete existing empty file
        break 2;                                                    //break out of the loops
      }
    } else {                                                        //if the file does not exist
      $next_job = $in;                                              //accept the job number
      $next_it = $it;                                               //and the iteration number
      break 2;                                                      //break out of the loops
    }
  }
}
//after job assignment we either end up with a legit job number and iteration number or with error codes
//in any cas ewe create the appropriate file in results (and user_info) folders
file_put_contents("results/job_" . $next_job . "_" . $next_it . ".txt", "");
file_put_contents("user_info/job_" . $next_job . "_" . $next_it . ".txt", "");

//if after the job assignment we have error codes we return an error message
if ($next_job == 10000 and $next_it == 10000) {
  require_once('header.php');
  echo "<h1>⚠️</h1>
  <h1>Sorry, there are no more jobs available at the moment.</h1>
  <h2>Please try again later.</h2>
  ";
  require_once('footer.php');
  exit();
}

//if job was assigned we read the appropriate job TXT file line by line
$json_filename = 'jobs/job_' . $next_job . '.txt';

$handle = fopen($json_filename, "r");
$data = array();

$tasks_per_job = 5;
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

//add control task
$data[$tasks_per_job] = array();
$data[$tasks_per_job][] = json_decode('[{"ID": "41", "number": 6, "number_points": 7, "points": [{"number": 40, "x": 563, "y": 529.8515625}, {"number": 41, "x": 477, "y": 566.8515625}, {"number": 42, "x": 354, "y": 496.8515625}, {"number": 43, "x": 347, "y": 362.8515625}, {"number": 44, "x": 447, "y": 289.8515625}, {"number": 45, "x": 584, "y": 356.8515625}, {"number": 46, "x": 593, "y": 362.8515625}], "max_x": 593, "min_x": 347, "max_y": 566.8515625, "min_y": 289.8515625}]', true);
$data[$tasks_per_job][] = json_decode('[{"ID": "96", "number": 3, "number_points": 98, "points": [{"number": 91, "x": 442.00567626953125, "y": 502.51419830322266}, {"number": 92, "x": 429.00567626953125, "y": 496.51419830322266}, {"number": 93, "x": 422.00567626953125, "y": 508.51419830322266}, {"number": 94, "x": 436.00567626953125, "y": 516.5141983032227}, {"number": 95, "x": 439.00567626953125, "y": 524.5141983032227}, {"number": 96, "x": 428.00567626953125, "y": 538.5141983032227}, {"number": 97, "x": 414.00567626953125, "y": 551.5141983032227}, {"number": 98, "x": 398.00567626953125, "y": 564.5141983032227}, {"number": 99, "x": 388.00567626953125, "y": 567.5141983032227}, {"number": 100, "x": 372.00567626953125, "y": 562.5141983032227}, {"number": 101, "x": 355.00567626953125, "y": 550.5141983032227}, {"number": 102, "x": 351.00567626953125, "y": 537.5141983032227}, {"number": 103, "x": 346.00567626953125, "y": 522.5141983032227}, {"number": 104, "x": 334.00567626953125, "y": 509.51419830322266}, {"number": 105, "x": 321.00567626953125, "y": 530.5141983032227}, {"number": 106, "x": 308.00567626953125, "y": 550.5141983032227}, {"number": 107, "x": 284.00567626953125, "y": 541.5141983032227}, {"number": 108, "x": 261.00567626953125, "y": 540.5141983032227}, {"number": 109, "x": 251.00567626953125, "y": 535.5141983032227}, {"number": 110, "x": 239.00567626953125, "y": 519.5141983032227}, {"number": 111, "x": 249.00567626953125, "y": 502.51419830322266}, {"number": 112, "x": 264.00567626953125, "y": 488.51419830322266}, {"number": 113, "x": 245.00567626953125, "y": 472.51419830322266}, {"number": 114, "x": 222.00567626953125, "y": 462.51419830322266}, {"number": 115, "x": 223.00567626953125, "y": 444.51419830322266}, {"number": 116, "x": 236.00567626953125, "y": 437.51419830322266}, {"number": 117, "x": 247.00567626953125, "y": 427.51419830322266}, {"number": 118, "x": 263.00567626953125, "y": 408.51419830322266}, {"number": 119, "x": 279.00567626953125, "y": 408.51419830322266}, {"number": 120, "x": 296.00567626953125, "y": 381.51419830322266}, {"number": 121, "x": 314.00567626953125, "y": 363.51419830322266}, {"number": 122, "x": 319.00567626953125, "y": 352.51419830322266}, {"number": 123, "x": 312.00567626953125, "y": 337.51419830322266}, {"number": 124, "x": 321.00567626953125, "y": 322.51419830322266}, {"number": 125, "x": 314.00567626953125, "y": 311.51419830322266}, {"number": 126, "x": 301.00567626953125, "y": 306.51419830322266}, {"number": 127, "x": 285.00567626953125, "y": 292.51419830322266}, {"number": 128, "x": 289.00567626953125, "y": 279.51419830322266}, {"number": 129, "x": 292.00567626953125, "y": 263.51419830322266}, {"number": 130, "x": 294.00567626953125, "y": 250.51419830322266}, {"number": 131, "x": 301.00567626953125, "y": 238.51419830322266}, {"number": 132, "x": 315.00567626953125, "y": 228.51419830322266}, {"number": 133, "x": 334.00567626953125, "y": 211.51419830322266}, {"number": 134, "x": 357.00567626953125, "y": 222.51419830322266}, {"number": 135, "x": 371.00567626953125, "y": 232.51419830322266}, {"number": 136, "x": 378.00567626953125, "y": 247.51419830322266}, {"number": 137, "x": 386.00567626953125, "y": 247.51419830322266}, {"number": 138, "x": 412.00567626953125, "y": 246.51419830322266}, {"number": 139, "x": 420.00567626953125, "y": 260.51419830322266}, {"number": 140, "x": 433.00567626953125, "y": 263.51419830322266}, {"number": 141, "x": 448.00567626953125, "y": 253.51419830322266}, {"number": 142, "x": 461.00567626953125, "y": 252.51419830322266}, {"number": 143, "x": 469.00567626953125, "y": 266.51419830322266}, {"number": 144, "x": 478.00567626953125, "y": 279.51419830322266}, {"number": 145, "x": 485.00567626953125, "y": 291.51419830322266}, {"number": 146, "x": 494.00567626953125, "y": 307.51419830322266}, {"number": 147, "x": 495.00567626953125, "y": 318.51419830322266}, {"number": 148, "x": 507.00567626953125, "y": 320.51419830322266}, {"number": 149, "x": 521.0056762695312, "y": 325.51419830322266}, {"number": 150, "x": 530.0056762695312, "y": 337.51419830322266}, {"number": 151, "x": 534.0056762695312, "y": 346.51419830322266}, {"number": 152, "x": 531.0056762695312, "y": 357.51419830322266}, {"number": 153, "x": 532.0056762695312, "y": 372.51419830322266}, {"number": 154, "x": 544.0056762695312, "y": 387.51419830322266}, {"number": 155, "x": 559.0056762695312, "y": 405.51419830322266}, {"number": 156, "x": 564.0056762695312, "y": 419.51419830322266}, {"number": 157, "x": 568.0056762695312, "y": 428.51419830322266}, {"number": 158, "x": 587.0056762695312, "y": 430.51419830322266}, {"number": 159, "x": 598.0056762695312, "y": 445.51419830322266}, {"number": 160, "x": 603.0056762695312, "y": 455.51419830322266}, {"number": 161, "x": 604.0056762695312, "y": 463.51419830322266}, {"number": 162, "x": 592.0056762695312, "y": 474.51419830322266}, {"number": 163, "x": 577.0056762695312, "y": 481.51419830322266}, {"number": 164, "x": 578.0056762695312, "y": 492.51419830322266}, {"number": 165, "x": 593.0056762695312, "y": 503.51419830322266}, {"number": 166, "x": 607.0056762695312, "y": 512.5141983032227}, {"number": 167, "x": 613.0056762695312, "y": 520.5141983032227}, {"number": 168, "x": 612.0056762695312, "y": 535.5141983032227}, {"number": 169, "x": 598.0056762695312, "y": 542.5141983032227}, {"number": 170, "x": 581.0056762695312, "y": 543.5141983032227}, {"number": 171, "x": 566.0056762695312, "y": 547.5141983032227}, {"number": 172, "x": 554.0056762695312, "y": 551.5141983032227}, {"number": 173, "x": 550.0056762695312, "y": 546.5141983032227}, {"number": 174, "x": 544.0056762695312, "y": 554.5141983032227}, {"number": 175, "x": 536.0056762695312, "y": 559.5141983032227}, {"number": 176, "x": 534.0056762695312, "y": 562.5141983032227}, {"number": 177, "x": 535.0056762695312, "y": 571.5141983032227}, {"number": 178, "x": 524.0056762695312, "y": 576.5141983032227}, {"number": 179, "x": 514.0056762695312, "y": 577.5141983032227}, {"number": 180, "x": 493.00567626953125, "y": 565.5141983032227}, {"number": 181, "x": 478.00567626953125, "y": 562.5141983032227}, {"number": 182, "x": 467.00567626953125, "y": 553.5141983032227}, {"number": 183, "x": 465.00567626953125, "y": 541.5141983032227}, {"number": 184, "x": 470.00567626953125, "y": 527.5141983032227}, {"number": 185, "x": 477.00567626953125, "y": 517.5141983032227}, {"number": 186, "x": 480.00567626953125, "y": 506.51419830322266}, {"number": 187, "x": 470.00567626953125, "y": 495.51419830322266}, {"number": 188, "x": 454.00567626953125, "y": 491.51419830322266}], "max_x": 613.0056762695312, "min_x": 222.00567626953125, "max_y": 577.5141983032227, "min_y": 211.51419830322266}]', true);
$data[$tasks_per_job][] = json_decode('[{"ID": "36", "number": 5, "number_points": 28, "points": [{"number": 108, "x": 368, "y": 232.85416793823242}, {"number": 109, "x": 391, "y": 246.85416793823242}, {"number": 110, "x": 432, "y": 264.8541679382324}, {"number": 111, "x": 460, "y": 242.85416793823242}, {"number": 112, "x": 492, "y": 309.8541679382324}, {"number": 113, "x": 544, "y": 354.8541679382324}, {"number": 114, "x": 530, "y": 362.8541679382324}, {"number": 115, "x": 575, "y": 430.8541679382324}, {"number": 116, "x": 614, "y": 465.8541679382324}, {"number": 117, "x": 578, "y": 482.8541679382324}, {"number": 118, "x": 614, "y": 538.8541679382324}, {"number": 119, "x": 544, "y": 550.8541679382324}, {"number": 120, "x": 518, "y": 584.8541679382324}, {"number": 121, "x": 471, "y": 545.8541679382324}, {"number": 122, "x": 438, "y": 498.8541679382324}, {"number": 123, "x": 392, "y": 574.8541679382324}, {"number": 124, "x": 326, "y": 524.8541679382324}, {"number": 125, "x": 282, "y": 548.8541679382324}, {"number": 126, "x": 226, "y": 544.8541679382324}, {"number": 127, "x": 252, "y": 477.8541679382324}, {"number": 128, "x": 200, "y": 426.8541679382324}, {"number": 129, "x": 252, "y": 425.8541679382324}, {"number": 130, "x": 291, "y": 394.8541679382324}, {"number": 131, "x": 322, "y": 360.8541679382324}, {"number": 132, "x": 311, "y": 322.8541679382324}, {"number": 133, "x": 280, "y": 272.8541679382324}, {"number": 134, "x": 287, "y": 226.85416793823242}, {"number": 135, "x": 343, "y": 204.85416793823242}], "max_x": 614, "min_x": 200, "max_y": 584.8541679382324, "min_y": 204.85416793823242}]', true);

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
    echo '<h1>Task #' . $ID . ': ' . $number_points . ' corners</h1>';
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
let total_jobs = ' . $tasks_per_job . ';
const userInfo = {
  campaign: "' . $Campaign_id . '",
  worker: "' . $Worker_id . '",
  vcode: "' . $vcode_for_proof . '"
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
            <span>Task #' . $ID . '</span>
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
      echo '<div id="task_counter">Task <span>1</span>/' . ($tasks_per_job + 1) . '</div>';
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
