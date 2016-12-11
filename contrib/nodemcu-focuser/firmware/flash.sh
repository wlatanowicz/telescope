
export PORT=/dev/tty.wchusbserial1410

./esptool.py --port $PORT erase_flash
./esptool.py --port $PORT write_flash -fm dio 0x00000 nodemcu-master-10-modules-2016-12-04-18-10-49-integer.bin
