// 全てのDOMが読み込まれたら実行
document.addEventListener('DOMContentLoaded', function () {
    const selectedStore = document.getElementById('selectedStore');
    const employeesNum = document.getElementById('employeesNum');
    const inventoriesNum = document.getElementById('inventoriesNum');
    const totalBooksWeight = document.getElementById('totalBooksWeight');
    const inventoryTableBody = document.getElementById('inventoryTableBody');

    // 店舗選択時のイベント
    selectedStore.addEventListener('change', function () {
        const storeId = this.value;               // thisで上記のselecterStoreを指定

        // 引数のurlにgetを送る
        fetch(`/store-info/${storeId}`)
            .then(response => response.json())    // サーバからのレスポンスをjsonに変換
            .then(data => {
                employeesNum.innerText = `${data.employeesNum} 人`;
                inventoriesNum.innerText = `${data.inventoriesNum} 冊`;
                totalBooksWeight.innerText = `${data.totalBooksWeight} Kg`;

                inventoryTableBody.innerHTML = ''; // 在庫があるtbodyをリセット

                // 在庫があれば実行
                if (data.inventories.length > 0) {
                    data.inventories.forEach(item => {
                        const bookName = item.book ? item.book.name : '本の情報なし';
                        const bookWeight = item.book ? item.book.weight : '-';
                        let bookImage = `${item.book.image}`;

                        const createdAt = new Date(item.created_at).toISOString().split('T')[0];

                        inventoryTableBody.innerHTML += `
                            <tr style="height: 80px;">
                                <td class="text-center align-middle">
                                    <img src="${bookImage}" alt="本の画像" class="img-fluid rounded" style="width: 80px; height: 80px;">
                                </td>
                                <td class="align-middle">
                                    <p class="mb-1">${bookName}</p>
                                </td>
                                <td class="align-middle">
                                    ${bookWeight} Kg
                                </td>
                                <td class="align-middle">
                                    ${createdAt}
                                </td>
                            </tr>`;
                    });
                } else {
                    inventoryTableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center text-muted">在庫がありません</td>
                        </tr>`;
                }
            })
            .catch(error => {
                console.error(error);
                alert('店舗情報の取得に失敗しました');
            });
    });
});
