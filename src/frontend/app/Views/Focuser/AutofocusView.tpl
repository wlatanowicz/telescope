
<com:TRouteView Path="/autofocus/step-one">

    <div class="autofocus__settings">
        <div class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <com:TLabel For="FocuserName">
                        Focuser name:
                    </com:TLabel>
                    <com:TTextBox ID="FocuserName" Value="sim" />
                </div>

                <div class="form-group">
                    <com:TLabel For="CameraName">
                        Camera name:
                    </com:TLabel>
                    <com:TTextBox ID="CameraName" Value="sim" />
                </div>

                <div class="form-group">
                    <com:TLabel For="InitialPosition">
                        Initial focuser position:
                    </com:TLabel>
                    <com:TTextBox ID="InitialPosition" Value="3000" />
                </div>

                <div class="form-group">
                    <com:TLabel For="ExposureTime">
                        Exposure time:
                    </com:TLabel>
                    <com:TTextBox ID="ExposureTime" Value="3" />
                </div>
            </div>

            <div class="box-footer">
                <com:TButton on:Click=".capturePreviewClicked" Text="Capture Preview" />
            </div>

            <div class="box-body">
                <div class="form-group">
                    <com:TLabel For="MinPosition">
                        Star position:
                    </com:TLabel>
                    <com:TTextBox ID="ImageX" Value="" />
                    x
                    <com:TTextBox ID="ImageY" Value="" />
                </div>

                <div class="form-group">
                    <com:TLabel For="MinPosition">
                        Measure area radius:
                    </com:TLabel>
                    <com:TTextBox ID="Radius" Value="40" />
                </div>

                <div class="form-group">
                    <com:TLabel For="MinPosition">
                        Min focuser position:
                    </com:TLabel>
                    <com:TTextBox ID="MinPosition" Value="3000" />
                </div>

                <div class="form-group">
                    <com:TLabel For="MaxPosition">
                        Max focuser position:
                    </com:TLabel>
                    <com:TTextBox ID="MaxPosition" Value="4000" />
                </div>

                <div class="form-group">
                    <com:TLabel For="Iterations">
                        Iterations:
                    </com:TLabel>
                    <com:TTextBox ID="Iterations" Value="5" />
                </div>
            </div>

            <div class="box-footer">
                <com:TButton on:Click=".autofocusClicked" Text="Autofocus" />
            </div>

        </div>
    </div>

    <com:Image ID="Image" CssClass="autofocus__image-preview" on:Select=".starSelected" />

</com:TRouteView>
