from flask import Flask, render_template, request
from datetime import datetime
import requests

app = Flask(__name__)

API_KEY = "49c0bad2c7458f1c76bec9654081a661"  # Usa tu propio API Key

@app.route('/', methods=['GET', 'POST'])
def weather():
    status = None
    msg = ""
    city = ""

    if request.method == 'POST':
        city = request.form['city']
        url = f"http://api.openweathermap.org/data/2.5/weather?q={city}&appid={API_KEY}"
        
        # Hacemos la solicitud a la API
        response = requests.get(url)
        result = response.json()

        # Si el código de respuesta es 200, el API encontró la ciudad
        if result['cod'] == 200:
            status = "yes"
            # Convertimos la fecha de Unix timestamp a formato legible
            result['date'] = datetime.utcfromtimestamp(result['dt']).strftime('%d %b')
            
            # Convertir temperatura a Celsius y formatear con 2 decimales (NO realizamos más cálculos en el template)
            temp_celsius = result['main']['temp'] - 273.15
            result['main']['temp'] = "{:.2f}".format(temp_celsius)
            
            # Redondear la velocidad del viento a 2 decimales
            result['wind']['speed'] = "{:.2f}".format(result['wind']['speed'])
        else:
            msg = result.get('message', 'Error al obtener los datos')

    return render_template('weather.html', status=status, result=result if status == "yes" else None, msg=msg, city=city)

if __name__ == '__main__':
    app.run(debug=True)
