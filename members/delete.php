<?php    //在資料庫中刪除
require __DIR__ . '/../config/_connect_db.php';
// require __DIR__. '/0831_admin_required.php';

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'members_data_list.php';

if (empty($_GET['sid'])) {
    header('Location: ' . $referer);
    exit;
}
$sid = intval($_GET['sid']) ?? 0;

$pdo->query("DELETE FROM members WHERE sid=$sid ");
header('Location: ' . $referer);