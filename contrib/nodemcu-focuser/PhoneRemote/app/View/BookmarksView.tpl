<com:RouteView Path="/bookmarks" on:BecameActive=".becameActive">
    <div class="bar bar-header bar-light">
        <a href="#/" class="button button-icon icon ion-chevron-left"></a>
        <h1 class="title">Bookmarks</h1>
    </div>
    <com:TouchScrollView>
        <div class="list">
            <com:Repeater ID="List">
                <temp:Item>
                    <com:LinkButton on:Click="SourceTemplateControl.itemClicked"
                                    CustomData="[%= this.DataItem %]"
                                    CssClass="item item-icon-right item-button-left">
                        <com:Button CssClass="button button-assertive"
                                    on:Click="SourceTemplateControl.removeClicked"
                                    CustomData="[%= this.ItemIndex %]">
                            <i class="icon ion-ios-trash-outline"></i>
                        </com:Button>
                        [%= this.DataItem.name %] ([%= this.DataItem.position %])
                        <i class="icon ion-chevron-right"></i>
                    </com:LinkButton>
                </temp:Item>
                <temp:Empty>
                    <label class="item">
                        <center>- no items -</center>
                    </label>
                </temp:Empty>
            </com:Repeater>
        </div>
    </com:TouchScrollView>
</com:RouteView>
