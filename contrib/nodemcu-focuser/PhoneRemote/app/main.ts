import ServiceContainer from "@framework/DependencyInjection/ServiceContainer";

import services from "@app/services";

ServiceContainer.batchDefine(services);

window.location.hash = "#/";

var app = ServiceContainer.get("view.main");
app.render();
app.refreshFocuserStatus();