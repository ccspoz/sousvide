import time

class PID:
    def __init__(self, Kp=0, Ki=0, Kd=0, sat=1023):
        #Initialise gains
        self.Kp = Kp
        self.Ki = Ki
        self.Kd = Kd
        self.sat = sat

        #Initialise delta variables
        self.cur_time = time.time()
        self.prev_time = self.cur_time
        self.prev_error = 0

        #Result variables (absolute coefficients, after gains applied)
        self.Cp = 0
        self.Ci = 0
        self.Cd = 0
        self.output = 0

    def SetKp(self, Kp):
        self.Kp = Kp
    def SetKi(self, Ki):
        self.Ki = Ki
    def SetKd(self, Kd):
        self.Kd = Kd

    #Set saturation value, outside of which output will be clipped
    def SetSat(self, sat):
        self.sat = sat

    #Clip the output to be inside the saturation range
    def Clip(self,value):
        return min(max(value,-self.sat),self.sat)

    #Calculate another interation of the PID Loop
    def Control_Output(self, error):
        self.cur_time = time.time()
        dt = self.cur_time - self.prev_time
        de = error - self.prev_error

        self.Cp = self.Clip(error * self.Kp)

        if dt > 0:
            self.Cd = self.Clip(self.Kd*de/dt)
        else:
            self.Cd = 0

        self.output = self.Clip(self.Cp + self.Ci + self.Cd)

        #Use integral term only if output is within controllable region to prevent integral windup
        if self.output < self.sat and self.output > -self.sat:
            self.Ci += self.Ki*error*dt
            #Because we assume only passive cooling, we also want to prevent any negative integral terms
            if self.Ci < 0:
                self.Ci = 0
            self.output = self.Clip(self.Cp + self.Ci + self.Cd)

        self.prev_time = self.cur_time
        self.prev_error = error

        return self.output
