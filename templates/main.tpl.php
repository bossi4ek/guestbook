<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//RU" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Гостевуха</title>
    <script type="text/javascript" src="/apps/plugins/jquery/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="/apps/plugins/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="/apps/js/main.js"></script>
    <link rel="stylesheet" href="/apps/plugins/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/apps/css/main.css">
</head>

<body>
    <div id="content_header" class="content_header">
        <nav class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">Гостенвая книга</a>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="/">Главня</a></li>
                    <li><a href="#">Мои сообщения</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <div id="content_main">
        <div>
            <div><label>Все сообщения</label></div>
            <div id="allPost" class="postBox">
                <?php if (isset($this->data['post'])): ?>
                    <?php foreach ($this->data['post'] as $post): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <span class="post_user_name"><?=isset($post['user_name']) ? $post['user_name'] : ''?></span>
                                <span class="post_user_email"><?=isset($post['user_email']) ? ($post['user_email']) : ''?></span>
                                <span class="post_date"><?=isset($post['date_create']) ? date("j.m.Y H:i:s", $post['date_create']) : ''?></span>
                            </div>
                            <div class="panel-body">
                                <?=isset($post['text']) ? $post['text'] : ''?>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    Нет записей
                <?php endif ?>
            </div>
            <div class="postForm">
                <label>Добавить сообщение</label>
                <div class="postFormBox">
                    <div class="postFormFields">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                            <input type="text" id="user_name" class="form-control" placeholder="Username">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="text" id="user_email" class="form-control" placeholder="Email">
                        </div>
<!--                        <label>Текст сообщение</label>-->
                        <textarea id="user_text" style="width: 100%;" class="form-control"></textarea>
                    </div>
                    <div class="postFormButtons">
                        <button class="btn btn-primary pull-right">Отмена</button>
                        <button class="btn btn-primary pull-right" style="margin-right: 10px;" onclick="addPost();">Отправить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="content_footer">
    </div>
</body>

</html>