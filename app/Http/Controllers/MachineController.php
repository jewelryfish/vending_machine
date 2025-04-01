<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\User;

class MachineController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function system(Request $request)
    {
        //システム部分変更のためのコントローラー
        $machine=Machine::find(1);
        $machine->lottery = $request->lottery;
        $machine->e_rate = $request->rate;
        $machine->pass = $request->pass;
        $machine->save();
        return redirect('/admin');
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function User(Request $request)
    {
        //ユーザー切り替えのためのコントローラー
        $machine=Machine::find(1);
        if($request->NewUser !=""){
            $user=new User();
            $user->name=$request->NewUser;
            $user->e_money=0;
            $user->e_point=0;
            $user->buy1= "";
            $user->buy2= "";
            $user->buy3= "";
            $user->save();
        }else{
            $user=User::find($request->change_user);
        }
        $machine->id_User = $user->id;
        $machine->save();
        return redirect('/select');
    }

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wallet()
    {
        //財布(マイページ)に飛ぶためのコントローラー
        $machine=Machine::find(1);
        $User = User::find($machine->id_User);
        return view('drinks.wallet', ['user'=> $User]);
    }

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wallet_custom($key , Request $request)
    {
        //財布(マイページ)でできる設定コントローラー
        $machine=Machine::find(1);
        $User = User::find($machine->id_User);

        if($key=='change'){
            //選択したポイント分電子マネーにチャージし、ポイント貯蔵からマイナスする
            $User->e_money+=$request->input('changePoint');
            $User->e_point-=$request->input('changePoint');
            $User->save();
        }else if($key=='charge'){
            //選択した金額分電子マネーにチャージさせる
            $User->e_money+=$request->input('changeMoney');
            $User->save();
        }
        return redirect('/wallet');
    }
}