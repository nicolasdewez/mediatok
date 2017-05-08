function MediaForm(typeField, formatField, fieldsBlock)
{
    this.typeField = typeField;
    this.formatField = formatField;
    this.fieldsBlock = fieldsBlock;

    this.loadFormats = function() {
        let url = Routing.generate('app_api_formats_by_types', {id: typeField.val()}, true);
        $.get(url, function(data){
            this.formatField.html('');
            data.forEach(function(format) {
                this.formatField.append('<option value="' + format.id + '">' + format.title + '</option>');
            }.bind(this));
        }.bind(this));
    };

    this.loadAdditionalFields = function() {
        let url = Routing.generate('app_api_fields_by_types', {id: typeField.val()}, true);

        $.get(url, function(data){
            this.fieldsBlock.html('');
            data.forEach(function(field) {
                this.fieldsBlock.append(
                    '<div class="form-group">' +
                    '<label class="control-label" for="media_field_' + field.id + '">' + field.title + '</label>' +
                    '<input type="text" id="media_field_' + field.id + '" class="form-control" name="media[field_' + field.id + ']" />' +
                    '</div>'
                );
            }.bind(this));
        }.bind(this));
    };

    //
    this.onChangeTypeField = function(e) {
        e.preventDefault();
        this.loadFormats();
        this.loadAdditionalFields();
    };

    this.listenEvents = function() {
        // Load formats and  fields only if new media. If edit Symfony form do initialize.
        if ($('#is_edit').val() !== '1') {
            this.loadFormats();
            this.loadAdditionalFields();
        }

        this.typeField.change(this.onChangeTypeField.bind(this));
    };

    this.listenEvents();
}

$(function() {
    new MediaForm($('#media_type'), $('#media_format'), $('#fields'));
});
