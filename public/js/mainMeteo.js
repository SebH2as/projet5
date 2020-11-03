const ftch = new Fetch();
const meteo = new Meteo();

ftch.getCurrent('Nantes').then((data) => {
    meteo.getMeteoData(data);
});