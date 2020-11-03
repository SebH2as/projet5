class Meteo {
    constructor() {
        this.meteo = document.getElementById('meteo');
        this.icone = document.getElementById('icone');
    }

    getMeteoData(data){
        this.meteo.textContent = data.weather[0].description;
        this.icone.src = `http://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`
    }
}