<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BorrowLog extends Model
{
    // menentukan field yg bisa diakses model
    protected $fillable = [ 'book_id', 'user_id', 'is_returned' ];

    //Eloquent Orm join BorrowLog ke Book
    public function book() {
        return $this->belongsTo( 'App\Book' );
    }

    // Eloquent ORM join BorrowLog ke User
    public function user() {
        return $this->belongsTo( 'App\User' );
    }

    // Attribute casting eloquent
    protected $casts = [ 'is_returned' => 'boolean' ];

    // Query Scope pada Eloquent : scopeReturned
    public function scopeReturned( $query ) {
        return $query->where( 'is_returned', 1 );
    }

    // Query Scope pada Eloquent : scopeBorrowed
    public function scopeBorrowed( $query ) {
        return $query->where( 'is_returned', 0 );
    }
}
