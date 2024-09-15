<?php
/**
 * 碎碎念
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
    } elseif (isset($_POST['edit_entry_id']) && isset($_POST['edit_entry_content'])) {
        $update = $db->update($memoriesTable)
                     ->rows(['content' => $_POST['edit_entry_content']])
                     ->where('id = ?', $_POST['edit_entry_id']);
        $db->query($update);
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
                <button type="submit" class="submit-btn">提交</button>
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
                            <p id="content-<?php echo $entry['id']; ?>"><?php echo htmlspecialchars($entry['content']); ?></p>
                            <?php if ($isLoggedIn): ?>
                                <div class="action-buttons">
                                    <button class="edit-btn" onclick="editEntry(<?php echo $entry['id']; ?>)">编辑</button>
                                    <form method="post" action="<?php echo $this->permalink(); ?>" style="display:inline;">
                                        <input type="hidden" name="delete_entry_id" value="<?php echo $entry['id']; ?>">
                                        <button type="submit" class="delete-btn" onclick="return confirm('确定要删除这条记录吗？');">删除</button>
                                    </form>
                                </div>
                                <form id="edit-form-<?php echo $entry['id']; ?>" method="post" action="<?php echo $this->permalink(); ?>" style="display:none;">
                                    <input type="hidden" name="edit_entry_id" value="<?php echo $entry['id']; ?>">
                                    <textarea name="edit_entry_content" rows="3"><?php echo htmlspecialchars($entry['content']); ?></textarea>
                                    <button type="submit" class="save-btn">保存</button>
                                    <button type="button" class="cancel-btn" onclick="cancelEdit(<?php echo $entry['id']; ?>)">取消</button>
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

<script>
function editEntry(id) {
    document.getElementById('content-' + id).style.display = 'none';
    document.getElementById('edit-form-' + id).style.display = 'block';
}

function cancelEdit(id) {
    document.getElementById('content-' + id).style.display = 'block';
    document.getElementById('edit-form-' + id).style.display = 'none';
}
</script>

<?php
$this->need('footer.php'); 
?>