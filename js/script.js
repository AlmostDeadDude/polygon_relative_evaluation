//check what is the page name, is it index.php or results.php
let page = window.location.pathname.split("/").pop();;

//in both cases:
//update the year in the footer
document.getElementById("year").innerHTML = new Date().getFullYear();

//specific for index.php and results.php
if (page === "index.php" || page === "" || page === 'qualification.php') {
    // variables and dom elements
    let userClicked = false;
    const values = [];
    const confirmBtn = document.getElementById("confirmBtn");
    const ratingWrapper = document.getElementById("rating-wrapper");
    const ratingList = document.getElementById("rating");
    const taskWrapper = document.querySelector(".task-wrapper");
    const canvasWrapper = taskWrapper.querySelector(".canvas-wrapper");

    //adjust the width of the rating bar for bigger screens
    //calculate the width difference between the first task-wrapper and its child - canvas-wrapper on window resize and once at the beginning
    function adjustRatingBar() {
        let widthDiff = taskWrapper.offsetWidth - canvasWrapper.offsetWidth;
        //if the diff is > 250px we take that number, subtract 30 for spacing and make the width of the #rating-wrapper that number
        if (widthDiff > 250) {
            ratingWrapper.style.width = widthDiff - 30 + "px";
        } else {
            ratingWrapper.style.width = "100vw";
        }
    }
    window.addEventListener("resize", adjustRatingBar);
    adjustRatingBar();

    //at the beginning disable the first .arrow-up and last .arrow-down
    [...ratingList.children].forEach(item => {
        item.querySelector("li:first-child .arrow-up").disabled = true;
        item.querySelector("li:last-child .arrow-down").disabled = true;
    });

    // Add event listeners to each list item
    ratingList.querySelectorAll("li").forEach(item => {
        const upArrow = item.querySelector(".arrow-up");
        const downArrow = item.querySelector(".arrow-down");
        const content = item.querySelector("span");
        upArrow.addEventListener("click", () => moveItem(item, "up", content));
        downArrow.addEventListener("click", () => moveItem(item, "down", content));
    });

    // Move item up or down in the list
    function moveItem(item, direction, content) {
        const activeOptions = ratingList.querySelector('.active').children
        const currentIndex = Array.from(activeOptions).indexOf(item);
        const newIndex = direction === "up" ? currentIndex - 1 : currentIndex + 1;
        if (newIndex >= 0 && newIndex < activeOptions.length) {
            userClicked = true;
            const targetItem = activeOptions[newIndex];
            const temp = content.innerText;
            const temp_bgcolor = content.parentElement.style.backgroundColor;
            const temp_shadow = content.parentElement.style.boxShadow;
            const temp_color = content.parentElement.style.color;
            content.innerText = targetItem.querySelector("span").innerText;
            content.parentElement.style.backgroundColor = targetItem.style.backgroundColor;
            content.parentElement.style.boxShadow = targetItem.style.boxShadow;
            content.parentElement.style.color = targetItem.style.color;
            targetItem.querySelector("span").innerText = temp;
            targetItem.style.backgroundColor = temp_bgcolor;
            targetItem.style.boxShadow = temp_shadow;
            targetItem.style.color = temp_color;

            //both source and target spans recieve the class 'animated' for 0.5s
            content.classList.add("animated");
            targetItem.querySelector("span").classList.add("animated");
            setTimeout(() => {
                content.classList.remove("animated");
                targetItem.querySelector("span").classList.remove("animated");
            }, 500);
        }
    }

    if (page === "index.php" || page === "") {
        //when confirmed the values are sent to the server = saveResults.php
        //if it returns the success message, the user is redirected to the results page = results.php
        if (confirmBtn) {
            confirmBtn.addEventListener("click", async () => {
                //confirm button should only work if user interacted - otherwise the user gets a warning
                if (!userClicked) {
                    alert("Please order the selections using the arrows! If the initial order is correct, confirm again.");
                    userClicked = true;
                    return;
                } else {
                    //get the data from the #rating ul
                    values[current_job] = {};
                    [...ratingList.querySelector('.active').children].forEach((item, index) => {
                        values[current_job][index + 1] = item.querySelector("span").innerText;
                    });
                    document.getElementById('task_counter_bar').style.width = (current_job + 1) / total_jobs * 100 + '%';

                    //if there are more tasks to do - save current results and display the next task
                    if (current_job < total_jobs) {
                        //then go for the next task
                        document.getElementById('job_' + current_job).classList.remove('active');
                        document.getElementById('rating_job_' + current_job).classList.remove('active');
                        current_job++;
                        document.getElementById('job_' + (current_job)).classList.add('active');
                        document.getElementById('rating_job_' + (current_job)).classList.add('active');
                        document.querySelector('#task_counter span').innerText = current_job + 1;
                        //also smooth scroll to the top of the page
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                        userClicked = false;
                    } else {
                        //check the last task solution
                        //reference: 96, 36, 41
                        const reference = {
                            1: "Task #96",
                            2: "Task #36",
                            3: "Task #41",
                        }
                        //if the answer is incorrect, redirect to the failed page
                        let valuesLast = values[total_jobs];
                        if (valuesLast[1] != reference[1] || valuesLast[2] != reference[2] || valuesLast[3] != reference[3]) {
                            //also send the filename to the failed page - weak security but will do for now
                            window.location.href = "failed.php?file=" + dataInfo.job + "_" + dataInfo.iteration;
                            return;
                        } else {
                            //otherwise save the results and redirect to the results page
                            //send data as json 
                            let data = JSON.stringify({
                                userInfo: userInfo,
                                dataInfo: dataInfo,
                                values: values,
                            });
                            let response = await fetch("saveResults.php", {
                                method: "POST",
                                body: data,
                                headers: {
                                    "Content-Type": "application/json",
                                },
                            });
                            let result = await response.text();
                            console.log(result);
                            if (result == "success") {
                                window.location.href = "results.php?vcode=" + userInfo.vcode;
                            }
                        }
                    }
                }
            });
        }
    } else { //qualification.php
        //adjust the header text
        document.querySelector("header span").innerText = "Qualification Task";
        //proper sequence is: BCA
        const properSequence = ["B", "C", "A"];
        //when user submits we compare the submission against the proper sequence
        if (confirmBtn) {
            confirmBtn.addEventListener("click", () => {
                //get the data from the #rating ul
                [...ratingList.querySelector('.active').children].forEach((item, index) => {
                    values[index + 1] = item.querySelector("span").innerText.slice(-1);
                });
                //compare the values to the proper sequence
                if (values[1] === properSequence[0] && values[2] === properSequence[1] && values[3] === properSequence[2]) {
                    //if the sequence is correct, we allow the user to go to the main task
                    ratingWrapper.innerHTML = "<h2>Correct!</h2><p>You will now be redirected to the main task.</p><div class='loader'><i class='fas fa-spinner'></i></div>";
                    setTimeout(() => {
                        window.location.href = "index.php?campaign=" + userInfo.campaign + "&worker=" + userInfo.worker + "&rand_key=" + userInfo.random;
                    }, 3000);
                } else {
                    //otherwise, we show a warning telling the user to try again
                    alert("The placement is not correct! Please evaluate the selections properly.");
                }
            });
        }
    }
} else if (page === "results.php") {
    //the results page onle needs a simple button to copy the vcode to the clipboard
    const copyBtn = document.getElementById("copyVcodeBtn");
    const vcodeEl = document.getElementById("vcodeContainer");
    copyBtn.addEventListener("click", () => {
        navigator.clipboard.writeText(vcodeEl.innerText.trim());
        copyBtn.innerText = "Copied!";
    });
} else {
    //page === "about.php"
    //disable the link to about php - it is not needed
    document.querySelector('header a').remove();
}