/*var prevScrollpos = window.pageYOffset;

window.onscroll = function() {
  var currentScrollPos = window.pageYOffset;
  if (prevScrollpos > currentScrollPos) {
    document.getElementById("navBar").style.top = "-25px";
  } 
  else {
    document.getElementById("navBar").style.top = "-200px";
  }
  
  prevScrollpos = currentScrollPos;
  };

var link01 = document.getElementById('link01');

link01.addEventListener("mouseover", function(){
  document.getElementById("popupInfos").style.top = "150px";
  document.getElementById("popupInfos").style.backgroundColor = "green";
});
link01.addEventListener("mouseout", function(){
  document.getElementById("popupInfos").style.top = "-500px";
});*/

window.addEventListener("scroll", ()=>{
  let navbar = document.getElementById("navBar");
  navbar.classList.toggle("sticky", window.scrollY > 0);
})

