<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理画面</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-sky-950 flex">
    <!-- 飲み物一覧 -->
    <div class="w-2/3 mt-8 pt-2 mx-12 text-center border-x-2 border-dashed border-sky-800 flex items-center space-y-1 flex-col select-none overflow-y-scroll">
        @foreach ($drinks as $drink)
        <a href="/edit/{{$drink->id}}">
            <button type="button" class="{{ $drink->stock>0 ? 'bg-sky-900 hover:bg-sky-700' : 'bg-red-400  hover:bg-red-600' }} text-white w-60 h-10 text-nowrap mt-3">
                {{$drink->name}}
                {{$drink->stock>0 ? '' : '(売り切れ)'}}
            </button>
        </a>
        @endforeach
    </div>

    <div class="w-1/3 h-screen sticky top-0">
        <!-- タイトル部分 -->
        <div class="py-5 px-4 sm:px-6 max-h-xl flex">
            <div class="text-white text-4xl text-center font-serif w-4/6 underline select-none">drinker</div>
        </div>
        <!--その他ボタン-->
        <div class="border-black border-1 space-y-12 py-4 mt-24 flex flex-col justify-center items-center">
            <a href="/edit/0">
                <button type="button" class="bg-slate-600 hover:bg-green-500 text-white w-24 h-10 px-2 text-nowrap ">
                    新規作成
                </button>
            </a>
            <button type="button" id="change_drink" class="bg-slate-600 hover:bg-blue-400 text-white w-24 h-10 px-2 text-nowrap" >
                    入れ替え
                </button>
                <button type="button" id="change_system" class="bg-slate-600 hover:bg-yellow-400 text-white w-24 h-10 px-2 text-nowrap" >
                    システム
                </button>
            <a href="/select">
                <button type="button" class="bg-slate-600 hover:bg-slate-400 text-white w-24 h-10 px-2 text-nowrap" >
                    戻る
                </button>
            </a>
        </div>
    </div>



    <!-- モーダルウィンドウ 配置変更 -->
    <div id="changeD" class="fixed top-24 right-0 w-1/3 h-full bg-sky-950 hidden">
        <div class="modal-content m-auto text-center bg-white w-3/4 p-8 rounded-lg select-none">
            <h2 class="text-2xl font-bold mb-4">配置変更</h2>
            <p class="mb-4">どのドリンクの位置を入れ替えますか？</p>
            <p class="mb-4 text-sm text-red-500">※左のボタンを押すと別画面に飛びます</p>
            <br>
            <form name="chenge_drink" action="/admin/change" method="post">
                @csrf
                <div class="flex flex-row ">
                    <div class="mx-4">change1</div>
                    <select name="change_1" class="border-2 border-black">
                        <option value="0">--選択してください--</option>
                        @foreach ( $drinks as $drink )
                            <option value="{{$drink->id}}">{{$drink->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-row pt-4">
                    <div class="mx-4">change2</div>
                    <select name="change_2" class="border-2 border-black">
                        <option value="0">--選択してください--</option>
                        @foreach ( $drinks as $drink )
                            <option value="{{$drink->id}}">{{$drink->name}}</option>
                        @endforeach
                   </select>
                </div>
            </form>
            <p></p>
            <div class="space-x-4 pt-5">
                <button type="button" class="bg-green-500 hover:bg-green-400 text-white px-4 py-2 rounded-lg" onclick="Select()">Chenge</button>
                <button id="end_change1" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-lg">Close</button>
            </div>
        </div>
    </div>
    <!-- モーダルウィンドウ システム変更 -->
    <div id="changeS" class="fixed top-24 right-0 w-1/3 h-full bg-sky-950 hidden">
        <div class="modal-content m-auto text-center bg-white w-3/4 p-8 rounded-lg select-none">
            <h2 class="text-2xl font-bold mb-4">システム変更</h2>
            <p class="mb-4 text-sm text-red-500">※左のボタンを押すと別画面に飛びます</p>
            <br>
            <form name="chenge_system" action="/admin/system" method="post" id="changeSys">
                @csrf
                <div class="flex flex-col">
                    <div class="mx-4">当たりの確率(%)</div>
                    <input type="number" name="lottery" value="{{$machine->lottery}}" min="0" max="100" class="border-2 rounded-lg border-double border-black">
                </div>
                <div class="flex flex-col p-4">
                    <div class="mx-4">電子ポイント還元率</div>
                    <input id="range_rate" type="range" name="rate" min="0" max="1" step="0.01"  value="{{$machine->e_rate}}">
                    <div class="text-center">還元率: <output id="value"></output></div>
                </div>
                <div class="flex flex-col">
                    <div class="mx-4">管理者ページのパスワード</div>
                    <div class="mx-4">(15文字以内 空白注意)</div>
                    <input type="text" name="pass" maxlength="15" value="{{$machine->pass}}"  class="border-2 rounded-lg border-double border-black text-center">
                </div>
            </form>
            <p></p>
            <div class="space-x-4 pt-5">
                <button type="submit" form="changeSys" class="bg-green-500 hover:bg-green-400 text-white px-4 py-2 rounded-lg">Chenge</button>
                <button type="button" class="bg-slate-600 hover:bg-slate-400 text-white px-4 py-2 rounded-lg" onclick="Default()">Default</button>
                <button id="end_change2" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-lg">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    const value = document.querySelector("#value");
const input = document.querySelector("#range_rate");
value.textContent = input.value;


    document.getElementById('change_drink').addEventListener('click', function() {
        document.getElementById('changeD').classList.remove('hidden');
    });

    document.getElementById('change_system').addEventListener('click', function() {
        document.getElementById('changeS').classList.remove('hidden');
    });

    document.getElementById('end_change1').addEventListener('click', function() {
        document.getElementById('changeD').classList.add('hidden');
    });
    document.getElementById('end_change2').addEventListener('click', function() {
        document.getElementById('changeS').classList.add('hidden');
    });

input.addEventListener("input", (event) => {
  value.textContent = event.target.value;
});

    function Select(){           
        var change_1 = document.getElementsByName("change_1")[0];
        var change_2 = document.getElementsByName("change_2")[0];
        if(change_1.value == 0 || change_2.value == 0){
            alert('配置を変更する飲み物を選択してください');
        } else {
            document.forms["chenge_drink"].submit();
        }
        
    };

    function Default(){
        if (confirm('規定値に変更いたしますがよろしいでしょうか？')) {
                document.getElementById("range_rate").value = 0.01;
                document.getElementsByName("lottery")[0].value = 20;
                document.getElementsByName("pass")[0].value ='rext';
                document.getElementById("changeSys").submit();
            }  
    }
</script>

</body>