class formSave {
    constructor(form, ...input){

        this.form = document.getElementById(form);
        this.input = input;

        this.saveInputValue();
        this.setInputValue();

    }

    saveInputValue()
    {   
        this.form.addEventListener('submit', ()=>{
            this.input.forEach(element => {
                sessionStorage.setItem(element.id,  document.getElementById(element.id).value);
            });

        })
        
    }

    setInputValue()
    {
        this.input.forEach(element => {
            element.value = sessionStorage.getItem(element.id); 
        });
        sessionStorage.clear();
    }

}

