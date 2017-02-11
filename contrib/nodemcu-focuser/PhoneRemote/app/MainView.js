//= require TTemplateControl
//= require NodeClient

klass( 'MainView', TTemplateControl, {

	getPublicProperties : function() {
		var arr = this.base();
		arr.push({name: "Connected", type: "bool", default: false});
		arr.push({name: "Position", type: "int", default: null});
		arr.push({name: "TargetPosition", type: "int", default: null});
		arr.push({name: "IP", type: "string", default: "192.168.0.51"});
		return arr;
	},

	buttonClicked : function( sender, param ){
        var node = new NodeClient({
            "BaseUrl" : "http://" + this.IP
        });

        if (sender.CustomData.Direction === 'down') {
        	sign = -1;
		} else {
        	sign = +1;
		}

		step = this.$('StepSize').getValue();

        node.post("",{},{
        	"targetPosition": this.TargetPosition + (sign * step)
		})
			.done(function(result){
				this.updateStatus(result);
			}.bind(this));
	},

    refreshFocuserStatus : function() {
		var interval = 500;
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
				console.log('error');
				if (! this.Connected){
	               this.Connected = true;
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
            this.TargetPosition = result.target;

            this.$('Position').render();
        }
	}

} );
