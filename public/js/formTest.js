class formTest {
    constructor(form){

        this.form = document.getElementById(form);
        this.pseudo =  document.getElementById('pseudo');
        this.mail =  document.getElementById('mail');
        this.mail2 =  document.getElementById('mail2');
        this.password =  document.getElementById('password');
        this.password2 =  document.getElementById('password2');
        this.check = document.getElementById('check');

        this.pseudoErr = document.querySelector('.pseudoErr');
        this.mailErr = document.querySelector('.mailErr');
        this.passErr = document.querySelector('.passErr');
        this.submitErr = document.querySelector('.submitErr');

        this.error = true;
        
        this.testPseudo();
        this.testMail();
        this.testMail2();
        this.testPassword();
        this.testPassword2();
        this.submitForm();
    }

    testPseudo(){
       
        let regex = /^[A-Za-z]{3,15}\d*$/;

        this.pseudo.addEventListener('keyup', ()=>{

            if (!regex.test(this.pseudo.value)) {

                this.pseudo.style.backgroundColor = "rgb(255, 67, 67)";
                this.pseudoErr.classList.remove("none");
                this.pseudoErr.innerHTML = "Le pseudo choisi ne correspond aux critères demandés dans la note d\'information du champ Pseudo.";
            };

            if (regex.test(this.pseudo.value)) {

                this.pseudo.style.backgroundColor = "lightGreen";
                this.pseudoErr.classList.add("none");
            };
            
            if (this.pseudo.value === "" || this.pseudo.value === null) {

                this.pseudo.style.backgroundColor = "transparent";
                this.pseudoErr.classList.add("none");
                
            }
        });
            
    }

    testMail(){
       
        let regex = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

        this.mail.addEventListener('keyup', ()=>{

            if (!regex.test(this.mail.value)) {

                this.mail.style.backgroundColor = "rgb(255, 67, 67)";
                this.mailErr.classList.remove("none");
                this.mailErr.innerHTML = "Le mail choisi n'est pas valide.";
            };

            if (regex.test(this.mail.value)) {

                this.mail.style.backgroundColor = "lightGreen";
                this.mailErr.classList.add("none");
            };
            
            if (this.mail.value === "" || this.mail.value === null) {

                this.mail.style.backgroundColor = "transparent";
                this.mailErr.classList.add("none");
            }
        });
            
    }

    testMail2(){

        this.mail2.addEventListener('keyup', ()=>{

            if (this.mail.value !== this.mail2.value) {

                this.mail2.style.backgroundColor = "rgb(255, 67, 67)";
                this.mailErr.classList.remove("none");
                this.mailErr.innerHTML = "Les Emails saisis ne correspondent pas.";
            };

            if (this.mail.value === this.mail2.value) {

                this.mail2.style.backgroundColor = "lightGreen";
                this.mailErr.classList.add("none");
            };
            
            if (this.mail2.value === "" || this.mail2.value === null) {

                this.mail2.style.backgroundColor = "transparent";
                this.mailErr.classList.add("none");
            }
        });
            
    }

    testPassword(){
       
        let regex = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50}/;

        this.password.addEventListener('keyup', ()=>{

            if (!regex.test(this.password.value)) {

                this.password.style.backgroundColor = "rgb(255, 67, 67)";
                this.passErr.classList.remove("none");
                this.passErr.innerHTML = "Le mot de passe choisi ne correspond aux critères demandés dans la note d\'information du champ Mot de passe.";

            };

            if (regex.test(this.password.value)) {

                this.password.style.backgroundColor = "lightGreen";
                this.passErr.classList.add("none");
            };
            
            if (this.password.value === "" || this.password.value === null) {

                this.password.style.backgroundColor = "transparent";
                this.passErr.classList.add("none");
            }
        });
            
    }

    testPassword2(){
       
        this.password2.addEventListener('keyup', ()=>{

            if (this.password.value !== this.password2.value) {

                this.password2.style.backgroundColor = "rgb(255, 67, 67)";
                this.passErr.classList.remove("none");
                this.passErr.innerHTML = "Les Emails saisis ne correspondent pas.";
            };

            if (this.password.value === this.password2.value) {

                this.password2.style.backgroundColor = "lightGreen";
                this.passErr.classList.add("none");
            };
            
            if (this.password2.value === "" || this.password2.value === null) {

                this.password2.style.backgroundColor = "transparent";
                this.passErr.classList.add("none");
            }
        });
            
    }

    submitForm(){
       
        let error = false;
            
                this.form.addEventListener('submit', event => {
                    
                    if(this.password2.value === "" || this.password2.value === null || 
                    this.password.value === "" || this.password.value === null ||
                    this.mail2.value === "" || this.mail2.value === null ||
                    this.mail.value === "" || this.mail.value === null ||
                    this.pseudo.value === "" || this.pseudo.value === null){
                    
                        event.preventDefault();
                        this.submitErr.classList.remove("none");
                        this.submitErr.innerHTML = "Au moins un des champs est vide, veuillez tous les remplir.";
                    }

                    if(this.pseudo.style.backgroundColor === "rgb(255, 67, 67)" ||
                    this.mail.style.backgroundColor === "rgb(255, 67, 67)" ||
                    this.mail2.style.backgroundColor === "rgb(255, 67, 67)" ||
                    this.password.style.backgroundColor === "rgb(255, 67, 67)" ||
                    this.password2.style.backgroundColor === "rgb(255, 67, 67)"){
                    
                        event.preventDefault();
                        this.submitErr.classList.remove("none");
                        this.submitErr.innerHTML = "Au moins un des champs renseignés ne correspond pas aux critères demandés.";
                    }
            });
        
    }

};

formTestJoin = new formTest("formSignIn");




