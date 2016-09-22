<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Requests\StoreMemberRequest;
use App\Role;
use App\User;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Facades\Datatables;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, Builder $htmlBuilder ) {
        if( $request->ajax() ) {
            $members = Role::where( 'name', 'member' )->first()->users;
            return Datatables::of( $members )
                            ->addColumn( 'name', function( $member ) {
                                return '<a href=" '.route( 'members.show', $member->id ).' ">' .$member->name. '</a>';
                            })
                            ->addColumn( 'action' , function( $member ) {
                                return view( 'datatable._action', [
                                    'model' => $member,
                                    'form_url' => route( 'members.destroy', $member->id ),
                                    'edit_url' => route( 'members.edit', $member->id ),
                                    'confirm_message' => 'Yakin mau menghapus' . $member->name . '?'
                                ]);
                            })->make( true );
        }

        $html = $htmlBuilder
                ->addColumn( [ 'data' => 'name', 'name' => 'name', 'title' => 'Nama' ] )
                ->addColumn( [ 'data' => 'email', 'name' => 'email', 'title' => 'Email' ] )
                ->addColumn( [ 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'class' => 'text-center' ] );

        return view( 'members.index', compact( 'html' ) );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view( 'members.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( StoreMemberRequest $request ){
        $password = str_random( 6 );

        // ambil seluruh field tabel users
        $data = $request->all();
        //get field password
        $data['password'] = bcrypt( $password );
        // get field is_verified untuk bypass verifikasi
        $data['is_verified'] = 1;
        // create member baru
        $member = User::create( $data );

        // set Role
        $memberRole = Role::where( 'name', 'member' )->first();
        $member->AttachRole( $memberRole );

        // kirim email
        Mail::send( 'auth.emails.invite', compact( 'member', 'password' ), function ($m) use ( $member ){
            $m->to( $member->email, $member->name )->subject( 'Anda telah didaftarkan di Larapus!' );
        });

        Session::flash( "flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan member dengan email <strong>" .$data['email'] . "</strong> dan password <strong>" . $password . "</strong>"
        ]);

        return redirect()->route( 'members.index' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = User::find( $id );
        return view( 'members.show', compact( 'member' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        $member = User::find( $id );
        return view( 'members.edit' )->with( compact( 'member' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( UpdateMemberRequest $request, $id )
    {
        $member = User::find($id);
        $member->update( $request->only( 'name', 'email' ) );
        Session::flash( "flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan $member->name"
        ]);
        return redirect()->route( 'members.index' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = User::find( $id );
        //cek member jika ada pinjaman
        $member_minjam = $member->borrowLogs()->where( 'user_id', $member->id )->where( 'is_returned', 0 )->count();

        if( $member->hasRole( 'member' ) ){
            if( $member_minjam > 0 ){
                Session::flash( "flash_notification", [
                    "level" => "danger",
                    "message" => "Tidak bisa dihapus, Member masih memiliki pinjaman"
                ]);
                return redirect()->back();
            }
            else{
                $member->delete();
                Session::flash( "flash_notification", [
                    "level" => "success",
                    "message" => "Member berhasil dihapus"
                ]);
            }
        }
        return redirect()->route( 'members.index' );
    }
}
