
<com:RouteView Path="/camera/preview">

    <div class="autofocus__settings">
        <div class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <com:Label ForControl="CameraName">
                        Camera name:
                    </com:Label>
                    <def:component.selector.camera ID="CameraName" />
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

        </div>
    </div>

    <com:ImagePanel ID="Image" CssClass="autofocus__image-preview" />

</com:RouteView>
