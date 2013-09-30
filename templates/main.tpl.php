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
                <a class="navbar-brand" href="/">Гостевая книга</a>
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
                <?php if (isset($this->data['data']['post']) && count($this->data['data']['post']) > 0): ?>
                    <?php foreach ($this->data['data']['post'] as $post): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading post_head">
                                <span class="post_user_name"><?=isset($post['user_name']) ? $post['user_name'] : ''?></span>
                                <span class="post_user_email"><?=isset($post['user_email']) ? ($post['user_email']) : ''?></span>
                                <span class="glyphicon glyphicon-remove post_delete" onclick="delPost(<?=$post['id']?>)"></span>
                                <span class="post_date"><?=isset($post['date_create']) ? date("j.m.Y H:i:s", $post['date_create']) : ''?></span>
                            </div>
                            <div class="panel-body post_body">
                                <?=isset($post['text']) ? $post['text'] : ''?>
                            </div>
                        </div>
                    <?php endforeach ?>

                    <?php if ($this->data['data']['page']['count_element'] > $this->data['data']['page']['element_per_page']): ?>
                        <center>
                            <ul class='pagination pagination-sm pagination_ajax' style='margin-top: 10px;'>
                            <li onclick=<?= $this->data['data']['page']['javaFunction']."(0)" ?>><a>первая</a></li>
                            <li onclick=<?= $this->data['data']['page']['javaFunction']."(".($this->data['data']['page']['index'] - 1).")" ?>><a>«</a></li>
                            <?php foreach ($this->data['data']['page']['array'] as $value): ?>
                                <?php if ($this->data['data']['page']['index'] == $value): ?>
                                    <li onclick=<?= $this->data['data']['page']['javaFunction']."(".$value.")" ?> class='active'><a><?= $value + 1 ?></a></li>
                                <?php else: ?>
                                    <li onclick=<?= $this->data['data']['page']['javaFunction']."(".$value.")" ?>><a><?= $value + 1 ?></a></li>
                                <?php endif ?>
                            <?php endforeach ?>
                            <li onclick=<?= $this->data['data']['page']['javaFunction']."(".($this->data['data']['page']['index'] + 1).")" ?>><a>»</a></li>
                            <li onclick=<?= $this->data['data']['page']['javaFunction']."(".($this->data['data']['page']['count_page'] - 1).")" ?>><a>последняя</a></li>
                            </ul>
                        </center>
                    <?php endif ?>

                <?php else: ?>
                    <center>Нет записей</center>
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
                        <textarea id="user_text" style="width: 100%;" class="form-control"></textarea>
                    </div>
                    <div class="postFormButtons">
                        <button class="btn btn-primary pull-right" onclick="clearPost()">Очистить</button>
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