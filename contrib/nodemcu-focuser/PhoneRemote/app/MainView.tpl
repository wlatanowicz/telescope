<div class="bar bar-header bar-light">
	<h1 class="title">Fouser Remote</h1>
</div>
<div class="content has-header has-footer padding">

	<div class="list card">
		<label class="item range">
			<i class="icon ion-speedometer"></i>
			<com:TTextBox Disabled="[%= ! this.Connected %]"
						  ID="StepSize"
						  Type="range"
						  Attributes.min="1"
						  Attributes.max="1000"
						  />
		</label>
		<com:TPlaceHolder ID="Position">
			<a class="item" href="#">
				Current position
				<span class="item-note">
					[%= this.Position %]
				</span>
			</a>
			<a class="item" href="#">
				Target position
				<span class="item-note">
					[%= this.TargetPosition %]
				</span>
			</a>
		</com:TPlaceHolder>
	</div>

	<com:TButton Disabled="[%= ! this.Connected %]"
				 CssClass="button button-block button-positive icon-left ion-arrow-down-a position-button"
				 on:Click=".buttonClicked"
				 Text="Down"
				 CustomData.Direction="down" />

	<com:TButton Disabled="[%= ! this.Connected %]"
				 CssClass="button button-block button-positive icon-left ion-arrow-up-a position-button"
				 on:Click=".buttonClicked"
				 Text="Up"
				 CustomData.Direction="up" />

</div>
<div class="bar bar-footer bar-light">
	<div class="title">
		[%= this.IP %]
		<com:TPlaceHolder Visible="[%= this.Connected">
			<i class="icon ion-checkmark-circled balanced"></i>
		</com:TPlaceHolder>
		<com:TPlaceHolder Visible="[%= ! this.Connected">
			<i class="icon ion-close-circled assertive"></i>
		</com:TPlaceHolder>
	</div>
</div>
