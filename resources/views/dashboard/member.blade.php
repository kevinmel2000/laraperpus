@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title">Dashboard</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="text-center text-primary">Selamat datang di Larapus, <b> {{ auth()->user()->name }} </b> </h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr>
                                            <th colspan="2" class="bg-default"> Buku Yang Anda Pinjam </th>
                                        </tr>
                                            @if ( $borrowLogs->count()  == 0 )
                                                <tr>
                                                    <td class="text-center">Tidak ada buku dipinjam</td>
                                                </tr>
                                            @endif

                                            @foreach ($borrowLogs as $borrowLog)
                                            <tr>
                                                <td>
                                                {!! Form::open([
                                                        'url'          => route( 'member.books.return', $borrowLog->book_id ),
                                                        'method'       => 'put',
                                                        'class'        => 'form-inline js-confirm',
                                                        'data-confirm' => "Anda yakin akan mengembalikan " . $borrowLog->book->title . "?"
                                                    ])
                                                !!}
                                                {{ $borrowLog->book->title }}
                                                </td>
                                            <td class="text-center" >
                                                {!! Form::submit( 'Kembalikan', [ 'class' => 'btn btn-xs btn-primary' ] ) !!}
                                                {!! Form::close() !!}
                                            @endforeach
                                            </td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
