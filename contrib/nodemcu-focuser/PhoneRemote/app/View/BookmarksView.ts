import TemplateControl from "@framework/TemplateControl";
import FocuserInterface from "@app/Client/FocuserInterface";
import Bookmark from "@app/Repository/Bookmark";

import template from  "./BookmarksView.tpl";

export default class BookmarksView extends TemplateControl
{
    template = template;

    private repository : Bookmark;
    private client: FocuserInterface;

    constructor(client: FocuserInterface, repository: Bookmark)
    {
        super();
        this.repository = repository;
        this.client = client;
    }

    becameActive()
    {
        this.$('List').DataSource = this.repository.findAll();
        this.$('List').render();
    }

    itemClicked(sender)
    {
        this.client.setPosition(sender.CustomData.position);
        window.location.hash = "#/";
    }

    removeClicked(sender, param)
    {
        param.domEvent.preventDefault();
        param.domEvent.stopPropagation();
        let index = sender.CustomData;
        this.repository.removeBookmark(index);

        this.$('List').DataSource = this.repository.findAll();
        this.$('List').render();
    }
}