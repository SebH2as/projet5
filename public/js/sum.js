class Sum {
    constructor (rubImgs, rubTxts) {
        this.nbImgs = rubImgs.children.length;
        this.imgs = rubImgs.children;
        this.height = 100/this.nbImgs;
        this.arrayImgs = Array.from(this.imgs);

        this.nbTxts = rubTxts.children.length;
        this.txts = rubTxts.children;
        this.arrayTxts = Array.from(this.txts);
        
        this.heightOfImgs();
        
        
        this.linkingArrays();

    }

    heightOfImgs()
    {
        for (let i = 0; i < this.nbImgs; i++) 
        {
            this.imgs[i].style.height = this.height + "%";
            
        }
       
    }

    linkingArrays()
    {
        let arrayTxts = this.arrayTxts;
        
        for (let i = 0; i < this.nbImgs; i++) 
        {
            this.imgs[i].onclick = ()=>{
                
                let textIndex = i + 1;
                arrayTxts[textIndex].style.opacity = "1";

                for (let j =0; j < this.nbTxts; j++)
                {
                    if(j !== textIndex)
                    {
                        arrayTxts[j].style.opacity = "0";

                    }
                }
                
                /*let imgIndex = i ;
                this.arrayImgs[imgIndex].style.opacity = ".5";

                for (let j =0; j < this.nbImgs; j++)
                {
                    if(j !== imgIndex)
                    {
                        this.arrayImgs[j].style.opacity = "1";

                    }
                }*/
                
            }
        }
        
    }

}

chroniquesImgs = new Sum(chronicImgs, chronicsText);
essaisImgs = new Sum(essaisImgs,essaisText);
fictionsImgs = new Sum(fictionsImgs, fictionsText);