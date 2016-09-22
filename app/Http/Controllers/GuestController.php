<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use App\Book;
use Laratrust\LaratrustFacade as Laratrust;

class GuestController extends Controller {

    public function index ( Request $request, Builder $htmlBuilder ) {
        // Datatables ajax request
        if( $request->ajax() ) {
            $books = Book::with( 'author' );
            return Datatables::of( $books )
                            ->addColumn( 'stock', function( $book ){
                                return $book->stock;
                            })
                            ->addColumn( 'action', function( $book ){
                                if( Laratrust::hasRole( 'admin' ) ) return '';
                                return '<a href="'.route( 'guest.books.borrow', $book->id ).'" class="btn btn-xs btn-primary">Pinjam</a>';
                            })->make( true );
        }

        // generate htmlBuilder
        $html = $htmlBuilder
                ->addColumn( [ 'data' => 'title', 'name' => 'title', 'title' => 'Judul' ] )
                ->addColumn( [ 'data' => 'stock', 'name' => 'stock', 'title' => 'Stok Buku', 'orderable' => false, 'searchable' => false, 'class' => 'text-center' ] )
                ->addColumn( [ 'data' => 'author.name', 'name' => 'author.name', 'title' => 'Penulis' ] )
                ->addColumn( [ 'data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'] );

        return view( 'guest.index' )->with( compact( 'html' ) );
    }
}
