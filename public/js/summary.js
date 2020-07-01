let rubric = document.getElementById("essaisImgs");

let nbImgs = rubric.children.length;
console.log(nbImgs);

let imgs = essaisImgs.children;
console.log(imgs);

let height = 100/nbImgs;
console.log(height);

let i;
for (i = 0; i < nbImgs; i++) {
    imgs[i].style.height = height + "%";
  }