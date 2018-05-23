function loadMainData(pg) {
    $.ajax({
        url: "main.php",
        type: "GET",
        data: "page=" + pg + "&searchParam=" + $('#approach').val() + "&searchValue=" + $('#searchBar').val(),
        dataType: "json",
        success: function (data) {
            if (data !== "Login") {
                $('#persons').empty();

                for (var i = 0; i < data.length; i++) {
                    if (i >= (pg - 1) * 20 && i < (pg - 1) * 20 + 20) {
                        var value = data[i];
                        var row = "<tr onclick='viewInfo(" + value['id'] + ")' id='" + value['id'] + "'><td class='person-id'>" + value['id'] + "</td><td class='name'>" + (value['firstName'] + " " + value['lastName']) + "</td><td class='phone'>" + value['phone'] + "</td><td class='age'>" + value['age'] + "</td><td><a href='#' onclick='deleteUser(" + value['id'] + ")'>Delete</a> | <a href='edituser.html?userid=" + value['id'] + "'>Edit</a></td></tr>";
                        $('#persons').append(row);                           
                    }
                };

                $('#pagination').attr("data-current", parseInt(pg)).attr("data-total", Math.floor(data.length / 20) + 1).bladePagination({
                    maxPageNum: 3,
                    clickPage: function (page) {
                        loadMainData(page);
                    }
                });


                $('#persons tr').css('opacity', '0').each(function (index) {
                  $(this).delay(20 * index).animate({
                    opacity: 1,
                  });
                });
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

function viewInfo(personId) {
    $('#userPhone').empty().append("telephone: " + $('tr#' + personId + " .phone").text());
    $('#userName').empty().append($('tr#' + personId + " .name").text() + ", " + $('tr#' + personId + ' .age').text() + "y");

    $.ajax({
        url: "personInterest/get",
        type: "GET",
        data: "personId=" + personId,
        dataType: "json",
        success: function (data) {
          if (data != "Error") {
            $.each(data, function (key, value) {
              console.log(value);
              $('#userInterests').empty().append("<li>" + value['description'] + "</li>");
            })
          }
        }
      });

    $('#userInfo').modal("show");
}