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
        if(document.URL.indexOf("error") >= 0 ){ 
            this.input.forEach(element => {
                element.value = sessionStorage.getItem(element.id); 
            });
            sessionStorage.clear();
        }

        else if(document.URL.indexOf("message") >= 0){ 
            this.input.forEach(element => {
                element.value = sessionStorage.getItem(element.id); 
            });
            sessionStorage.clear();
        }
        
        else {
            sessionStorage.clear();
        }
       
    }


}

