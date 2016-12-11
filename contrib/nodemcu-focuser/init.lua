wifi.setmode(wifi.STATION)
wifi.sta.config("flux2g","1qazxsw23edc")

print(wifi.sta.getip())
pin1 = 0
pin2 = 1
pin3 = 2
pin4 = 3

position = 0
targetPosition = 0
startPosition = 0

gpio.mode(pin1, gpio.OUTPUT)
gpio.mode(pin2, gpio.OUTPUT)
gpio.mode(pin3, gpio.OUTPUT)
gpio.mode(pin4, gpio.OUTPUT)

if srv~=nil then
  srv:close()
end

steps = {0x03,0x06,0x0c,0x09}

function signedtonumber(value, base)
  local result=tonumber(value, base)
  if string.sub(value,1,1)=="-" then
    if result>0 then
      result=-result
    end
  end

  return result
end

function driveMotor(pos, motoOn)
  local step=steps[(pos%4)+1];

  if ( motoOn > 0 and bit.band(step, 0x01) > 0 ) then
    gpio.write(pin1, gpio.HIGH)
  else
    gpio.write(pin1, gpio.LOW)
  end

  if ( motoOn > 0 and bit.band(step, 0x02) > 0 ) then
    gpio.write(pin2, gpio.HIGH)
  else
    gpio.write(pin2, gpio.LOW)
  end

  if ( motoOn > 0 and bit.band(step, 0x04) > 0 ) then
    gpio.write(pin3, gpio.HIGH)
  else
    gpio.write(pin3, gpio.LOW)
  end

  if ( motoOn > 0 and bit.band(step, 0x08) > 0 ) then
    gpio.write(pin4, gpio.HIGH)
  else
    gpio.write(pin4, gpio.LOW)
  end
end

function setSpeed(speed)
    if (speed > 100) then
        speed = 100;
    end
    if (speed < 0) then
        speed = 0;
    end
    if (speed == 0) then
        tmr.interval(0, 500);
    else
        tmr.interval(0, 105 - speed);
    end
end

function setSpeedByPosition()

    local maxSpeed = 100
    local flatOutDistance = 20

    local distance = math.min( math.abs(startPosition - position), math.abs(targetPosition - position) )

    local speed = maxSpeed

    if (distance < flatOutDistance )
    then
        speed = ( maxSpeed * distance ) / flatOutDistance;
    end

    speed = math.floor(speed)
       
    setSpeed(speed)
end

srv=net.createServer(net.TCP)
srv:listen(80,function(conn)
    conn:on("receive", function(client,request)
        local buf = "";
        local _, _, method, path, vars = string.find(request, "([A-Z]+) (.+)?(.+) HTTP");
        if(method == nil)then
            _, _, method, path = string.find(request, "([A-Z]+) (.+) HTTP");
        end
        local _GET = {}
        if (vars ~= nil)then
            for k, v in string.gmatch(vars, "(%w+)=([-%w]+)&*") do
                _GET[k] = v
            end
        end

        if (method == "POST" and _GET.position ~= nil)then
            startPosition = position;
            targetPosition = signedtonumber(_GET.position, 10);
        end

        if (method == "PATCH" and _GET.position ~= nil)then
            position = signedtonumber(_GET.position, 10);
        end

        if (method == "DELETE" and _GET.position ~= nil)then
            position = 0;
        end

        buf = cjson.encode({
            position = position,
            target = targetPosition
        })

        client:send(buf);
        client:close();
        collectgarbage();
    end)
end)

tmr.register(0, 100, tmr.ALARM_AUTO, function ()
    local motorOn
    
    if ( position ~= targetPosition ) then
        setSpeedByPosition();
        motorOn = 1
    else
        setSpeed(0);
        motorOn = 0
    end

    if ( position > targetPosition ) then
        position = position - 1
    end
    if ( position < targetPosition ) then
        position = position + 1
    end

    driveMotor(position, motorOn)

end)
tmr.start(0)

