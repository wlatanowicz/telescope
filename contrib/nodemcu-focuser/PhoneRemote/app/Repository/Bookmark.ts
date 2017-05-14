export default class Bookmark
{
    private LS_KEY = "bookmarks";

    addBookmark(name: string, position: number)
    {
        let list = this.loadList();
        list.push({
            "name" : name,
            "position" : position
        });
        this.saveList(list);
    }

    removeBookmark(position: number)
    {
        let list = this.loadList();
        list = list.filter(function(v, index){
            return index != position
        });
        this.saveList(list);
    }

    findAll()
    {
        return this.loadList();
    }

    private loadList(): any[]
    {
        try {
            let list = JSON.parse(localStorage.getItem(this.LS_KEY))
            return list !== null ? list : [];
        } catch (ex) {}
        return [];
    }

    private saveList(list: any[])
    {
        localStorage.setItem(
            this.LS_KEY,
            JSON.stringify(list)
        );
    }
}