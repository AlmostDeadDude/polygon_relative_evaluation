//check what is the page name, is it index.php or results.php
let page = window.location.pathname.split("/").pop();;

//in both cases:
//update the year in the footer
// document.getElementById("year").innerHTML = new Date().getFullYear();

//specific for index.php
if (page === "index.php" || page === "") {
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
                    const progressBar = document.getElementById('task_counter_bar');
                    if (progressBar) {
                        progressBar.style.width = (current_job + 1) / total_jobs * 100 + '%';
                    }

                    //if there are more tasks to do - save current results and display the next task
                    if (current_job < total_jobs - 1) {
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
                        //otherwise save the results and redirect to the results page
                        //send data as json 
                        let data = JSON.stringify({
                            userInfo: userInfo,
                            dataInfo: dataInfo,
                            values: values,
                        });
                        try {
                            let response = await fetch("saveResults.php", {
                                method: "POST",
                                body: data,
                                headers: {
                                    "Content-Type": "application/json",
                                },
                            });
                            let result = await response.json();
                            if (result.status === "success") {
                                window.location.href = "results.php";
                            } else {
                                throw new Error(result.message || "Unexpected response");
                            }
                        } catch (error) {
                            console.error(error);
                            alert("Something went wrong while finishing the demo. Please try again.");
                        }
                    }
                }
            });
        }
    }
} else if (page === "results.php") {
    //the results page now simply links back to a fresh random job
    const retryBtn = document.getElementById("copyVcodeBtn");
    if (retryBtn) {
        retryBtn.addEventListener("click", () => {
            window.location.href = "index.php";
        });
    }
} else {
    //non-task informational pages
    const infoLink = document.querySelector('header a');
    if (infoLink) {
        infoLink.remove();
    }
}
