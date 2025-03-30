document.addEventListener('DOMContentLoaded', function () {
    let page = 1;            // ページ番号
    let isLoading = false;   // ロード中かどうか
    let hasMorePages = true; // 次のページがあるか
    let selectedStoreId = document.getElementById('selectedStore').value;

    const inventoryTableBody = document.getElementById('inventoryTableBody');

    const employeesNum = document.getElementById('employeesNum');
    const inventoriesNum = document.getElementById('inventoriesNum');
    const totalBooksWeight = document.getElementById('totalBooksWeight');
    const bookBody = document.querySelector('.book-body.overflow-auto');
    const searchForm = document.getElementById('searchForm');

    // 統合エンドポイントからデータを取得する関数
    function loadInventoryData(reset = false) {
        if (reset) {
            page = 1;
            hasMorePages = true;
            inventoryTableBody.innerHTML = '';
        }
        isLoading = true;
        const searchName = document.getElementById('searchName').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        let url = `/inventory/data/${selectedStoreId}?page=${page}`;
        if (searchName) {
            url += `&name=${encodeURIComponent(searchName)}`;
        }
        if (startDate) {
            url += `&start_date=${encodeURIComponent(startDate)}`;
        }
        if (endDate) {
            url += `&end_date=${encodeURIComponent(endDate)}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if(employeesNum) {
                    employeesNum.innerText = `${data.employeesNum} 人`;
                }
                if(inventoriesNum) {
                    inventoriesNum.innerText = `${data.inventoriesNum} 冊`;
                }
                if(totalBooksWeight) {
                    totalBooksWeight.innerText = `${data.totalBooksWeight} Kg`;
                }
                // 在庫一覧更新
                appendInventories(data.inventories);
                hasMorePages = data.hasMorePages;
                isLoading = false;
            })
            .catch(error => {
                console.error(error);
                isLoading = false;
            });
    }

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

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }

    // 店舗変更時
    document.getElementById('selectedStore').addEventListener('change', function() {
        selectedStoreId = this.value;
        loadInventoryData(true);
    });

    // 検索フォーム送信時
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        loadInventoryData(true);
    });

    // 無限スクロール(70%到達時に次ページを取得)
    bookBody.addEventListener('scroll', function () {
        const scrollTop = bookBody.scrollTop;
        const scrollHeight = bookBody.scrollHeight;
        const clientHeight = bookBody.clientHeight;
        if (scrollTop + clientHeight >= scrollHeight * 0.7 && !isLoading && hasMorePages) {
            page++;
            loadInventoryData();
        }
    });

    // 初回データ取得
    loadInventoryData(true);
});
