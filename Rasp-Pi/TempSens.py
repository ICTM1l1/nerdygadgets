import sense_hat
from time import sleep
import requests

sense = sense_hat.SenseHat()
apiKey = "55ed7846125b1aa3abc20c2c430133cc17e9a61a9f2b906dc15ee7c0179eacdc4b102927ea9e1e84c359d35e7dfe0a4fade8e6de5ba8c5c0c072fc98a293e473"

while True:
    temperature = sense.get_temperature()
    req = requests.post("localhost/NerdyGadgets/temperatureupdate.php", {"ApiKey" : apiKey, "Temperature" : temperature})
    sleep(3)