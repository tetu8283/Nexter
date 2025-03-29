document.addEventListener('DOMContentLoaded', function () {
    let page = 1; // 初期ページ
    let isLoading = false;
    let hasMorePages = true; // 次のページが存在するかのフラグ
    let selectedStoreId = document.getElementById('selectedStore').value;

    const inventoryTableBody = document.getElementById('inventoryTableBody');
    const bookBody = document.querySelector('.book-body.overflow-auto');

    // 店舗切替時の処理
    document.getElementById('selectedStore').addEventListener('change', function() {
        selectedStoreId = this.value;
        resetInventories();
        loadInitialInventories();
    });

    // スクロール時の処理（70%までスクロールしたら次のページを取得）
    bookBody.addEventListener('scroll', function () {
        const scrollTop = bookBody.scrollTop;
        const scrollHeight = bookBody.scrollHeight;
        const clientHeight = bookBody.clientHeight;

        if (scrollTop + clientHeight >= scrollHeight * 0.7 && !isLoading && hasMorePages) {
            loadMoreInventories();
        }
    });

    // 次ページの在庫データを取得する
    function loadMoreInventories() {
        isLoading = true;
        page++; // ページ番号をインクリメント

        fetch(`/inventory/load/${page}/${selectedStoreId}`)
            .then(response => response.json())
            .then(data => {
                if (data.inventories.length > 0) {
                    appendInventories(data.inventories);
                }
                hasMorePages = data.hasMorePages; // 次ページの有無を更新
                isLoading = false;
            })
            .catch(error => {
                console.error(error);
                isLoading = false;
            });
    }

    // 初期データを読み込む
    function loadInitialInventories() {
        page = 1;
        hasMorePages = true; // 初期化
        fetch(`/inventory/load/${page}/${selectedStoreId}`)
            .then(response => response.json())
            .then(data => {
                if(data.inventories.length > 0) {
                    appendInventories(data.inventories);
                }
                hasMorePages = data.hasMorePages;
            })
            .catch(error => {
                console.error(error);
            });
    }

    // 在庫データをテーブルに追加する
    function appendInventories(inventories) {
        inventories.forEach(item => {
            const row = `
                <tr style="height: 80px;">
                    <td class="text-center align-middle">
                        <img src="${item.book ? item.book.image : '/images/no_image.png'}" alt="本の画像" class="img-fluid rounded" style="width: 80px; height: 80px;">
                    </td>
                    <td class="align-middle">
                        <p class="mb-1">${item.book ? item.book.name : '本の情報なし'}</p>
                    </td>
                    <td class="align-middle">
                        ${item.book ? item.book.weight : '-'} Kg
                    </td>
                    <td class="align-middle">
                        ${formatDate(item.created_at)}
                    </td>
                </tr>
            `;
            inventoryTableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    // 在庫データをリセットする
    function resetInventories() {
        page = 1;
        hasMorePages = true;
        inventoryTableBody.innerHTML = '';
        isLoading = false;
    }

    // 日付のフォーマット
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }

    loadInitialInventories();
});
