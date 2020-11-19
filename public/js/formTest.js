class formTest {
    constructor(form){

        this.form = document.getElementById(form);
        this.pseudo =  document.getElementById(form);
        this.mail =  document.getElementById(form);
        this.password =  document.getElementById(form);
        
        this.testPseudo();
        this.testMail();
        this.testPassword();
    }

    testPseudo(){
       
        let regex = /^[A-Za-z]{3,15}\d*$/;

        pseudo.addEventListener('keyup', ()=>{

            if (!regex.test(pseudo.value)) {

                pseudo.style.backgroundColor = "rgb(255, 67, 67)";
            };

            if (regex.test(pseudo.value)) {

                pseudo.style.backgroundColor = "lightGreen";
            };
            
            if (pseudo.value === "" || pseudo.value === null) {

                pseudo.style.backgroundColor = "transparent";
            }
        });
            
    }

    testMail(){
       
        let regex = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

        mail.addEventListener('keyup', ()=>{

            if (!regex.test(mail.value)) {

                mail.style.backgroundColor = "rgb(255, 67, 67)";
            };

            if (regex.test(mail.value)) {

                mail.style.backgroundColor = "lightGreen";
            };
            
            if (mail.value === "" || mail.value === null) {

                mail.style.backgroundColor = "transparent";
            }
        });
            
    }

    testPassword(){
       
        let regex = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50}/;

        password.addEventListener('keyup', ()=>{

            if (!regex.test(password.value)) {

                password.style.backgroundColor = "rgb(255, 67, 67)";
            };

            if (regex.test(password.value)) {

                password.style.backgroundColor = "lightGreen";
            };
            
            if (password.value === "" || password.value === null) {

                password.style.backgroundColor = "transparent";
            }
        });
            
    }

};

formTestJoin = new formTest("formSignIn");




