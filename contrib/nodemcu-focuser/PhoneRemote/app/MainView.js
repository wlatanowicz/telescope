//= require TTemplateControl
//= require NodeClient

klass( 'MainView', TTemplateControl, {

	getPublicProperties : function() {
		var arr = this.base();
        arr.push({name: "Min", type: "int", default: 0});
        arr.push({name: "Max", type: "int", default: 10000});
        arr.push({name: "Connected", type: "bool", default: false});
        arr.push({name: "Position", type: "int", default: null});
        arr.push({name: "TargetPosition", type: "int", default: null});
        arr.push({name: "IP", type: "string", default: "192.168.0.51"});
		return arr;
	},

	buttonClicked : function( sender, param ){
	    var sign = 1;
        if (sender.CustomData.Direction === 'down') {
        	sign = -1;
		} else {
        	sign = +1;
		}

		var step = this.$('StepSize').getValue();

        this.TargetPosition = this.TargetPosition + (sign * step);

        if (this.TargetPosition < this.Min) {
        	this.TargetPosition = this.Min;
		}

        if (this.TargetPosition > this.Max) {
        	this.TargetPosition = this.Max;
		}

		this.$('PositionSlider').Text = this.TargetPosition;
        this.$('Position').render();
        this.requestNewPosition();
	},

	requestNewPosition : function() {
        var node = new NodeClient({
            "BaseUrl" : "http://" + this.IP
        });
        node.post("",{},{
            "targetPosition": this.TargetPosition
        })
            .done(function(result){
                this.updateStatus(result);
            }.bind(this));
	},

    refreshFocuserStatus : function() {
		var interval = 1000;
		var node = new NodeClient({
				"BaseUrl" : "http://" + this.IP
			});

		node.get("")
			.done(function(result){
				this.updateStatus(result);
                setTimeout(
                	this.refreshFocuserStatus.bind(this),
					interval
				);
			}.bind(this))
			.error(function() {
				if (this.Connected){
	               this.Connected = false;
	               this.render();
                }

                setTimeout(
                    this.refreshFocuserStatus.bind(this),
                    interval
                );
            }.bind(this));
	},

	updateStatus : function(result) {
        if (! this.Connected){
            this.Connected = true;
            this.render();
        }

        if (result.position != this.Position
            || result.target != this.TargetPosition) {

            this.Position = result.position;

            if (this._TargetPosition === null) {
                this.TargetPosition = result.target;
            }
        }
        
        this.$('PositionSlider').Text = this.TargetPosition;
        this.$('Position').render();
	},

	sliderMoved : function(sender) {
		this.TargetPosition = sender.Text;
        this.$('Position').render();
        this.requestNewPosition();
	}

} );
