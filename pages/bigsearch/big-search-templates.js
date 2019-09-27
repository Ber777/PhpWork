$(document).ready(function() {
    Handlebars.registerHelper("switch", function(value, options) {
        this._switch_value_ = value;
        var html = options.fn(this); // Process the body of the switch block
        delete this._switch_value_;
        return html;
    });

    Handlebars.registerHelper("case", function(value, options) {
        if (value == this._switch_value_) {
            return options.fn(this);
        }
    });
    var templateId = parseInt($('#search-template-id').text());
    if (templateId) {
        $('#form0').detach();
        var source   = $("#search-template-source").html();
        var template = Handlebars.compile(source);
        var json = JSON.parse($('#search-template-content').text());
        var context = {data: json};
        var html    = template(context);
        $('.groups-manage-aligment').after(html);
        
        if(json.length > 1) {
            $('.descriptors-form .content-actions').each(function() {
                $(this).append('<p class="delete-group content-action click-button">' + 'Удалить группу</p>');
            });
        }
        $(".delete-group").each(function() {
           $(this).on("click", deleteForm); 
        });
        JsonData = JSON.parse($('#search-template-content').text());
        
        $(".remove").on("click", deleteDescriptor);
        
        $('form').each(function() {

              $(this).find('.content-actions .add-tag:last').on("click", {
                id: "#modal-tags-add",
                name: $(this).attr('id')
              }, showWindow);
              $(this).find('.content-actions .add-attr:last').on("click", {
                id: "#modal-attr-add",
                name: $(this).attr('id')
              }, showWindow);
              $(this).find('.content-actions .add-not-tag:last').on("click", {
                id: "#modal-not-tags-add",
                name: $(this).attr('id')
              }, showWindow);
              $(this).find('.content-actions .check:last').on("click", {
                id: "#edit-group",
                name: $(this).attr('id'),
                edit: false
              }, showWindow);
              $(this).find('.content-actions .edit-group:last').on("click", {
                id: "#edit-group",
                name: $(this).attr('id'),
                edit: true
              }, showWindow);
              $(".delete-group:last").on("click", deleteForm);
        });
    }
});