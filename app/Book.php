<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Book extends Model
{
    protected $fillable = [ 'title', 'author_id', 'amount' ];

    // Eloquent from book to author
    public function author() {
        return $this->belongsTo( 'App\Author' );
    }

    // Eloquent dari buku ke borrow log (satu buku bisa dipinjam > 1)
    public function borrowLogs() {
        return $this->hasMany( 'App\BorrowLog' );
    }

    public function getStockAttribute() {
        // hitung buku yg lagi dipinjam
        $borrowed = $this->borrowLogs()->borrowed()->count();
        $stock = $this->amount - $borrowed;

        return $stock;
    }

    // validasi ketika mengubah jumlah buku dan hapus buku oleh admin
    public static function boot() {
        parent::boot();

        // eloquent event updating
        self::updating( function( $book ) {
            if( $book->amount < $book->borrowed ) {
                Session::flash( "flash_notification", [
                    "level"   => "danger",
                    "message" => "Jumlah buku $book->title harus >= " . $book->borrowed
                ]);
                return false;
            }
        });

        // eloquent event deleting
        self::deleting( function( $book ){
            if( $book->borrowLogs()->count() > 0 ) {
                Session::flash( "flash_notification", [
                    "level"   => "danger",
                    "message" => "Buku $book->title sudah pernah dipinjam"
                ]);
                return false;
            }
        });
    }

    // Accessor borrowed berisi total buku yg sedang dipinjam
    public function getBorrowedAttribute() {
        return $this->borrowLogs()->borrowed()->count();
    }
}
