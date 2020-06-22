window.addEventListener('scroll', function(e){
    const target = document.querySelectorAll('.scroll');

    var index = 0, length = target.length;
    for(index; index < length; index++){
        var pos = window.pageYOffset * target[index].dataset.rate;
        target[index].style.transform = 'translate3d(0px, '+pos+'px, 0px)';
    }
})

window.addEventListener('scroll', function(e){
    const target02 = document.querySelectorAll('.fade');

    var index02 = 0, length02 = target02.length;
    for(index02; index02 < length02; index02++){
        var off = window.pageYOffset;
        target02[index02].style.opacity = 1- ((off*4)/1000);
    }
})

window.addEventListener('scroll', function(e){
    const target03 = document.querySelectorAll('.fade02');

    var index03 = 0, length03 = target03.length;
    for(index03; index03 < length03; index03++){
        var off = window.pageYOffset;
        target03[index03].style.opacity = 1- ((off)/1000);
    }
})