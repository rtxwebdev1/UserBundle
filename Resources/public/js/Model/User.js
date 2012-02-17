Core.ns("App.User.Model");

App.User.Model.User = Backbone.Model.extend({
    urlRoot: Routing.generate("rtxlabs_userbundle_user_list"),

    initialize: function() {
        this.groups = new App.User.Collection.GroupCollection();
    },

    hasGroup: function(id) {
        var groups = _.find(this.groups, function(group){
            return id == group.id;
        });

        return groups !== undefined;
    },

    defaults: {
        firstname: "",
        lastname: "",
        email: "",
        personnelNumber: "",
        username: "",
        password: "",
        admin: false,
        locale: "de",
        roles: []
    },

    validate: {
        firstname: {
            required: true,
            minlength: 1
        },
        lastname: {
            required: true,
            minlength: 1
        },
        password: {
            required: true,
            minlength:5
        },
        username: {
            required: true,
            minlength: 1
        },
        email: {
            required: true,
            minlength: 1,
            type: "email"
        },
        personnelNumber: {
            pattern: /^[0-9]{4}$/
        }
    }
});