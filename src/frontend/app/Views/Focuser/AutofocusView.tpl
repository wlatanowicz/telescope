
<com:TRouteView Path="/autofocus/step-one">

    Initial focuser position:
    <com:TTextBox ID="InitialPosition" Value="3000" />

    Focuser name:
    <com:TTextBox ID="FocuserName" Value="sim" />

    Camera name:
    <com:TTextBox ID="CameraName" Value="sim" />

    <com:TButton on:Click=".capturePreviewClicked" Text="Capture Preview" />

    <com:Image ID="Image" Style="width: 100%; height: 500px; border: 1px solid #ccc; margin-bottom: 10px;" />

    <com:TButton on:Click=".buttonClicked" Text="Load" />

</com:TRouteView>
