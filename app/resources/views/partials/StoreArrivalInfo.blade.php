<div class="container mt-5">
    <div class="row mb-4">
        {{-- 総従業員数表示 --}}
        <div class="col-md-4">
            <div class="book bg-light mb-3">
                <div class="book-body d-flex align-items-center">
                    <i class="fa-solid fa-people-group fa-3x text-primary me-3"></i>
                    <div>
                        <h5 class="book-title mb-1">従業員数</h5>
                        <p id="employeesNum" class="book-text fs-3 text-end text-primary mb-0">{{ $employeesNum }} 人</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- 在庫総数表示 --}}
        <div class="col-md-4">
            <div class="book bg-light mb-3">
                <div class="book-body d-flex align-items-center">
                    <i class="fa-solid fa-book fa-3x text-success me-3"></i>
                    <div>
                        <h5 class="book-title mb-1">在庫数</h5>
                        <p id="inventoriesNum" class="book-text fs-3 text-end text-success mb-0">{{ $inventoriesNum }} 冊</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- 入荷予定冊数表示 --}}
        <div class="col-md-4">
            <div class="book bg-light mb-3">
                <div class="book-body d-flex align-items-center">
                    <i class="fa-solid fa-people-carry-box fa-3x text-warning me-3"></i>
                    <div>
                        <h5 class="book-title mb-1">入荷予定冊数</h5>
                        <p id="totalBooksWeight" class="book-text fs-3 text-end text-warning mb-0">{{ $arrivalBooksNum }} 冊</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
