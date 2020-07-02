class Sum {
    constructor (rubric) {
        this.rubric = document.getElementById(rubric);
        this.nbImgs = rubric.children.length;
        this.imgs = rubric.children;
        this.height = 100/this.nbImgs;
        
        this.heightOfImgs();
       
    }

    heightOfImgs()
    {
        let i;
        for (i = 0; i < this.nbImgs; i++) {
            this.imgs[i].style.height = this.height + "%";
        }
    }
}

chroniquesImgs = new Sum(chronicImgs);
chroniquesTexts = new Sum(chronicsText);
essaisImgs = new Sum(essaisImgs);
fictionsImgs = new Sum(fictionsImgs);