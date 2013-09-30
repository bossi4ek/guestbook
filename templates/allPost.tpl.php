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