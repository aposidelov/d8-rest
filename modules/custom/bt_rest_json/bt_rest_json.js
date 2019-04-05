(function ($, Drupal) {

    function getCsrfToken(callback) {
        $.get(Drupal.url('rest/session/token'))
         .done(function (data) {
                console.log('dd0', data);
            var csrfToken = data;
            callback(csrfToken);
        });
    }

    function postNode(csrfToken, body, user) {
        console.log('ddd', csrfToken, body, user );
        $.ajax({
            url: "http://my.drupal85.ua/jsonapi/node/article?_format=api_json",
            method: "POST",
            data: JSON.stringify(body),
            headers: {
                "Accept": "application/vnd.api+json",
                "Content-Type": "application/vnd.api+json",
                'X-CSRF-Token': csrfToken,
                "Authorization": "Basic " + btoa(user.name + ':' + user.password)
            },
            success: function (result, status, xhr) {
                console.log('data', result, status);
            }
        });
    }

    Drupal.behaviors.btRestJson = {
        attach: function (context, settings) {
            $('#edit-list').click(function() {
                console.log('list');

                $('#bt-container').html('hello');
                return false;
            });
            $('#edit-add').click(function() {
                console.log('add2');
                var user = { name: '', password: '' };
                user.name = $('#edit-username').val();
                user.password = $('#edit-password').val();
                var entityData = {
                    "data": {
                        "type": "node--article",
                        "attributes": {
                            "title": "My custom selle title",
                            "body": {
                                "value": "Custom value content cont cont ser",
                                "format": "plain_text"
                            }
                        }
                    }
                };
                getCsrfToken(function (csrfToken) {
                    postNode(csrfToken, entityData, user);
                });
                return false;
            });
        }
    };
})(jQuery, Drupal);