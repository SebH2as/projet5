const pseudo = document.getElementById('pseudo');

const regex = /^[A-Za-z]{3,15}\d*$/;

pseudo.addEventListener('keyup', ()=>{

    

    if (!regex.test(pseudo.value)) {

        pseudo.style.backgroundColor = "red";
    };
    if (regex.test(pseudo.value)) {

        pseudo.style.backgroundColor = "lightGreen";
    }
});



