<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理画面</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-500 flex flex-col h-full col space-y-16 my-16 mx-6">
    <!--電子マネーに関わる部分の説明-->
    <div class="border-4 border-dashed border-slate-800  h-1/2 flex flex-row">
        <div class="border border-black ml-4 text-center w-1/4 my-auto">
            電子マネー
        </div>
        <div class="text-center w-full  flex flex-col space-y-4 py-6">
            <div>残高：{{$user->e_money}}円</div>
            <div>所得ポイント：{{$user->e_point}}p</div>
            <!-- 残高変換系統-->
            <div class="flex flex-row mx-auto space-x-2">
                <!-- ボタンを押すことでモーダルウィンドウを呼び出す -->
                <button id="openModal1" class="border-2 border-black px-8 py-1 text-white bg-slate-600  hover:bg-slate-400 w-42">
                    ポイント変換
                </button>
                <button id="openModal2" class="border-2 border-black px-8 py-1 text-white bg-slate-600 hover:bg-slate-400 w-42">
                    現金チャージ
                </button>
            </div>
        </div>
    </div>
    <!--購入履歴に関わる部分の説明-->
    <div class="border-4 border-dashed border-slate-800 flex flex-row">
        <div class="border border-black ml-4 text-center w-1/4 m-auto">
            購入履歴
        </div>
        <div class="ml-4 text-center w-2/3 m-4">
            <div class="border-b border-black">新しい順に過去三件表示されます。</div>
            <!-- 使用回数が3回以内の場合表の形を変える必要がある -->
            @if ($user->buyHow % 10 !=0)
                <table class="border border-spacing-4 mx-auto my-4 text-slate-800 table-auto">
                    <tr class="bg-slate-400">
                        <th> 直近順</th><th>商品名</th><th>購入方法</th>
                    </tr>
                    <tr>
                        <th>1</th><th>{{$user->buy1}}</th><th>{{$user->buyHow % 10 ==3 ? '当たり' : ($user->buyHow % 10 ==2 ? '電子マネー' : '現金')}}</th>
                    </tr>
                    @if ($user->buyHow /10 % 10 !=0)
                        <tr>
                            <th>2</th><th>{{$user->buy2}}</th><th>{{$user->buyHow /10 % 10 ==3 ? '当たり' : ($user->buyHow /10 % 10 ==2 ? '電子マネー' : '現金')}}</th>
                        </tr>
                    @endif
                    @if ($user->buyHow /100 %10 !=0)
                        <tr>
                            <th>3</th><th>{{$user->buy3}}</th><th>{{$user->buyHow /100 % 10 ==3 ? '当たり' : ($user->buyHow /100 % 10 ==2 ? '電子マネー' : '現金')}}</th>
                        </tr>
                    @endif
                </table>
            <!-- ユーザー登録がされていない場合(ダミーデータが読み込まれている場合)とそれ以外とで表示 -->
            @elseif($user->name == "not User")
                ユーザー登録を先に行なってください。
            @else
                まだ自販機を利用していません。
            @endif
        </div>
    </div>
</body>


<!--フッター部分-->
<footer class="py-12 px-4 place-items-end fixed bottom-0 w-full text-right">
    <a href="/select">
        <button class="border-2 border-black px-8 mx-10 py-1  text-white bg-slate-600 hover:bg-slate-400">
            戻る
        </button>
    </a>
</footer>

<!-- モーダルウィンドウ ✖️２-->
<div id="modal1" class="fixed  -top-16 left-0 w-full h-full bg-gray-800/75 hidden">
    <div class="modal-content m-auto text-center bg-white w-2/3 p-8 mt-32 rounded-lg select-none">
        <h2 class="text-2xl font-bold mb-4">ポイント変換
        </h2>
        <p class="mb-4">You can change Point to e-money</p>
        <br>
        <p>現在のポイント：{{$user->e_point}}p</p>
        <!--何ポイント電子マネーに交換するか記載-->
        <form id="c_point" action="/wallet/change" method="post">
        @csrf
        <div class="flex flex-row items-center justify-center text-center">
            <div>変換ポイント：</div>
            <input type="number" name="changePoint" id="inputPoint" class="border-2 border-slate-800 rounded-md" max="{{$user->e_point}}" min="0">
            <div class="ml-2">p</div>
        </div>
        </form>
        <p name="p_error"></p>
        <div class="space-x-4 pt-5">
            <button type="submit" form="c_point" class="bg-green-500 hover:bg-green-400 text-white px-4 py-2 rounded-lg">Chenge</button>
            <button id="closeModal" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-lg">Close</button>
        </div>
    </div>
</div>


<div id="modal2" class="fixed -top-16 left-0 w-full h-full bg-gray-800/75 hidden">
    <div class="modal-content mx-auto text-center bg-white w-2/3  mt-32 p-8 rounded-lg select-none">
        <h2 class="text-2xl font-bold mb-4">500円単位でチャージ可能
        </h2>
        <p class="mb-4">You can charge money in units of 500 yen</p>
        <br>
        <p>現在の残高：{{$user->e_money}}円</p>
        <!--押したボタンに対応してチャージする金額が選択される-->
        <form id="c_money" action="/wallet/charge" method="post">
            @csrf
            <div class=" flex flex-row items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" width=45 height=45 class="fill-sky-600 active:fill-sky-300 cursor-pointer" onclick="mathMoney(-500)"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M9.4 278.6c-12.5-12.5-12.5-32.8 0-45.3l128-128c9.2-9.2 22.9-11.9 34.9-6.9s19.8 16.6 19.8 29.6l0 256c0 12.9-7.8 24.6-19.8 29.6s-25.7 2.2-34.9-6.9l-128-128z"/></svg>
                <input type="hidden" name="changeMoney" id="moneyValue" value="0">
                <div id="displayValue" class="border-b-2 border-black">入力された金額： 0円</div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" width=45 height=45 class="fill-sky-600 active:fill-sky-300 cursor-pointer" onclick="mathMoney(500)"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
            </div>
        </form>
        <p></p>
        <div class="space-x-4 pt-5">
            <button type="submit" form="c_money" class="bg-green-500 hover:bg-green-400 text-white px-4 py-2 rounded-lg">Chenge</button>
            <button id="closeModal2" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-lg">Close</button>
        </div>
    </div>
</div>







<script>
    //モーダルウィンドウを呼び出すor隠すコード
    document.getElementById('openModal1').addEventListener('click', function() {
        document.getElementById('modal1').classList.remove('hidden');
    });
    document.getElementById('openModal2').addEventListener('click', function() {
        document.getElementById('modal2').classList.remove('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('modal1').classList.add('hidden');
    });
    document.getElementById('closeModal2').addEventListener('click', function() {
        document.getElementById('modal2').classList.add('hidden');
    });

    //ポイントチャージのエラーチェック(念のため)
    function math(){           
        var inputPoint = document.getElementById('inputPoint').value;
        
        if (isNaN(inputPoint)) {
            document.getElementById('p_error').innerHTML = "規定の数値を入力してください";
        } else {
            document.forms["c_point"].submit();
        }
    }

    //チャージ金額の集計and表示部分
    function mathMoney(amount) {
        let moneyInput = document.getElementById('moneyValue');
        let currentAmount = parseInt(moneyInput.value);
        let newAmount;
        if( currentAmount + amount>=0){
            newAmount = currentAmount + amount;
        }else{
            newAmount = currentAmount;
        }
        moneyInput.value = newAmount;
        document.getElementById('displayValue').innerText = '入力された金額：' + newAmount + '円';
    }
</script>