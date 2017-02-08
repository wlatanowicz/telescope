
<com:TRouteView Path="/autofocus/step-one">
    <com:TButton on:Click=".capturePreviewClicked" Text="Capture Preview" />
</com:TRouteView>

<com:TRouteView Path="/autofocus/step-two">

    <com:Image ID="Image" Style="width: 100%; height: 100%; border: 1px solid #ccc; margin-bottom: 10px;" />

    <com:TButton on:Click=".buttonClicked" Text="Load" />

</com:TRouteView>
