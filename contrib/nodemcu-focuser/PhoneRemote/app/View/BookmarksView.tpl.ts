/*{"source":"/htdocs/radio-telescope/contrib/nodemcu-focuser/PhoneRemote/app/View/BookmarksView.tpl","md5":"7572a5442a8231cad9b08df678dc658b","mtime":"2017-05-10T20:07:43.000Z","date":"2017-05-10T20:07:44.275Z"}*/
import Expression from "/htdocs/radio-telescope/contrib/nodemcu-focuser/PhoneRemote/framework/Expression";
import Content from "/htdocs/radio-telescope/contrib/nodemcu-focuser/PhoneRemote/framework/Content";
import RouteView from "/htdocs/radio-telescope/contrib/nodemcu-focuser/PhoneRemote/framework/RouteView";
import TouchScrollView from "/htdocs/radio-telescope/contrib/nodemcu-focuser/PhoneRemote/framework/WebControls/Mobile/TouchScrollView";
import Repeater from "/htdocs/radio-telescope/contrib/nodemcu-focuser/PhoneRemote/framework/Repeater";
import LinkButton from "/htdocs/radio-telescope/contrib/nodemcu-focuser/PhoneRemote/framework/WebControls/FormControls/LinkButton";
import Button from "/htdocs/radio-telescope/contrib/nodemcu-focuser/PhoneRemote/framework/WebControls/FormControls/Button";

export default function template()
{
	var ExpressionContext = this;
	var SourceTemplateControl = this;
	var c502 = new Content();
	this.addTemplateChildControl( "c502", c502 );
	c502.renderTemplateChildControls = function( placeholder ){
		this._templateControls["c503"].renderContentsInPlaceholder( placeholder );
	};
	var c503 = new RouteView();
	c503.Path = "/bookmarks";
	c502.addTemplateChildControl( "c503", c503 );
	c503.event.attach( "BecameActive", SourceTemplateControl.becameActive.bind( SourceTemplateControl ) );
	c503.renderTemplateChildControls = function( placeholder ){
		var h_c504 = document.createElement( "div" );
		h_c504.setAttribute( "class", "bar bar-header bar-light" );
		var h_c505 = document.createElement( "a" );
		h_c505.setAttribute( "href", "#/" );
		h_c505.setAttribute( "class", "button button-icon icon ion-chevron-left" );
		h_c504.appendChild( h_c505 );
		var h_c506 = document.createElement( "h1" );
		h_c506.setAttribute( "class", "title" );
		var t_c507 = document.createTextNode( "Bookmarks" );
		h_c506.appendChild( t_c507 );
		h_c504.appendChild( h_c506 );
		placeholder.appendChild( h_c504 );
		this._templateControls["c508"].renderContentsInPlaceholder( placeholder );
	};
	var c508 = new TouchScrollView();
	c503.addTemplateChildControl( "c508", c508 );
	c508.renderTemplateChildControls = function( placeholder ){
		var h_c509 = document.createElement( "div" );
		h_c509.setAttribute( "class", "list" );
		this._templateControls["c510"].renderContentsInPlaceholder( h_c509 );
		placeholder.appendChild( h_c509 );
	};
	var c510 = new Repeater();
	c510.ID = "List";
	c508.addTemplateChildControl( "c510", c510 );
	c510.ItemTemplate = function( item ){
		var ExpressionContext = item;
		var c512 = new Content();
		item.addTemplateChildControl( "c512", c512 );
		c512.renderTemplateChildControls = function( placeholder ){
			this._templateControls["c513"].renderContentsInPlaceholder( placeholder );
		};
		var c513 = new LinkButton();
		c513.CustomData = ( new Expression( function(){ return ( this.DataItem ); }.bind( ExpressionContext ) ) );
		c513.CssClass = "item item-icon-right item-button-left";
		c512.addTemplateChildControl( "c513", c513 );
		c513.event.attach( "Click", SourceTemplateControl.itemClicked.bind( SourceTemplateControl ) );
		c513.renderTemplateChildControls = function( placeholder ){
			this._templateControls["c514"].renderContentsInPlaceholder( placeholder );
			var t_c515 = document.createTextNode( "\n                        "+( new Expression( function(){ return ( this.DataItem.name ); }.bind( ExpressionContext ) ) )+" ("+( new Expression( function(){ return ( this.DataItem.position ); }.bind( ExpressionContext ) ) )+")\n                        " );
			placeholder.appendChild( t_c515 );
			var h_c516 = document.createElement( "i" );
			h_c516.setAttribute( "class", "icon ion-chevron-right" );
			placeholder.appendChild( h_c516 );
		};
		var c514 = new Button();
		c514.CssClass = "button button-assertive";
		c514.CustomData = ( new Expression( function(){ return ( this.ItemIndex ); }.bind( ExpressionContext ) ) );
		c513.addTemplateChildControl( "c514", c514 );
		c514.event.attach( "Click", SourceTemplateControl.removeClicked.bind( SourceTemplateControl ) );
		c514.renderTemplateChildControls = function( placeholder ){
			var h_c517 = document.createElement( "i" );
			h_c517.setAttribute( "class", "icon ion-ios-trash-outline" );
			placeholder.appendChild( h_c517 );
		};
	};
	c510.EmptyTemplate = function( item ){
		var ExpressionContext = item;
		var c519 = new Content();
		item.addTemplateChildControl( "c519", c519 );
		c519.renderTemplateChildControls = function( placeholder ){
			var h_c520 = document.createElement( "label" );
			h_c520.setAttribute( "class", "item" );
			var h_c521 = document.createElement( "center" );
			var t_c522 = document.createTextNode( "- no items -" );
			h_c521.appendChild( t_c522 );
			h_c520.appendChild( h_c521 );
			placeholder.appendChild( h_c520 );
		};
	};
}
