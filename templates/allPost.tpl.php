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