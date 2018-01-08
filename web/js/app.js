 $(document).ready(function () {
        $('#createUser').submit(function (e) {
            var getUrl = Routing.generate('createUser');
            e.preventDefault();

            $.ajax({
                type: $(this).attr('method'),
                url: getUrl,
                data: $(this).serialize(),
                success: function (response) {
                    console.log("Succes!");
                    location.reload();
                },
                error: function (response) {
                    console.log("Error: " + response);
                }
            });
        });

        $('.deleteUser').click(function (e) {
            var getUrl = Routing.generate('deleteUser', {'id': $(this).attr('id')});
            e.preventDefault();

            $.ajax({
                type: 'delete',
                url:  getUrl,
                data: $(this).serialize(),
                success: function (response) {
                    console.log("Succes!");
                    location.reload();
                },
                error: function (response) {
                    console.log("Error: " + response);
                }
            });
        });
 });