function SearchForm(typeField, formatField)
{
    this.typeField = typeField;
    this.formatField = formatField;

    this.loadFormats = function() {
        let url = Routing.generate('app_api_formats_by_types', {id: typeField.val()}, true);
        $.get(url, function(data){
            this.formatField.html('');
            data.forEach(function(format) {
                this.formatField.append('<option value="' + format.id + '">' + format.title + '</option>');
            }.bind(this));
        }.bind(this));
    };

    this.onChangeTypeField = function(e) {
        e.preventDefault();
        this.loadFormats();
    };

    this.listenEvents = function() {
        this.loadFormats();
        this.typeField.change(this.onChangeTypeField.bind(this));
    };

    this.listenEvents();
}

$(function() {
    new SearchForm($('#search_media_type'), $('#search_media_format'));
});
