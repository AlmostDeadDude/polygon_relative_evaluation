//check what is the page name, is it index.php or results.php
let page = window.location.pathname.split("/").pop();;

//in both cases:
//update the year in the footer
document.getElementById("year").innerHTML = new Date().getFullYear();

//specific for index.php and results.php
if (page === "index.php" || page === "") {
    // variables and dom elements
    let userClicked = false;
    const values = {};
    const confirmBtn = document.getElementById("confirmBtn");
    const ratingWrapper = document.getElementById("rating-wrapper");
    const ratingList = document.getElementById("rating");
    const taskWrapper = document.querySelector(".task-wrapper");
    const canvasWrapper = taskWrapper.querySelector(".canvas-wrapper");

    //make rating options selectable (like radio buttons)
    let tasks = document.querySelectorAll(".task-wrapper");
    tasks.forEach(task => {
        console.log(task);
    });

    //adjust the width of the rating bar for biiger screens
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
    ratingList.querySelector("li:first-child .arrow-up").disabled = true;
    ratingList.querySelector("li:last-child .arrow-down").disabled = true;

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
        const currentIndex = Array.from(ratingList.children).indexOf(item);
        const newIndex = direction === "up" ? currentIndex - 1 : currentIndex + 1;
        if (newIndex >= 0 && newIndex < ratingList.children.length) {
            userClicked = true;
            const targetItem = ratingList.children[newIndex];
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

            //both source and tatget spans recieve the class 'animated' for 0.5s
            content.classList.add("animated");
            targetItem.querySelector("span").classList.add("animated");
            setTimeout(() => {
                content.classList.remove("animated");
                targetItem.querySelector("span").classList.remove("animated");
            }, 500);
        }
    }

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
                //get the date from the #rating ul
                [...ratingList.children].forEach((item, index) => {
                    values[index + 1] = item.querySelector("span").innerText;
                });
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
        });
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