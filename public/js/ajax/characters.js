function searchCharacter(url, token, searchData, button) {
    "use strict";
    // alert(`url: ${url}, token: ${token}, searchData: ${searchData}`);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: url,
        data: { _token: token, search: searchData.toString() },
        success: function(data) {
            $(button).attr("disabled", false);
            $("#searchLoader").hide();

            let searchVal = $("#searchData .body");
            searchVal.empty();

            if (data.status)
            {
                if (data.count === 0)  {
                    searchVal.html(`<em>Nie znaleziono żadnych użytkowników spełniających podane kryteria.</em>`);
                }
                else {
                    let html = "";
                    html += `Znaleziono <strong>${data.count}</strong> użytkownika(ów).<br /><br />`
                    html += "<table class='table table-striped'><thead>" +
                        "<tr><th>ID</th><th>NICK GRACZA</th><th>UŻYTKOWNIK</th></tr>" +
                        "</thead><tbody>";
                    $.each(data.data, function(index, value) {
                        html += `<tr><td>${value.Id}</td><td><a href="https://admin.lsvrp.ga/characters/${value.Id}">${value.Name} ${value.Lastname}</a></td>` +
                            `<td><a href="https://lsvrp.pl/profile/${value.MemberID}-${value.Globalname}">${value.Globalname}</a></td></tr>`;
                    });
                    html += "</tbody></table>";
                    searchVal.html(html);
                }
            }

            $("#searchData").show();
        }
    });
}