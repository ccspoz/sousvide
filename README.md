# sousvide

A PID controller intended for use in DIY sousvide-cookers, but easily adaptable to other PID tasks and temperature regulation.

This project involves several parts:

* ESP8266 or similar running micropython, with wifi capability
* Temperature sensor and control hardware
* Web server to receive logging data from the microcontroller and display process control variables
