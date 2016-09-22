@extends( 'layouts.app' )

@section( 'content' )
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ url( '/home' ) }}"> Dashboard </a></li>
                    <li class="active"> Profil </li>
                </ul>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title">
                            Profil
                        </h2>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-8 col-md-offset-2">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="text-muted"> Nama </td>
                                        <td>{{ auth()->user()->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Email</td>
                                        <td>{{ auth()->user()->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Login Terakhir</td>
                                        <td>{{ auth()->user()->last_login }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2 col-md-offset-2">
                            <a href="{{ url('/settings/profile/edit' ) }}" class="btn btn-primary">Ubah</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
