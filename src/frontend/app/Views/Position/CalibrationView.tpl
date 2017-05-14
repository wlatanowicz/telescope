<com:RouteView Path="/position/calibration">
    <div class="form-horizontal">
        <div class="box-body" style="display: inline-block; width: 30%;">
            <div class="form-group">
                <com:Label ForControl="RaShift">
                    RA calibration shift:
                </com:Label>
                <com:TextBox ID="RaShift" Text="0.25" />
            </div>
            <div class="form-group">
                <com:Label ForControl="DecShift">
                    DEC calibration shift:
                </com:Label>
                <com:TextBox ID="DecShift" Text="0.25" />
            </div>
            <com:Button Text="Start" on:Click=".startCalibrationClicked" />
        </div>
        <div class="box-body" style="display: inline-block; width: 30%;">
            <div class="form-group">
                <com:Label ForControl="TelescopeName">
                    Telescope name:
                </com:Label>
                <com:TextBox ID="TelescopeName" Text="sim" />
            </div>
            <div class="form-group">
                <com:Label ForControl="CameraName">
                    Camera name:
                </com:Label>
                <com:TextBox ID="CameraName" Text="sim-fast" />
            </div>
            <div class="form-group">
                <com:Label ForControl="ExposeTime">
                    Expose time:
                </com:Label>
                <com:TextBox ID="ExposeTime" Text="5" />
            </div>
        </div>
        <div class="box-body" style="display: inline-block; width: 30%;">
            <div class="form-group">
                <com:Label ForControl="DiffAngle">
                    Angle Diff:
                </com:Label>
                <com:TextBox ID="DiffAngle" Disabled="true" />
            </div>
            <div class="form-group">
                <com:Label ForControl="DiffShift">
                    Shift Ratio:
                </com:Label>
                <com:TextBox ID="DiffShift" Disabled="true" />
            </div>
            <com:Button Text="Store" on:Click=".storeClicked" />
        </div>
    </div>

    <com:Image ID="PrimaryImage" CssClass="autofocus__image-preview" on:Select=".starSelected" />

    <com:Image ID="SecondaryImage" CssClass="autofocus__image-preview" on:Select=".starSelected" />

</com:RouteView>