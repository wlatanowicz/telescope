
<com:RouteView Path="/autofocus/step-one">

    <div class="autofocus__settings">
        <div class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <com:Label ForControl="FocuserName">
                        Focuser name:
                    </com:Label>
                    <com:TextBox ID="FocuserName" Value="sim" />
                </div>

                <div class="form-group">
                    <com:Label ForControl="CameraName">
                        Camera name:
                    </com:Label>
                    <com:TextBox ID="CameraName" Value="sim" />
                </div>

                <div class="form-group">
                    <com:Label ForControl="InitialPosition">
                        Initial focuser position:
                    </com:Label>
                    <com:TextBox ID="InitialPosition" Value="3000" />
                </div>

                <div class="form-group">
                    <com:Label ForControl="ExposureTime">
                        Exposure time:
                    </com:Label>
                    <com:TextBox ID="ExposureTime" Value="3" />
                </div>
            </div>

            <div class="box-footer">
                <com:Button on:Click=".capturePreviewClicked" Text="Capture Preview" />
            </div>

            <div class="box-body">
                <div class="form-group">
                    <com:Label ForControl="MinPosition">
                        Star position:
                    </com:Label>
                    <com:TextBox ID="ImageX" Value="" />
                    x
                    <com:TextBox ID="ImageY" Value="" />
                </div>

                <div class="form-group">
                    <com:Label ForControl="MinPosition">
                        Measure area radius:
                    </com:Label>
                    <com:TextBox ID="Radius" Value="40" />
                </div>

                <div class="form-group">
                    <com:Label ForControl="MinPosition">
                        Min focuser position:
                    </com:Label>
                    <com:TextBox ID="MinPosition" Value="3000" />
                </div>

                <div class="form-group">
                    <com:Label ForControl="MaxPosition">
                        Max focuser position:
                    </com:Label>
                    <com:TextBox ID="MaxPosition" Value="4000" />
                </div>

                <div class="form-group">
                    <com:Label ForControl="Iterations">
                        Iterations:
                    </com:Label>
                    <com:TextBox ID="Iterations" Value="5" />
                </div>
            </div>

            <div class="box-footer">
                <com:Button on:Click=".autofocusClicked" Text="Autofocus" />
            </div>

        </div>
    </div>

    <com:Image ID="Image" CssClass="autofocus__image-preview" on:Select=".starSelected" />

</com:RouteView>
