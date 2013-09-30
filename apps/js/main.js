//Отображение всех постов
showAllPost = function(page) {
    $.ajax({
        url: "index.php",
        global: false,
        type: "POST",
        data: ({
            action: "showAllPostAjax",
            page:   page == undefined ? 0 : page
        }),
        dataType: "html",
        success: function(response){
            $("#allPost").html(response);
        }
    });
}

//Добавление поста
addPost = function() {
    $.ajax({
        url: "index.php",
        global: false,
        type: "POST",
        data: ({
            action: "addPost",
            name:   $("#user_name").val(),
            email:  $("#user_email").val(),
            text:   $("#user_text").val()
        }),
        dataType: "html",
        success: function(response){
            var msg = "Ошибка добавления сообщения";
            var style = "error";
            if (response == 1) {
                showAllPost();
                msg = "Сообщение успешно добавлено";
                style = "success";
            }
            showBottomAlert(msg, style);
        }
    });
}

//Очистка полей ввода
clearPost = function() {
    $("#user_name").val('');
    $("#user_email").val('');
    $("#user_text").val('');
}

//Удаление поста
delPost = function(id) {
    $.ajax({
        url: "index.php",
        global: false,
        type: "POST",
        data: ({
            action: "delPost",
            id:     id
        }),
        dataType: "html",
        success: function(response){
            var msg = "Ошибка удаления сообщения";
            var style = "error";
            if (response == 1) {
                showAllPost(getCurrentPage());
                msg = "Сообщение успешно удалено";
                style = "success";
            }
            showBottomAlert(msg, style);
        }
    });
}

//==============================================================================
//Отображение затухающего алерта внизу страницы
//style(success, error, warning, info)
showBottomAlert = function(text, style, fade_out) {
    //Скорость затухания
    fade_out = (fade_out == undefined) ? 10000 : fade_out;
    style = style == "error" ? "danger" : style
    style = "alert-" + style;

    if ($("#bottom_alert").length) {
        $("#bottom_alert").remove();
    }
    $("#content_main").prepend("<div id=\"bottom_alert\" class=\"alert\"></div>");
    $("#bottom_alert").show();
    $("#bottom_alert").addClass(style).html(text);
    $("#bottom_alert").fadeOut(fade_out, function() {
        $("#bottom_alert").remove();
    });
};

getCurrentPage = function() {
    return $(".pagination_ajax").find(".active a").text() - 1;
}

//Инициализация
$(document).ready(function() {

});