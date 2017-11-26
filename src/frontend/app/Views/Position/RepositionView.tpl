<com:RouteView Path="/position/reposition" on:BecameActive=".activated">
    <div class="form-horizontal">
        <div class="box-body" style="margin-left: 20px; display: inline-block; width: 30%;">
            <div class="form-group">
                <com:Label ForControl="DiffAngle">
                    Cal Angle Diff:
                </com:Label>
                <com:TextBox ID="DiffAngle" Disabled="true" />
            </div>
            <div class="form-group">
                <com:Label ForControl="DiffShift">
                    Cal Shift Ratio:
                </com:Label>
                <com:TextBox ID="DiffShift" Disabled="true" />
            </div>
        </div>
        <div class="box-body" style="display: inline-block; width: 30%;">
            <div class="form-group">
                <com:Label ForControl="TelescopeName">
                    Telescope name:
                </com:Label>
                <def:component.selector.telescope ID="TelescopeName" />
            </div>
            <div class="form-group">
                <com:Label ForControl="CameraName">
                    Camera name:
                </com:Label>
                <def:component.selector.camera ID="CameraName" />
            </div>
            <div class="form-group">
                <com:Label ForControl="ExposeTime">
                    Expose time:
                </com:Label>
                <com:TextBox ID="ExposeTime" Text="5" />
            </div>
            <com:Button Text="Expose" on:Click=".exposeClicked" />
        </div>
        <div class="box-body" style="display: inline-block; width: 30%;">
            <div class="form-group">
                <com:Label ForControl="RaShift">
                    RA shift:
                </com:Label>
                <com:TextBox ID="RaShift" Disabled="true" />
            </div>
            <div class="form-group">
                <com:Label ForControl="DecShift">
                    DEC shift:
                </com:Label>
                <com:TextBox ID="DecShift" Disabled="true" />
            </div>
            <com:Button Text="Reposition" on:Click=".scopeRepositionClicked" />
        </div>
    </div>

    <com:SelectImagePanel ID="PrimaryImage" CssClass="autofocus__image-preview" on:Select=".starSelected" />

</com:RouteView>