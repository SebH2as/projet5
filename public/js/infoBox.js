class InfoBox{
    constructor(infoBox)
    {
        this.infoBoxes = document.getElementsByClassName(infoBox);
        this.nbInfos = this.infoBoxes.length;

        this.infoBoxReveal();
    }

    infoBoxReveal()
    {
        console.log(this.infoBoxes);
        console.log(this.nbInfos);

        for (let i = 0; i < this.nbInfos; i++) 
        {
            this.infoBoxes[i].parentElement.onmouseover = ()=>{
                this.infoBoxes[i].classList.remove("hidden");
                this.infoBoxes[i].style.opacity = '1';
            }

            this.infoBoxes[i].parentElement.onmouseout = ()=>{
                this.infoBoxes[i].classList.add("hidden");
                this.infoBoxes[i].style.opacity = '0';
            }
        }
    }
}

infobox = new InfoBox('infoBox');