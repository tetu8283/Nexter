document.addEventListener('DOMContentLoaded', function () {
    let page = 1;            // 現在のページ番号
    let isLoading = false;   // ロード中かどうか
    let hasMorePages = true; // 次のページが存在するか
    const selectedStoreId = document.getElementById('selectedStore').value;
    const inventoryTableBody = document.getElementById('inventoryTableBody');
    const bookBody = document.querySelector('.book-body.overflow-auto');
    const searchForm = document.getElementById('searchForm');

    // 入荷予定データを取得してテーブルに追加
    function loadArrivalData(reset = false) {
        if (reset) {
            page = 1;
            hasMorePages = true;
            inventoryTableBody.innerHTML = '';
        }
        isLoading = true;
        // 検索フォームの値を取得
        const searchName = document.getElementById('searchName').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // クエリパラメータを付与してURLを生成
        let url = `/arrivals/load/${page}/${selectedStoreId}?`;
        if (searchName) {
            url += `name=${encodeURIComponent(searchName)}&`;
        }
        if (startDate) {
            url += `start_date=${encodeURIComponent(startDate)}&`;
        }
        if (endDate) {
            url += `end_date=${encodeURIComponent(endDate)}&`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                appendArrivals(data.arrivals);
                hasMorePages = data.hasMorePages;
                isLoading = false;
            })
            .catch(error => {
                console.error(error);
                isLoading = false;
            });
    }

    // 入荷データをテーブルへ追記する関数
    function appendArrivals(arrivals) {
        arrivals.forEach(item => {
            const row = `
                <tr style="height: 80px;">
                    <td class="text-center align-middle">
                        <input type="checkbox" name="selected_items" value="${item.id}" style="width: 20px; height: 20px; cursor: pointer;">
                    </td>
                    <td class="text-center align-middle">
                        <img src="${item.book ? item.book.image : '/images/no_image.png'}" alt="本の画像" class="img-fluid rounded" style="width: 80px; height: 80px;">
                    </td>
                    <td class="align-middle">
                        <p class="mb-1">${item.book ? item.book.name : '本の情報なし'}</p>
                    </td>
                    <td class="align-middle">
                        ${formatDate(item.arrival_date)}
                    </td>
                    <td class="align-middle" style="width: 5rem;">
                        <form action="/arrivals/${item.id}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-sm">削除</button>
                        </form>
                    </td>
                </tr>
            `;
            inventoryTableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    // 日付フォーマット関数
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }

    // 検索フォーム送信時
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        loadArrivalData(true); // リセットして再取得
    });

    // 無限スクロール: 70%までスクロールしたら次ページを取得
    bookBody.addEventListener('scroll', function () {
        const scrollTop = bookBody.scrollTop;
        const scrollHeight = bookBody.scrollHeight;
        const clientHeight = bookBody.clientHeight;

        if (scrollTop + clientHeight >= scrollHeight * 0.7 && !isLoading && hasMorePages) {
            page++;
            loadArrivalData();
        }
    });

    // 初回データ取得
    loadArrivalData(true);
});


// チェックボックスの選択状態を管理
window.addEventListener('load', function () {
    document.getElementById('confirmArrivals').addEventListener('click', function() {
        const checkedCheckboxes = document.querySelectorAll('input[name="selected_items"]:checked');
        if (checkedCheckboxes.length === 0) {
            alert('確定する入荷予定を選択してください');
            return;
        }

        const ids = Array.from(checkedCheckboxes).map(cb => cb.value);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/arrivals/update_flag', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('入荷登録が確定しました');
                location.reload();
            } else {
                alert(data.message || 'エラーが発生しました');
            }
        })
        .catch(error => {
            console.error(error);
            alert('エラーが発生しました');
        });
    });
});
