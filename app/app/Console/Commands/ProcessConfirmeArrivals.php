<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arrival;
use App\Models\Inventory;
use App\Models\Book;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class ProcessConfirmeArrivals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-confirme-arrivals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 入荷予定が確定しているレコードを取得
        $confirmedArrivals = Arrival::where('arrival_flag', 1)->get();

        if ($confirmedArrivals->isEmpty()) {
            $this->info("処理対象の入荷予定はありません。");
            return 0;
        }

        DB::transaction(function () use ($confirmedArrivals) {
            foreach ($confirmedArrivals as $arrival) {
                // Inventoryに在庫を追加
                Inventory::insert([
                    'store_id'   => $arrival->store_id,
                    'book_id'    => $arrival->book_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 入荷処理済みにするため、arrival_flagを2に更新
                $arrival->update([
                    'arrival_flag' => 2,
                    'updated_at'   => now(),
                ]);

                // Bookのstatus_flagを入荷済みに更新
                Book::where('id', $arrival->book_id)->update([
                    'status_flag' => 2,
                    'updated_at'  => now(),
                ]);

                // 在庫に登録し、入荷予定で無くなったためデータを削除
                DB::table('arrival_books')->where('id', $arrival->id)->delete();
            }
        });

        $this->info("入荷確定のデータを在庫へ追加しました。");

        return 0;
    }
}
