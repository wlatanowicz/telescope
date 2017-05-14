import ByConstructor from "@framework/DependencyInjection/Definition/ByConstructor";
import RPC from "@app/Client/Common/RPC";
import Http from "@framework/Data/Http";
import Camera from "@app/Client/Camera";
import ByName from "@framework/DependencyInjection/Definition/ByName";
import Telescope from "@app/Client/Telescope";
import Focuser from "@app/Client/Focuser";
import PositionCalibrationRegistry from "@app/Registry/PositionCalibrationRegistry";
import CalibrationView from "@app/Views/Position/CalibrationView";
import AutofocusView from "@app/Views/Focuser/AutofocusView";
import PositionCalibrationFactory from "@app/Factory/PositionCalibrationFactory";
import ByValue from "@framework/DependencyInjection/Definition/ByValue";

export default
{
    "client.rpc": new ByConstructor(
        RPC,
        [
            new ByConstructor(Http),
            new ByValue("http://localhost/radio-telescope/index.php")
        ]
    ),

    "client.camera": new ByConstructor(
        Camera,
        [
            new ByName("client.rpc")
        ]
    ),

    "client.telescope": new ByConstructor(
        Telescope,
        [
            new ByName("client.rpc")
        ]
    ),

    "client.focuser": new ByConstructor(
        Focuser,
        [
            new ByName("client.rpc")
        ]
    ),

    "registry.position_calibration": new ByConstructor(
        PositionCalibrationRegistry
    ),

    "view.focuser.autofocus": new ByConstructor(
        AutofocusView,
        [
            new ByName("client.focuser"),
            new ByName("client.camera"),
        ]
    ),

    "view.position.calibration": new ByConstructor(
        CalibrationView,
        [
            new ByName("registry.position_calibration"),
            new ByName("factory.position_calibration"),
            new ByName("client.camera"),
            new ByName("client.telescope"),
        ]
    ),

    "factory.position_calibration": new ByConstructor(
        PositionCalibrationFactory
    ),
};