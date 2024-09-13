<?php
/**
 *碎碎念
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need('header.php'); 

$db = Typecho_Db::get();

$memoriesTable = $db->getPrefix() . 'memories';
if (!$db->fetchRow($db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='{$memoriesTable}';"))) {
    $createTable = "CREATE TABLE IF NOT EXISTS `{$memoriesTable}` (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT,
        `content` TEXT NOT NULL,
        `created` INTEGER NOT NULL
    );";
    $db->query($createTable);
}

// 检查用户是否登录
$user = Typecho_Widget::widget('Widget_User');
$isLoggedIn = $user->hasLogin();

if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['memories_entry'])) {
        $insert = $db->insert($memoriesTable)->rows([
            'content' => $_POST['memories_entry'],
            'created' => time()
        ]);
        $db->query($insert);
        header("Location: " . $this->permalink);
        exit;
    } elseif (isset($_POST['delete_entry_id'])) {
        $delete = $db->delete($memoriesTable)
                     ->where('id = ?', $_POST['delete_entry_id']);
        $db->query($delete);
        header("Location: " . $this->permalink);
        exit;
    }
}

$select = $db->select()->from($memoriesTable)->order('created', Typecho_Db::SORT_DESC);
$entries = $db->fetchAll($select);
?>

<link rel="stylesheet" href="<?php $this->options->themeUrl('css/memories.css'); ?>">

<div class="memories-page">
    <h1>碎碎念</h1>
    <?php if ($isLoggedIn): ?>
        <div class="memories-form-container">
            <form method="post" action="<?php echo $this->permalink(); ?>">
            <textarea name="memories_entry" rows="3" cols="50" placeholder="写下你今天的碎碎念..."></textarea>
            <div style="text-align: right;">
                <button type="submit">提交</button>
            </div>
            </form>
        </div>
    <?php else: ?>
        只有登录用户才能添加碎碎念。
    <?php endif; ?>
    <hr>

    <div id="memories">
        <ul class="memories-list">
            <?php if (count($entries) > 0): ?>
                <?php foreach ($entries as $entry): ?>
                    <li>
                        <div class="date"><?php echo date('Y-m-d', $entry['created']); ?></div>
                        <div class="content">
                            <p><?php echo htmlspecialchars($entry['content']); ?></p>
                            <?php if ($isLoggedIn): ?>
                                <!-- 删除功能 -->
                                <form method="post" action="<?php echo $this->permalink(); ?>" style="display:inline;">
                                    <input type="hidden" name="delete_entry_id" value="<?php echo $entry['id']; ?>">
                                    <button type="submit" onclick="return confirm('确定要删除这条记录吗？');">删除</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li><p>还没有任何碎碎念。</p></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php
$this->need('footer.php'); 
?>