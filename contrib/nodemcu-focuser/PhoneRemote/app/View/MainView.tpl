<com:AnimatedRouteView Path="/">
    <div class="bar bar-header bar-light">
        <com:Button on:Click=".addBoomarkClicked" CssClass="button button-icon icon ion-android-favorite"></com:Button>
        <h1 class="title">Fouser Remote</h1>
        <a href="#/bookmarks" class="button button-icon icon ion-navicon"></a>
    </div>
    <div class="content has-header has-footer padding">

        <div class="list card">
            <label class="item range">
                <i class="icon ion-speedometer"></i>
                <com:TextBox Disabled="[%= ! this.Connected %]"
                             ID="StepSize"
                             Type="range"
                             Attributes.min="1"
                             Attributes.max="1000"
                             Text="100"
                />
            </label>
            <com:PlaceHolder ID="Position">
                <span class="item">
                    Current position
                    <span class="item-note">
                    [%= this.Position %]
                    </span>
                </span>
                <span class="item">
                    Target position
                    <span class="item-note">
                    [%= this.TargetPosition %]
                    </span>
                </span>
            </com:PlaceHolder>
            <label class="item range">
                <i class="icon ion-arrow-down-a"></i>
                <com:TextBox Disabled="[%= ! this.Connected %]"
                             on:Change=".sliderMoved"
                             ID="PositionSlider"
                             Type="range"
                             Attributes.min="[%= this.Min %]"
                             Attributes.max="[%= this.Max %]"
                />
                <i class="icon ion-arrow-up-a"></i>
            </label>
        </div>

        <com:Button Disabled="[%= ! this.Connected %]"
                    CssClass="button button-block button-positive icon-left ion-arrow-down-a position-button"
                    on:Click=".buttonClicked"
                    Text="Down"
                    CustomData="[%= { Direction: 'down' } %]" />

        <com:Button Disabled="[%= ! this.Connected %]"
                    CssClass="button button-block button-positive icon-left ion-arrow-up-a position-button"
                    on:Click=".buttonClicked"
                    Text="Up"
                    CustomData="[%= { Direction: 'up' } %]" />

    </div>
</com:AnimatedRouteView>

<srv:view.bookmarks />

<div class="bar bar-footer bar-light">
	<div class="title">
		[%= this.IP %]
		<com:PlaceHolder Visible="[%= this.Connected">
			<i class="icon ion-checkmark-circled balanced"></i>
		</com:PlaceHolder>
		<com:PlaceHolder Visible="[%= ! this.Connected">
			<i class="icon ion-close-circled assertive"></i>
		</com:PlaceHolder>
	</div>
</div>
