import TemplateControl from "@framework/TemplateControl";
import template from "./MainView.tpl";
import Settings from "@app/Repository/Settings";
import FocuserInterface from "@app/Client/FocuserInterface";
import Bookmark from "@app/Repository/Bookmark";

export default class MainView extends TemplateControl
{
    template = template;

    private nodeClient: FocuserInterface;
    private settings: Settings;
    private bookmarks: Bookmark;

    private Min: number = 0;
    private Max: number = 15000;
    private Connected: boolean = false;

    private get Position(): number
    {
        return this.nodeClient.Position;
    }

    private get TargetPosition(): number
    {
        return this.nodeClient.TargetPosition;
    }

    constructor(nodeClient: FocuserInterface, settings: Settings, bookmarks: Bookmark)
    {
        super();
        this.nodeClient = nodeClient;
        this.settings = settings;
        this.bookmarks = bookmarks;
    }

    get IP():string
    {
        return this.settings.IP;
    }

    buttonClicked( sender, param ){
	    var sign = 1;
        if (sender.CustomData.Direction === 'down') {
        	sign = -1;
		} else {
        	sign = +1;
		}

		var step = this.$('StepSize').Value;

        let targetPosition = this.TargetPosition + (sign * step);

        if (targetPosition < this.Min) {
        	targetPosition = this.Min;
		}

        if (targetPosition > this.Max) {
        	targetPosition = this.Max;
		}

        this.requestNewPosition(targetPosition);

		this.$('PositionSlider').Text = this.TargetPosition;
        this.$('Position').render();
	}

	requestNewPosition(targetPosition) {
        this.nodeClient.setPosition(targetPosition)
            .done(function(result){
                this.updateStatus(result);
            }.bind(this));
	}

    refreshFocuserStatus() {
		var interval = 1000;

		this.nodeClient.getPosition()
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
	}

	updateStatus(result) {
        if (! this.Connected){
            this.Connected = true;
            this.render();
        }

        this.$('PositionSlider').Text = this.TargetPosition;
        this.$('Position').render();
	}

	sliderMoved(sender) {
		let targetPosition = this.converters.int(sender.Text);
        this.requestNewPosition(targetPosition);
        this.$('Position').render();
	}

	addBoomarkClicked()
    {
        let bookmarkName = window.prompt("Enter bookmark name", "");
        if (bookmarkName !== null) {
            this.bookmarks.addBookmark(
                bookmarkName,
                this.Position
            );
        }
    }

} 
