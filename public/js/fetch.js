class Fetch {

    async getCurrent(input) {

        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?q=${input}&lang=fr&units=metric&appid=0ccdd3f1ecc146fe09dbc7e4416b201b`
        );

        const data = await response.json();

        return data;

    }

}

