document.getElementById("menuToggle").addEventListener("click", function () {
    let menuItems = document.getElementById("menuItems");
    if (menuItems.style.display === "block") {
        menuItems.style.display = "none";
    } else {
        menuItems.style.display = "block";
    }
});
