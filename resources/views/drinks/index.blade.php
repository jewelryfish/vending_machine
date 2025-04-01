<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>自販機</title>
    @vite('resources/css/app.css')
</head>
 
<body class="bg-sky-950 flex">
    <!-- ここに飲み物が並ぶ予定-->
    <div class="w-2/3 mt-8 mx-8 overflow-y-scroll">
        <div class="flex flex-wrap">
            <!--自販機の中身の数だけループ　飲み物の説明を表記していく-->
            @foreach ($drinks as $drink)      
                <div class="border-2 border-sky-800 rounded-lg bg-gradient-to-b from-sky-950 to-sky-900 px-4 py-5 mx-3 my-3 w-36 ">
                    <form action="/receipt/{{$drink->id}}" method="POST" name="Get_Drink_{{$drink->id}}" class="justify-center items-center text-center">
                        @csrf
                        <div class="w-24 h-24">
                            <img src="/storage/{{$drink->image}}" alt="{{$drink->name}}" class="mx-auto object-contain w-auto h-auto max-w-full max-h-full">
                        </div>
                        <p class="text-white text-sm py-2 select-none">{{$drink->name}}</p>
                        <input type="hidden" name="put_money" class="put_money_now" value="0">
                        <button type="button" class="{{ $drink->stock>0 ? ($drink->temperature==0 ? 'bg-gradient-to-b from-blue-600 to-blue-300 hover:from-blue-400 hover:to-blue-100' : 'bg-gradient-to-b from-red-600 to-red-400  hover:from-red-400 hover:to-red-100') : 'bg-gradient-to-b from-gray-600 to-gray-400' }} border rounded-full text-white w-24 h-9 text-nowrap" onclick="Select('{{$drink->price}}','{{$drink->stock}}','{{$drink->id}}','{{$user->e_money}}')">
                            <!--値段表記/売り切れなら売り切れ-->
                            @if ($drink->stock>0)
                                ¥ {{$drink->price}}
                            @else
                                売り切れ
                            @endif
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- お金関連などの操作部分 -->
    <div class="h-screen min-w-48 sticky top-0">
        <!-- タイトル部分 -->
        <div class="mt-6 mb-5 mx-auto sm:px-6 max-h-xl  flex justify-between whitespace-nowrap">
            <div class="text-white text-4xl text-right font-serif w-4/6 underline select-none px-2">drinker</div>
            <div class="w-2/6 min-w-12 px-25">
                <!--管理画面へ飛ぶボタン-->
                <img src="/storage/gear_icon.png" alt="gear" width=45 height=45  class="mx-auto hover:animate-spin" onclick="adminJump('{{$pass}}')">
            </div>
        </div>
        <!--チェックボックスの部分-->
        <div class="mx-5 h-1/5 mx-10 pt-5 pb-5">
            <!-- 電子マネー -->
            <div class="ml-5 text-xl select-none">
                <input type="radio" class="hidden" name= "howpay" id="pay1">
                <label for="pay1" class="flex items-center cursor-pointer">
                    <div class="w-9 h-9  border-2 border-blue-300 bg-sky-950 rounded-md transition duration-200 flex-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-8 hidden text-blue-300 fill-current pointer-events-none" viewBox="0 0 24 24">
                            <path d="M21.707 3.293c-0.391-0.391-1.023-0.391-1.414 0l-11 11c-0.476 0.476-1.318 0.476-1.793 0l-5-5c-0.391-0.391-1.023-0.391-1.414 0s-0.391 1.023 0 1.414l5.707 5.707c0.377 0.377 0.883 0.586 1.414 0.586s1.037-0.209 1.414-0.586l11-11c0.391-0.391 0.391-1.023 0-1.414z"/>
                        </svg>
                    </div>
                    <div class="ml-3 text-xl text-lime-400 text-nowrap">電子マネー</div>
                </label>
            </div>

            <!-- 現金 -->
            <div class="ml-5 text-xl select-none mt-5">
                <input type="radio" class="hidden" name="howpay" id="pay2" >
                <label for="pay2" class="flex items-center cursor-pointer">
                    <div class="w-9 h-9 border-2 border-blue-300 bg-sky-950 rounded-md transition  duration-200 flex-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-8 hidden text-blue-300 fill-current pointer-events-none " viewBox="0 0 24 24">
                            <path d="M21.707 3.293c-0.391-0.391-1.023-0.391-1.414 0l-11 11c-0.476 0.476-1.318 0.476-1.793 0l-5-5c-0.391-0.391-1.023-0.391-1.414 0s-0.391 1.023 0 1.414l5.707 5.707c0.377 0.377 0.883 0.586 1.414 0.586s1.037-0.209 1.414-0.586l11-11c0.391-0.391 0.391-1.023 0-1.414z"/>
                        </svg>
                    </div>
                    <div class="ml-3 text-xl text-lime-400 text-nowrap">現金</div>
                </label>
            </div>

            <!--ユーザー切り替え部分-->
            <div class="h-1/8 mx-5 my-4 flex flex-row space-x-2">
                <div onclick="Usercheak(1)" class="cursor-pointer">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user text-blue-300"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                </div>
                <div class="text-lime-400 select-none">{{$user->name}}</div>
            </div>
        </div>


        <!-- お金投入部分 -->
        <div class="h-1/4 border-sky-900 border-2 mx-5 my-4">
            <p class="text-center text-lg mt-2 text-sky-900 font-serif font-semibold select-none">please put money</p>
            <!-- 硬貨ボタン -->
            <div class="flex mt-2 space-x-4 p-2 justify-center">
                <button type="button" onclick="Calc(10)">
                    <img src="/storage/money_coin_10.png" alt="10Yen" width=45 height=45>
                </button>
                <button type="button" onclick="Calc(50)">
                    <img src="/storage/money_coin_50.png" alt="50Yen" width=45 height=45>
                </button>
                <button type="button" onclick="Calc(100)">
                    <img src="/storage/money_coin_100.png" alt="100Yen" width=45 height=45>
                </button>
                <button type="button" onclick="Calc(500)">
                    <img src="/storage/money_coin_500.png" alt="500Yen" width=45 height=45>
                </button>
            </div>
            <!-- 紙幣ボタン -->
            <div  class="flex mx-10 my-4 space-x-4 justify-center">
                <button type="button" onclick="Calc(1000)">
                    <img src="/storage/money_1000.png" alt="1000Yen" width=70 height=70>
                </button>
                <button type="button" onclick="Calc(5000)">
                    <img src="/storage/money_5000.png" alt="5000Yen" width=70 height=70>
                </button>
            </div>
        </div>

        <!-- 投入金額表示 -->
        <div class="h-1/7 border-black border-1 bg-sky-900 py-4 my-6 mx-8 ">
            <p  id="totalDisplay" class="text-white px-10">¥0</p>
        </div>

        <!-- その他ボタン -->
        <div class="h-1/7 space-x-4 mt-16  flex-row flex justify-center">
            <button type="button" class="bg-slate-600 hover:bg-slate-400 border-slate-600 border-2 text-white w-24 h-10 px-2 text-nowrap bg-{{ $drink->temperature ? 'red' : 'blue' }}" onclick="Delete()">
                払い戻し
            </button>
            <a href="/wallet">
                <button type="button" class="bg-slate-600 hover:bg-slate-400 border-slate-600 border-2 text-white w-24 h-10 px-2 text-nowrap" >
                    財布確認
                </button>
            </a>
        </div>
    </div>
    
    <script>
        //計算関連 変数or定数定義
        let total=0;
        let jse_money = "<?php echo $user->e_money; ?>";
        const radio1 = document.getElementById('pay1');
        const radio2 = document.getElementById('pay2');
        // 複数のformに金額情報を伝えるための準備
        const buttons = document.querySelectorAll('.put_money_now');

        radio1.addEventListener('change', Reset);
        radio2.addEventListener('change', Reset);
        

        //チェックボックスが変わったらラジオボタンの見た目を変える関数を起動
        document.getElementsByName('howpay').forEach(radioButton => {
            radioButton.addEventListener('change', function() {
                toggleChange('howpay');       
            });
        });

        //✔️を表示され外枠の四角が光るスクリプト
        function toggleChange(radioButtonName) {
            const radioButtons = document.getElementsByName(radioButtonName);
                radioButtons.forEach(radioButton => {
                const checkmark = radioButton.nextElementSibling.querySelector('svg');
                const radioButtonContainer = radioButton.nextElementSibling.querySelector('.border-2');
                if (radioButton.checked) {
                    checkmark.classList.remove('hidden');
                    radioButtonContainer.classList.add('border-blue-100');
                } else {
                    checkmark.classList.add('hidden');
                    radioButtonContainer.classList.remove('border-blue-100');
                }
            });
        }

        //購入選択がされた時の確認
        function Select(price,stock,id,e_total){           
            if(stock<=0){
                alert('売り切れです');
            } else if (document.getElementById('pay1').checked) {
                // 電子マネーが選択されている場合の処理
                if(e_total-price<0){
                    alert('金額が足りません');
                }else{
                    document.forms["Get_Drink_"+id].submit();
                }
            } else if (document.getElementById('pay2').checked) {
                // 現金が選択されている場合の処理
                if(total<price){
                    alert('金額が足りません');
                }else{
                    document.forms["Get_Drink_"+id].submit();
                }
            } else {
                // どちらも選択されていない場合の処理
                alert('購入方法を選択してください');
            }
        };
                    
        //お金の計算関連
        function Calc(money){
            if (radio2.checked){
                total+=money;
                totalDisplay.textContent = "¥ " + total;    
                // 全ての要素に対して値を設定
                buttons.forEach(button => {
                    button.value = total;
                });
            }
        };
        function Delete(){
            total=0;
            totalDisplay.textContent = "¥ " + total;
            buttons.forEach(button => {
                button.value = total;
            });
        }    
        function Reset() {
            total=0;
            if(radio1.checked){
                totalDisplay.textContent = "残高 ¥ " + jse_money;
            }else if(radio2.checked){
                totalDisplay.textContent = "¥ " + total;
            }
                buttons.forEach(button => {
                    button.value = total;
                });
        }

        //管理者画面へ飛ぶためのパスワードチェック
        function adminJump(pass){
            input = window.prompt("パスワードを入力","");
            if(input == pass){
                alert("管理画面に移動します。");
                location.href="/admin";
            }else if(input !== null && input !==""){
                alert("あなたはこの先にはアクセスできません");
            }
        }

        //ユーザ変更関連
        function Usercheak(key){
            if(key==1){
                document.getElementById('Users').classList.remove('hidden');
            }else if(key==0){
                document.getElementById('Users').classList.add('hidden');
            }else if(key==2){
                if(document.getElementById("makeName").value != ''){
                    if (confirm('ユーザーを新規で作成していいですか？(名前は変更できません)')) {
                    document.getElementById("UserIvent").submit();
                    }  
                }else{
                    document.getElementById("UserIvent").submit(); 
                }
            }
        }
    </script>
 
