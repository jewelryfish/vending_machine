<?php

namespace App\Http\Controllers;
use App\Models\drink;
use App\Models\User;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\store;


class DrinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        //Select(初期画面表示のためのコントローラー)
        $drinks = drink::all();
        $machine = Machine::find(1);
        $User = User::find($machine->id_User);
        $Users = User::all();

        //データが入っていない時用ページ
        if($drinks->isEmpty()) {
            // ダミーデータ入力
            $drinks=new Drink();
            $drinks->id = 0;
            $drinks->name= "設定画面でdrinkを設定してください";
            $drinks->image= "";
            $drinks->price=-100;
            $drinks->stock= 0;
            $drinks->save();
        }
        if($drinks->isEmpty()) {
            // ダミーデータ入力
            $User=new User();
            $User->id = 0;
            $User->name= "not User";
            $User->e_point= 0;
            $User->e_money= 0;
            $User->buy1="";
            $User->buy2= "";
            $User->buy3= "";
            $User->save();
        }
        return view('drinks.index', ['drinks'=> $drinks,'user'=> $User,'users'=>$Users,'pass'=>$machine->pass]);
        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function receipt(request $request, $id)
    {
        //購入後の明細確認用のページに遷移するためのコントローラー
        $machine = Machine::find(1);

        //自販機に入れたお金と選ばれたドリンク
        $coins=$request->input('put_money');
        $buy_Drink=drink::find($id);
        //購入されたのでドリンクのストック数を減らす
        $buy_Drink->stock-=1;
        $buy_Drink->save();

        //電子マネーの場合電子マネーの操作とポイントを付与する　$howは購入履歴用の操作
        $User = User::find($machine->id_User);
        if ($coins==0) {
            $User->e_money-=$buy_Drink->price;
            $User->e_point+=(int) $buy_Drink->price * $machine->e_rate;
            $User->save();
            $how=2;
        } else{
            $how= 1;
        }
        //多重購入防止策
        $request->session()->regenerateToken();
        //くじ番号を抽選
        //$hit とif文を変えることで当たる確率変更可能
        $win_num=array(1111,2222,3333,4444,5555,6666,7777,8888,9999);
        $hit_name=rand(0,8);
        $hit=rand(1,100);
        if($hit<= $machine->lottery){
            $lucky_num=$win_num[$hit_name];
        }else{
            //ハズレの数字もランダムに出力　当たりが出ないようにしっかり調整
            if($hit_name==8){
                $lucky_num=rand(0,1110);
            }else{
                $lucky_num=rand($win_num[$hit_name]+1,$win_num[$hit_name+1]-1);
            }
        }

        //購入履歴を変更する　1...現金 2...電子マネー 3...くじ buy1 buy2 buy3の順に古くなる
        $User->buy3=$User->buy2;
        $User->buy2=$User->buy1;
        $User->buy1=$buy_Drink->name;
        $User->buyHow=( $User->buyHow*10 + $how )%1000;
        $User->save();
        return view('drinks.receipt', ['drink'=> $buy_Drink ,'money'=> $coins,'user'=> $User , 'lucky'=>$lucky_num , 'e_rate'=>$machine->e_rate]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin(request $request)
    {
        //管理ページに飛ぶためのコントローラー
        $machine = Machine::find(1);

        //Routeが changeの場合は管理ページ内で入れ替えを要求した時のもの
        if ($request->route()->getName() == 'change') {
            if($request->change_1 != $request->change_2){
                $change1=drink::find($request->change_1);
                $change2=drink::find($request->change_2);
                //id重複不可なので一旦片方をあり得ない数字に避難させる
                $change1->id=0;
                $change2->id=$request->change_1;
                $change1->save();
                $change2->save();
                $change1->id=$request->change_2;
                $change1->save();
            }
            //アドレスを元に戻すためにリダイレクト
            return redirect('/admin');
        } else{
            //通常の場合
            $drinks = drink::all();
            return view('drinks.admin', ['drinks'=> $drinks , 'machine'=>$machine]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //ドリンクの作成画面に飛ぶためのコントローラー
        //id=0 新規登録　id>0 編集のためにページに飛ぶ
        if($id==0){
            //新規作成の場合新しい外枠だけを準備して作成ページに飛ばせる
            $edit_Drink=new drink;
            $edit_Drink->name="";
            $edit_Drink->image= "";
            $edit_Drink->price=0;
            $edit_Drink->stock= 0;
            $edit_Drink->save();
            return Redirect::route('edit', ['id' => $edit_Drink->id]);
        }else{
        $edit_Drink=drink::find($id);
        return view('drinks.edit', ['drink'=> $edit_Drink]) ;
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(request $request, $id)
    {
        //ドリンクの情報更新を行うためのコントローラー
        $edit_Drink=drink::find($id);
        //画像がアップロードされている場合
        if($request->hasFile('drink_image')){
            $image=$request->file("drink_image");
            if (Storage::exists('public/' . $image->getClientOriginalName())) {
                // ファイルが既に存在する場合の処理
                $edit_Drink->image = $image->getClientOriginalName();
            } else {
                // ファイルが存在しない場合は保存を実行
                $image->storeAs('public', $image->getClientOriginalName());
                $edit_Drink->image = $image->getClientOriginalName();
            }
        }
        //画像以外のデータをアップデート
        $edit_Drink->name=$request->input('drink_name');
        $edit_Drink->price=$request->input('drink_price');
        $edit_Drink->stock=$request->input('drink_stock');
        $edit_Drink->temperature=$request->input('drink_temperature');
        $edit_Drink->save();
        return redirect('/edit/'.$edit_Drink->id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function destroy($id){
        //ドリンクを削除するためのコントローラー
        drink::find($id)->delete();
        return redirect('/admin');
     }

  


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lucky($key)
    {
        //くじがあたりだった時のコントローラー
        if($key=="select"){
            //「もう一本！」を選択した時に飛ぶための部分(selectをキーにしている)
            $drinks = drink::all();
            return view('drinks.lucky', ['drinks'=> $drinks ,'lucky'=> 'win']);
        }else if($key== ''){
            //ないと思うけどkeyがなかった時用のエラーチェック
            dd("sorry this page is エラー");
            return redirect("/select");
        }else if($key=='point'){
            //何も飲み物を選択せずに退出するボタンを押した時の処理　こっそり150p追加してあげる
            $machine = Machine::find(1);
            $User = User::find($machine->id_User);
            $User ->e_point+=150;
            $User->save();
            return redirect("/select");
        }else{
            //普通に飲み物を選択した時の処理　keyは飲み物のid
            $select_drink = drink::find($key);
            $select_drink->stock-=1;
            $select_drink->save();
            $User = User::find(1);
            $User->buy3=$User->buy2;
            $User->buy2=$User->buy1;
            $User->buy1=$select_drink->name;
            $User->buyHow=( $User->buyHow*10 + 3 )%1000;
            $User->save();
            return redirect("/select");
        }
    }
}
?>