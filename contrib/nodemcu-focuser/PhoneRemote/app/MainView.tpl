<div class="bar bar-header bar-light">
	<h1 class="title">Fouser Remote</h1>
</div>
<div class="content has-header has-footer padding">

	<com:TButton Disabled="[%= ! this.Connected %]"
				 CssClass="button button-block button-positive icon-left ion-arrow-up-a"
				 on:Click=".buttonClicked"
				 Text="Up"
				 CustomData.Direction="up" />

	<div class="list card">
		<label class="item item-input item-select">
			<div class="input-label">
				Step size
			</div>
			<com:TDropDownList Disabled="[%= ! this.Connected %]"
							   ID="StepSize">
				<com:TOption Text="1" Value="1" />
				<com:TOption Text="10" Value="10" />
				<com:TOption Text="100" Value="100" />
				<com:TOption Text="1000" Value="1000" />
			</com:TDropDownList>
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
				 CssClass="button button-block button-positive icon-left ion-arrow-down-a"
				 on:Click=".buttonClicked"
				 Text="Down"
				 CustomData.Direction="down" />

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
