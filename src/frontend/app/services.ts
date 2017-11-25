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
import ResourceSelector from "@app/Components/ResourceSelector";

import parameters from "@app/parameters";
import PreviewView from "@app/Views/Camera/PreviewView";
import CaptureView from "@app/Views/Camera/CaptureView";

export default
{
    "client.http": new ByConstructor(
        Http,
        [
            new ByValue(parameters.server_url)
        ]
    ),

    "client.rpc": new ByConstructor(
        RPC,
        [
            new ByName("client.http"),
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

    "view.camera.preview": new ByConstructor(
        PreviewView,
        [
            new ByName("client.camera"),
        ]
    ),

    "view.camera.capture": new ByConstructor(
        CaptureView,
        [
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

    "component.selector.camera": new ByConstructor(
        ResourceSelector,
        [
            new ByName("client.http"),
            new ByValue("cameras")
        ]
    ),

    "component.selector.telescope": new ByConstructor(
        ResourceSelector,
        [
            new ByName("client.http"),
            new ByValue("telescopes")
        ]
    ),

    "component.selector.focuser": new ByConstructor(
        ResourceSelector,
        [
            new ByName("client.http"),
            new ByValue("focusers")
        ]
    ),

    "component.selector.measure": new ByConstructor(
        ResourceSelector,
        [
            new ByName("client.http"),
            new ByValue("measures")
        ]
    ),

    "component.selector.autofocus": new ByConstructor(
        ResourceSelector,
        [
            new ByName("client.http"),
            new ByValue("autofocuses")
        ]
    ),
};
