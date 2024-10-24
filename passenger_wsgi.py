import sys
import os
from app import app as application  # Asegúrate de que 'app' coincida con el nombre de tu archivo Flask

# Agrega el directorio de tu aplicación a la variable de entorno de Python
sys.path.insert(0, os.path.dirname(__file__))