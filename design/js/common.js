function deletePerson(id) {
    $.ajax({
        url: "person/delete",
        type: "GET",
        data: "id=" + id,
        dataType: "json",
        success: function (data) {
            if (data === "Пользователь удалён успешно") {
                $('tr#' + id).remove();
            }
        }
    })
}

function loadPersons() {
    $.ajax({
        url: "main.php",
        type: "GET",
        data: "page=" + $('#pageNumber').text(),
        dataType: "json",
        success: function (data) {
          if (data === "Пожалуйста войдите в учётную запись")
          {
            window.location.href = window.location.href.substring(0, window.location.href.lastIndexOf('/') + 1) + "LoginPage.html";
          } else
          {
            var html = "";
  
            $.each(data, function (key, value) {
              html += "<tr id='" + value['id'] + "'><td class='id'>" + value['id'] + "</td><td class='name'>" + value['firstName'] + " " + value['lastName'] + "</td><td class='phone'>" + value['phone'] + "</td><td class='age'>" + value['age'] + "</td><td><a href='#'>Edit</a> | <a href='#' onclick='deletePerson(" + value['id'] + ")'>Delete</td></tr>";
            });
  
            $('#persons').html(html);
          }
        }
      });
}

function previousPage() {
    $('#pageNumber').text((parseInt($('#pageNumber').text()) - 1) + "");
    loadPersons();
    if ($('#pageNumber').text() === "1") {
        $('#lastPageRef').prop('disabled', 'true');
    }
}

function nextPage() {
    $('#pageNumber').text((parseInt($('#pageNumber').text()) + 1) + "");
    loadPersons();
    $('#lastPageRef').removeAttr('disabled');
}

function searchPerson() {
    var approach = $("#approach").val();
    var value = $('#searchBar').val();

    $.ajax({
        url: "person/search",
        type: "GET",
        data: approach + "=" + value,
        dataType: "json",
        success: function (data) {
            var html = "";
  
            $.each(data, function (key, value) {
              html += "<tr id='" + value['id'] + "'><td class='id'>" + value['id'] + "</td><td class='name'>" + value['firstName'] + " " + value['lastName'] + "</td><td class='phone'>" + value['phone'] + "</td><td class='age'>" + value['age'] + "</td><td><a href='#'>Edit</a> | <a href='#' onclick='deletePerson(" + value['id'] + ")'>Delete</td></tr>";
            });
  
            $('#persons').html(html);
        }
    })
}