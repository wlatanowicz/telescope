<com:TRouteView Path="/position/calibration">
    <div class="form-horizontal">
        <div class="box-body">
            <div class="form-group">
                <com:TLabel ForControl="RaShift">
                    RA calibration shift:
                </com:TLabel>
                <com:TTextBox ID="RaShift" Text="0.25" />
            </div>
            <div class="form-group">
                <com:TLabel ForControl="DecShift">
                    DEC calibration shift:
                </com:TLabel>
                <com:TTextBox ID="DecShift" Text="0.25" />
            </div>
            <com:TButton Text="Start" on:Click=".startCalibrationClicked" />
        </div>
        <div class="box-body">
            <div class="form-group">
                <com:TLabel ForControl="TelescopeName">
                    Telescope name:
                </com:TLabel>
                <com:TTextBox ID="TelescopeName" Text="sim" />
            </div>
            <div class="form-group">
                <com:TLabel ForControl="CameraName">
                    Camera name:
                </com:TLabel>
                <com:TTextBox ID="CameraName" Text="sim-fast" />
            </div>
            <div class="form-group">
                <com:TLabel ForControl="ExposeTime">
                    Expose time:
                </com:TLabel>
                <com:TTextBox ID="ExposeTime" Text="5" />
            </div>
        </div>
    </div>

    <com:Image ID="PrimaryImage" CssClass="autofocus__image-preview" />

    <com:Image ID="SecondaryImage" CssClass="autofocus__image-preview" />

</com:TRouteView>