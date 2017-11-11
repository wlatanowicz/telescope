
<com:RouteView Path="/autofocus/step-one">

    <div class="autofocus__settings">
        <div class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <com:Label ForControl="FocuserName">
                        Focuser name:
                    </com:Label>
                    <def:component.selector.focuser ID="FocuserName" />

                    <com:Label ForControl="CameraName">
                        Camera name:
                    </com:Label>
                    <def:component.selector.camera ID="CameraName" />
                </div>

                <div class="form-group">
                    <com:Label ForControl="AutofocusName">
                        Autofocus name:
                    </com:Label>
                    <def:component.selector.autofocus ID="AutofocusName" />

                    <com:Label ForControl="MeasureName">
                        Measure name:
                    </com:Label>
                    <def:component.selector.measure ID="MeasureName" />
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
                    <com:TextBox ID="Radius" Value="40" on:Change="SourceTemplateControl.radiusChanged" />
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
                <div class="form-group">
                    <com:Label ForControl="Partials">
                        Partials:
                    </com:Label>
                    <com:TextBox ID="Partials" Value="7" />
                </div>
                <div class="form-group">
                    <com:Label ForControl="Tries">
                        Tries:
                    </com:Label>
                    <com:TextBox ID="Tries" Value="1" />
                </div>
            </div>

            <div class="box-footer">
                <com:Button on:Click=".autofocusClicked" Text="Autofocus" />
            </div>

        </div>
    </div>

    <com:SelectImagePanel ID="Image" CssClass="autofocus__image-preview" on:Select=".starSelected" />

</com:RouteView>
