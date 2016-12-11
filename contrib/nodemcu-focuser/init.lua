--configuration: BEGIN

--wifi
wifiSSID = "flux2g";
wifiPassword = "1qazxsw23edc";

wifiDHCP = false;
wifiIP = "192.168.0.51";
wifiMask = "255.255.255.0"
wifiGateway = "192.168.0.1"

--motor pins
pin1 = 0;
pin2 = 1;
pin3 = 2;
pin4 = 3;

--set -1 for reverse direction
inverter = -1;

-- num of powered cycles before rotating shaft
motorWarmUp = 5;

-- cycle speed in miliseconds
idleDelay = 500;
runDelay = 105;

-- this value is substracted from runDelay
maxSpeed = 100;

-- maxSpeed is reached after this many cycles
flatOutDistance = 20;

steps = {0x03,0x06,0x0c,0x09}

--configuration:END

--status variables:
motorOn = 0;
position = 0;
targetPosition = 0;
startPosition = 0;

--init network:

wifi.setmode(wifi.STATION);
wifi.sta.config(wifiSSID, wifiPassword);

if (not wifiDHCP) then
    wifi.sta.setip({
        ip = wifiIP,
        netmask = wifiMask,
        gateway = wifiGateway
    });
end

if ( wifi.sta.getip() ~= nil ) then
    print("IP: " .. wifi.sta.getip())
else
    print("IP: not set yet")
end

--init outputs:
gpio.mode(pin1, gpio.OUTPUT)
gpio.mode(pin2, gpio.OUTPUT)
gpio.mode(pin3, gpio.OUTPUT)
gpio.mode(pin4, gpio.OUTPUT)

--close server if there is already a one
if srv~=nil then
  srv:close()
end


function signedtonumber(value, base)
  local result=tonumber(value, base)
  if string.sub(value,1,1)=="-" then
    if result>0 then
      result=-result
    end
  end

  return result
end

function driveMotor()
  local step=steps[((inverter * position) % 4) + 1];

  if ( motorOn > 0 and bit.band(step, 0x01) > 0 ) then
    gpio.write(pin1, gpio.HIGH);
  else
    gpio.write(pin1, gpio.LOW);
  end

  if ( motorOn > 0 and bit.band(step, 0x02) > 0 ) then
    gpio.write(pin2, gpio.HIGH);
  else
    gpio.write(pin2, gpio.LOW);
  end

  if ( motorOn > 0 and bit.band(step, 0x04) > 0 ) then
    gpio.write(pin3, gpio.HIGH);
  else
    gpio.write(pin3, gpio.LOW);
  end

  if ( motorOn > 0 and bit.band(step, 0x08) > 0 ) then
    gpio.write(pin4, gpio.HIGH);
  else
    gpio.write(pin4, gpio.LOW);
  end
end

function setSpeed(speed)
    if (speed > maxSpeed) then
        speed = maxSpeed;
    end
    if (speed < 0) then
        speed = 0;
    end
    if (speed == 0) then
        tmr.interval(0, idleDelay);
    else
        tmr.interval(0, runDelay - speed);
    end
end

function setSpeedByPosition()
    local distance = math.min( math.abs(startPosition - position), math.abs(targetPosition - position) )

    local speed = maxSpeed;

    if (distance < flatOutDistance )
    then
        speed = ( maxSpeed * distance ) / flatOutDistance;
    end

    speed = math.floor(speed);
       
    setSpeed(speed);
end

function processRequest(client, request)
    local _, _, method, path, vars = string.find(request, "([A-Z]+) (.+)?(.+) HTTP");
    if(method == nil)then
        _, _, method, path = string.find(request, "([A-Z]+) (.+) HTTP");
    end

    local _, _, headers, bodyStr = string.find(request, "(.+)\n\r(.*)");

    --read json from POST/PATCH body
    local body = {}
    if ( bodyStr ~= nil ) then
        if (string.len(bodyStr) > 4 ) then
            body = (cjson.decode(bodyStr));
        end
    end

    --read GET params (not used at the moment)
    local get = {}
    if (vars ~= nil)then
        for k, v in string.gmatch(vars, "(%w+)=([-%w]+)&*") do
            _GET[k] = v
        end
    end

    print( "Request:" )
    print( "METHOD: " .. method )
    print( "JSON: " .. cjson.encode(body) )
    print( "GET: " .. cjson.encode(get) )

    if (method == "POST")then
        if (body.targetPosition == nil) then
            error("body.targetPosition has to be number")
        end
        startPosition = position;
        targetPosition = signedtonumber(body.targetPosition, 10);
    end

    if (method == "PATCH")then
        if (body.position == nil) then
            error("body.position has to be number")
        end
        position = signedtonumber(body.position, 10);
        targetPosition = position;
    end

    if (method == "DELETE")then
        position = 0;
        targetPosition = 0;
    end

    client:send(cjson.encode({
        position = position,
        target = targetPosition,
        result = "OK"
    }));
    client:close();
end

--init server
srv = net.createServer(net.TCP);
srv:listen(80,function(conn)
    conn:on("receive", function(client,request)
        local status, err = pcall(processRequest, client, request);
        if ( not status ) then
            print( "ERROR processing request" );
            client:send(cjson.encode({
                code = err,
                result = "ERROR"
            }));
            client:close();
        end
        collectgarbage();
    end);
end);

--main loop
tmr.register(0, 100, tmr.ALARM_AUTO, function ()
    
    if ( position ~= targetPosition ) then
        setSpeedByPosition();
        if ( motorOn < motorWarmUp ) then
          motorOn = motorOn + 1;
        end
    else
        if ( motorOn > 0 ) then
          motorOn = motorOn - 1;
        end
        setSpeed(0);
    end

    if (motorOn >= motorWarmUp) then
      if ( position > targetPosition ) then
          position = position - 1;
      end
      if ( position < targetPosition ) then
          position = position + 1;
      end
    end
    
    driveMotor();

end);
tmr.start(0);

