import ByConstructor from "@framework/DependencyInjection/Definition/ByConstructor";
import ByName from "@framework/DependencyInjection/Definition/ByName";
import ByValue from "@framework/DependencyInjection/Definition/ByValue";

import Node from "@app/Client/Node";
import Settings from "@app/Repository/Settings";
import MainView from "@app/View/MainView";
import Dummy from "@app/Client/Dummy";
import BookmarksView from "@app/View/BookmarksView";
import Bookmark from "@app/Repository/Bookmark";

export default {
    "client.node" : new ByConstructor(
        Node,
        [
            new ByName("repository.settings"),
        ]
    ),
    "client.dummy" : new ByConstructor(
        Dummy
    ),
    "repository.settings" : new ByConstructor(
        Settings,
        [
            new ByValue("192.168.0.51"),
        ]
    ),
    "repository.bookmarks" : new ByConstructor(
        Bookmark
    ),
    "view.main" : new ByConstructor(
        MainView,
        [
            new ByName("client.node"),
            new ByName("repository.settings"),
            new ByName("repository.bookmarks"),
        ]
    ),
    "view.bookmarks" : new ByConstructor(
        BookmarksView,
        [
            new ByName("client.dummy"),
            new ByName("repository.bookmarks"),
        ]
    )
}