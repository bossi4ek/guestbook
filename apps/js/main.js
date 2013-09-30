//Отображение всех постов
showAllPost = function() {
    $.ajax({
        url: "index.php",
        global: false,
        type: "POST",
        data: ({
            action: "showAllPostAjax"
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
            if (response == 1) {
                showAllPost();
            }
        }
    });
}

//Удаление поста
delPost = function() {
    alert(1);
}

//Инициализация
$(document).ready(function() {

});