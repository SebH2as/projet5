class NavBar{
    constructor (navBar) 
    {
      this.navBar = document.querySelector(navBar);

      this.stickyBar();
    }

    stickyBar()
    { 
      let current = location.href;  
              console.log(current);
      window.addEventListener("scroll", ()=>{
        let nav = this.navBar;
        nav.classList.toggle("sticky", window.scrollY > 0);
      });
    }

}

navBar = new NavBar('#navBar');









