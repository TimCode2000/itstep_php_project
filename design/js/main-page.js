function loadMainData() {
    $.ajax({
        url: "main.php",
        type: "GET",
        data: {page: $('#pageNumber').text()},
        dataType: "json",
        success: function (data) {
            if (data !== "Login") {
                $('#persons').empty();

                $.each(data, function (key, value) {
                    var row = "<tr id='" + value['id'] + "'><td>" + value['id'] + "</td><td>" + (value['firstName'] + " " + value['lastName']) + "</td><td>" + value['phone'] + "</td><td>" + value['age'] + "</td><td><a href='person/delete?id=" + value['id'] + "'>Delete</a> | <a href='#'>Edit</a></td></tr>";
                    $('#persons').append(row);
                })
            } else {
                window.location.href = window.location.href.substring(0, window.location.href.indexOf('/main.html') + 1);
            }
        }
    })
}

function nextPage() {
    var newPageNumber = parseInt($('#pageNumber').text()) + 1;
    $('#pageNumber').text(newPageNumber);
    loadMainData();
    $('#lastPageRef').removeAttr("disabled");
}

function previousPage() {
    if (!$('#lastPageRef')[0].hasAttribute("disabled")) {
        var newPageNumber = parseInt($('#pageNumber').text()) - 1;
        $('#pageNumber').text(newPageNumber);
        loadMainData();

        if (newPageNumber === 1) {
            $('#lastPageRef').prop("disabled", true);
        }
    }
}

function searchPerson() {
    console.log($('#approach').val());
    console.log($('#searchBar').val());

    $.ajax({
        url: "person/search",
        type: "GET",
        data: $('#approach').val() + "=" + $('#searchBar').val(),
        dataType: "json",
        success: function (data) {
            if (data !== "Login") {
                $('#persons').empty();

                $.each(data, function (kew, value) {
                    var row = "<tr id='" + value['id'] + "'><td>" + value['id'] + "</td><td>" + (value['firstName'] + " " + value['lastName']) + "</td><td>" + value['phone'] + "</td><td>" + value['age'] + "</td><td><a href='person/delete?id=" + value['id'] + "'>Delete</a> | <a href='#'>Edit</a></td></tr>";
                    $('#persons').append(row);
                });

                $('#approach').val("fullName");
                $('#searchBar').val("");
            }
        }
    })
}