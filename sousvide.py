from machine import Pin, PWM
import time
from ds18x20 import DS18X20
import onewire
import socket
import PID

def http_get(url):
    _, _, host, path = url.split('/', 3)
    addr = socket.getaddrinfo(host, 80)[0][-1]
    s = socket.socket()
    s.connect(addr)
    s.send(bytes('GET /%s HTTP/1.0\r\nHost: %s\r\n\r\n' % (path, host), 'utf8'))
    while True:
        data = s.recv(100)
        if data:
            #print(str(data, 'utf8'), end='')
            break
        else:
            break
    s.close()

def get_temp():
    sensor.convert_temp()
    try:
        temp = sensor.read_temp(rom)
    except:
        #If sensor read fails, use a sentinel value so control loop continues but the error is obvious on the graph
        #To fail SAFE the sentinel value must be higher than the setpoint, so that the heater is gradually turned off with failed reads
        temp = 123.45
    return temp

# PWM Control
heater = PWM(Pin(4)) #GPIO4 - D2 on ESP8266
heater.freq(1)

# DS18B20 Sensor Setup
ow = onewire.OneWire(Pin(12)) #GPIO12 - D6 on ESP8266
sensor = DS18X20(ow)
rom = sensor.scan()[0]
sensor.convert_temp()

#PID Setup
setpoint = 56.0
PID = PID.PID(220,0.3,2500,1023) #Create simple PID object with saturation value of 1023
sleep_time = 20  #Control loop interval (seconds)

def PID_Loop():
    print('{0:>10}{1:>10}{2:>10}{3:>7}{4:>10}{5:>10}{6:>10}'.format('TEMP', 'ERROR', 'DRIVE', 'DUTY', 'P', 'I', 'D'))
    while True:
        temp = get_temp()
        error = setpoint - temp
        drive = PID.Control_Output(error)
        p = PID.Cp
        i = PID.Ci
        d = PID.Cd
        duty = min(max(int(drive),0),900)
        print('{temp:10.3f}{error:10.3f}{drive:10.2f}{duty:7}{p:10.2f}{i:10.2f}{d:10.2f}'.format(temp=temp, error=error, drive=drive, duty=duty, p=p, i=i, d=d))
        heater.duty(duty)
        url = 'http://yourwebsite.com/PIDStoreZQ3jHPDy85.php?temp={}&error={}&duty={}&p={}&i={}&d={}'.format(str(temp), str(error), str(duty), str(p), str(i), str(d))
        http_get(url)
        time.sleep(sleep_time)
