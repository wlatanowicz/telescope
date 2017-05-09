import MainView from "@app/MainView";
import ServiceContainer from "@framework/DependencyInjection/ServiceContainer";

import services from "@app/services";

ServiceContainer.batchDefine(services);

var c = new MainView();
c.Placeholder = 'container';
c.render();

window['c'] = c;