<?php
$page_title = '資料列表';
// 1.history 2.trend 3.partner
require __DIR__ . '/../config/_connect_db.php';

$perPage = 4; // 每頁有幾筆資料
$page_name = 'blog_data_list';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sql = "SELECT COUNT(1) FROM `blog`";

// $sql = "SELECT * FROM `members` LEFT JOIN browsing_history LIMIT 0 , 2;";

$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
// die('~~~'); //exit; // 結束程式
$totalPages = ceil($totalRows / $perPage);

$rows = [];    //這裡有個功能可以再加
if ($totalRows > 0) {
    // if ($page < 1) $page = 1;
    // if ($page > $totalPages) $page = $totalPages;

    //另一種寫法不會reload
    if ($page < 1) {
        header('Location: blog_data_list.php');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: blog_data_list.php?page=' . $totalPages);
        exit;
    };

    $sql = sprintf("SELECT * FROM `blog` LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}


// 定義主題，對應資料庫的 theme(number 型態)
$type = array(
    1 => '歷史',
    2 => '趨勢',
    3 => '合作',
)

?>
<?php require __DIR__ . '/../views/_html_head.php'; ?>
<?php include __DIR__ . '/../views/_navbar.php'; ?>
<div class="container">
    <div class="row justify-content-between">
        <?php foreach ($rows as $r) : ?>
            <div class="card" style="width: 18rem;">

                <img class="card-img-top" src="../uploads/<?= $r['picture']; ?>" alt="部落格圖片">
                <div class="card-body">
                    <div>序號<?= $r['sid'] ?></div>
                    <h5 class="card-title">主題<?= $type[$r['theme']] ?></h5>
                    <p class="card-text"><?= $r['text'] ?></p>
                    <a href="#" class="btn btn-primary">Go somewhere(想連到商品頁)</a>

                    <a href="blog_delete.php?sid=<?= $r['sid'] ?>" onclick="ifDel(event)" data-sid="<?= $r['sid'] ?>">
                        <i class="fas fa-trash-alt"></i>
                    </a>

                    <a href="blog_edit.php?sid=<?= $r['sid'] ?>"><i class="fas fa-edit"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <div class="row ">
        <div class="col">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $page - 1 ?>"><i class="fas fa-hand-point-left"></i></a></li>
                    <?//php for ($i = 1; $i <= $totalPages; $i++) : ?><?php for ($i = $page - 2; $i <= $page + 2; $i++) :
                                                                            if ($i < 1) continue;
                                                                            if ($i > $totalPages) break;
                                                                        ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $page + 1 ?>"><i class="fas fa-hand-point-right"></i></a></li>
                </ul>
            </nav>

        </div>
    </div>



    <?//php for($i=1;$i<=$totalPages;$i++): ?>

    <!-- <script>
        const trashes = document.querySelectorAll('.my-trash-i');

        const trashHandler = (event) => {
            const t = event.target;
            const tr = t.closest('tr');
            tr.style.backgroundColor = 'yellow';
            setTimeout(function() {
                tr.remove();
            }, 300);
        };

        trashes.forEach((el) => {
            el.addEventListener('click', trashHandler);
        })
    </script> -->

</div>
<?php include __DIR__ . '/../views/_scripts.php'; ?>

<script>
    function ifDel(event) {
        const a = event.currentTarget;
        console.log(event.target, event.currentTarget);
        const sid = a.getAttribute('data-sid');
        if (!confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            event.preventDefault(); // 取消連往 href 的設定
        }
    }

    function delete_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料???`)) {
            location.href = 'v_delete.php?sid='
            sid;
        }
    }
</script>

<?php include __DIR__ . '/../views/_html_foot.php'; ?>


<style>
</style>