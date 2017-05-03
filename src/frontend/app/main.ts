import MainView from "@app/MainView";
import registerServices from "@app/services";

registerServices();

var c = new MainView();
c.Placeholder = 'container';
c.render();

window['c'] = c;