import DropDownList from "@framework/WebControls/FormControls/DropDownList";
import TextBox from "@framework/WebControls/FormControls/TextBox";
import Http from "@framework/Data/Http";

export default class ResourceSelector extends DropDownList
{
    private http: Http;

    private resourceKind: string;

    constructor(http: Http, resourceKind: string)
    {
        super();
        this.http = http;
        this.resourceKind = resourceKind;

        this.ValueFieldName = "value";
        this.TextFieldName = "text";

        this.http.get("/hardware/available/" + this.resourceKind, {}).done((response)=>{
            let rendered = this._renderedMainElement;
            let value = this.SelectedValue;
            this.DataSource = response.map((e) => {return {value: e, text: e};});
            if (rendered) {
                this.render();
            }
            if (!value) {
                this.http.get("/hardware/available/" + this.resourceKind + "/default",{}).done((response)=>{
                    if (response) {
                        this.SelectedValue = response;
                    }
                })
            }
        });
    }
}