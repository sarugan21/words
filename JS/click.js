let overlay = document.getElementById("overlay");
let inner = document.getElementById("inner");
let inner2 = document.getElementById("inner2");
let close = document.getElementById("close");
let blur = document.getElementById("blur");

inner.addEventListener("click", onInnerClick, false);
close.addEventListener("click", onCloseClick, false);
overlay.addEventListener("click", onOverClick, false);

function onInnerClick(event) {
  inner2.className = "active";
  overlay.className = "active";
  close.className = "active";
  blur.className = "active";
}

function onCloseClick(event) {
  inner2.className = " ";
  overlay.className = " ";
  close.className = " ";
  blur.className = " ";
}

function onOverClick(event) {
  inner2.className = " ";
  overlay.className = " ";
  close.className = " ";
  blur.className = " ";
}