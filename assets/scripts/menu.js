const btnOpen = document.getElementById("open-btn");
const btnClose = document.getElementById("close-btn");
const sideBar = document.querySelector(".sidebar");

const handleMenu = () => {
    if(sideBar.classList.contains("open")) {
        sideBar.classList.remove("open");
        sideBar.classList.add("close");
    } else {
        sideBar.classList.remove("close");
        sideBar.classList.remove("closed");
        sideBar.classList.add("open");
    }
}


btnOpen.addEventListener("click", handleMenu);
btnClose.addEventListener("click", handleMenu);

const handlePreventMenu = () => {
    if(window.innerWidth >= 992) {
        sideBar.classList.remove("close");
        sideBar.classList.remove("closed");
        sideBar.classList.add("open");
    } else {
        sideBar.classList.remove("open");
        sideBar.classList.add("closed");
    }
}

window.addEventListener('resize', handlePreventMenu);

