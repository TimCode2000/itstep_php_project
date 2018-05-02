function loadMainData(pg) {
    $.ajax({
        url: "main.php",
        type: "GET",
        data: "page=" + pg,
        dataType: "json",
        success: function (data) {
            if (data !== "Login") {
                $('#persons').empty();

                $.each(data, function (key, value) {
                    var row = "<tr id='" + value['id'] + "'><td>" + value['id'] + "</td><td>" + (value['firstName'] + " " + value['lastName']) + "</td><td>" + value['phone'] + "</td><td>" + value['age'] + "</td><td><a href='#' onclick='deleteUser(" + value['id'] + ")'>Delete</a> | <a href='edituser.html?userid=" + value['id'] + "'>Edit</a></td></tr>";
                    $('#persons').append(row);
                })

                $('#pagination').attr("data-current", parseInt(pg)).bladePagination({
                    maxPageNum: 3,
                    clickPage: function (page) {
                        loadMainData(page);
                    }
                });
            } else {
                window.location.href = window.location.href.substring(0, window.location.href.indexOf('/main.html') + 1);
            }
        }
    })
}

function searchPerson() {
    $.ajax({
        url: "person/search",
        type: "GET",
        data: $('#approach').val() + "=" + $('#searchBar').val(),
        dataType: "json",
        success: function (data) {
            if (data !== "Login") {
                $('#persons').empty();

                $.each(data, function (kew, value) {
                    if (value['id'] !== null) {
                        var row = "<tr id='" + value['id'] + "'><td>" + value['id'] + "</td><td>" + (value['firstName'] + " " + value['lastName']) + "</td><td>" + value['phone'] + "</td><td>" + value['age'] + "</td><td><a href='#' onclick='deleteUser(" + value['id'] + ")'>Delete</a> | <a href='edituser.html?userid=" + value['id'] + "'>Edit</a></td></tr>";
                        $('#persons').append(row);
                    }
                });

                $('#approach').val("fullName");
                $('#searchBar').val("");
            }
        }
    })
}

function deleteUser(id) {
    $.ajax({
        url: "person/delete",
        type: "GET",
        data: "id=" + id,
        dataType: "json",
        success: function (data) {
            if (data !== "Error") {
                loadMainData($('#pagination').attr("data-current"));
            }
        }
    })
}