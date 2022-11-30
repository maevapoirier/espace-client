const a = document.getElementById("btnWS");
const ws = document.querySelector(".ws-block");

console.log(a);

const handleWS = () => {
    if(ws.classList.contains("open")) {
        ws.classList.remove("open");
    } else {
        ws.classList.add("open");
    }
}

a.addEventListener("click", handleWS);