</body>
 

<div id="Users" class="fixed left-0 w-full h-full bg-gray-800/75 hidden">
    <div class="w-2/3">
    <div class="modal-content m-auto text-center bg-white w-2/3 p-8 mt-32 rounded-lg select-none">
        <h2 class="text-2xl font-bold mb-4">自販機利用ユーザー
        </h2>
        <p class="mb-4">現在のユーザー : {{$user->name}}</p>
        <br>
        <form id="UserIvent" action="/select/user" method="post">
        @csrf
        <div class="flex flex-row items-center justify-center text-center">
            <div>ユーザー切り替え：</div>
            <select name="change_user" class="border-2 border-black">
                @foreach ( $users as $changeuser)
                    <option value="{{$changeuser->id}}" {{$changeuser->id == $user->id ? 'selected':''}}>{{$changeuser->name}}</option>
                @endforeach
           </select>
        </div>
        <div class="flex flex-row items-center justify-center text-center space-y-4">
            <div>新規登録(名前を入力)：</div>
            <input type="text" name="NewUser" id="makeName" class="border-2 border-slate-800 rounded-md">
        </div>
        </form>
        <p name="error"></p>
        <div class="space-x-4 pt-5">
            <button type="button" form="UserIvent" class="bg-green-500 hover:bg-green-400 text-white px-4 py-2 rounded-lg" onclick="Usercheak(2)">Chenge</button>
            <button type="button" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-lg" onclick="Usercheak(0)">Close</button>
        </div>
    </div>
    </div>
</div>


</html>