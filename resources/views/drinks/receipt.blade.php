<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>購入完了</title>
    @vite('resources/css/app.css')
    @vite('resources/css/confetti.css')
</head>
 
<body class="bg-sky-950">
    <!-- あたりの時は紙吹雪が舞う -->
    @if ($lucky%1111==0)
        <ul class="confetti">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    @endif
    <div class="flex">
        <!-- 購入した飲み物 -->
        <div class="w-1/3 h-fit mt-5 mx-5  relative">
            <div class="flex flex-col items-center justify-center h-screen">
                <div class="w-80 h-80">
                    <img src="/storage/{{$drink->image}}" alt="{{$drink->name}}" width=36 height="24" class="mx-auto object-contain w-auto h-auto max-w-full max-h-full">
                </div>                        
                <p class="text-white mt-3">{{$drink->name}}</p>
                <div class="{{ $drink->temperature==0 ? 'bg-blue-300' : 'bg-red-400' }} text-white w-24 h-10 mx-auto mt-4 pt-2 text-nowrap text-center">
                    ¥ {{$drink->price}}
                </div>
            </div>
        </div>

        <!-- お金関連などの操作部分 -->
        <div class="w-full h-fit">
             <!-- タイトル部分 -->
            <div class="py-5 px-4 sm:px-6 max-h-xl flex">
                <div class="text-lime-400 text-2xl text-right font-serif mx-auto select-none">購入完了：{{$money==0 ? '電子マネー' : '現金'}}</div>
            </div>
            <!--money>0の時現金で購入/money=0の時電子マネーで購入 -->
            @if ($money>0)
                <!--現金で購入-->
                <!-- お釣り表示部分 -->
                <div class="h-3/4 w-max mx-auto  mt-16 space-y-12">
                    <div class="border-black border-1 bg-sky-900 py-4 items-center text-center">
                        <p  id="totalDisplay" class="text-white px-10">お釣り  ¥{{$money - $drink->price}}</p>
                    </div>
                    <!-- お釣り計算 -->
                    @php
                        $Changes=array(5000=>0,1000=>0,500=>0,100=>0,50=>0,10=>0);
                        $return=$money-$drink->price;

                        foreach($Changes as $Change =>$num){
                            if($return >= $Change){
                            $i=intval($return/$Change);
                            $Changes[$Change]=$i;
                            $return-=$Change*$i;
                            }
                        }
                    @endphp
                    <!-- お釣り表示 -->
                    <div class="border-sky-900 border-2 mt-8">
                        <div class="mt-2 space-x-4 text-white text-center">内訳</div>
                        <div class="flex mt-4 space-x-4 text-white justify-center">
                            <img src="/storage/money_coin_10.png" alt="10Yen" width=45 height=45>
                            <div>✖︎{{$Changes[10]}}</div>
                            <img src="/storage/money_coin_50.png" alt="50Yen" width=45 height=45>
                            <div>✖︎{{$Changes[50]}}</div>
                            </div>    
                            <div class="flex mt-4 space-x-4 text-white justify-center">
                            <img src="/storage/money_coin_100.png" alt="100Yen" width=45 height=45>
                            <div>✖︎{{$Changes[100]}}</div>  
                            <img src="/storage/money_coin_500.png" alt="500Yen" width=45 height=45>
                            <div>✖︎{{$Changes[500]}}</div>    
                        </div>
                        <div  class="flex mx-10 my-4 space-x-4 text-white justify-center">    
                            <img src="/storage/money_1000.png" alt="1000Yen" width=70 height=70>
                            <div>✖︎{{$Changes[1000]}}</div>
                            <img src="/storage/money_5000.png" alt="5000Yen" width=70 height=70>
                            <div>✖︎{{$Changes[5000]}}</div>
                        </div>
                    </div>
                <script>
                    //計算関連スクリプト
                    let total=0;
                    function Calc(money){
                        total+=money;
                        totalDisplay.textContent = "¥ " + total;
                    };
                    function Delete(){
                        total=0;
                        totalDisplay.textContent = "¥ " + total;
                    }                    
                </script>
            </div>

            @else
            <!--電子マネーで購入-->
            <div class="space-y-12  mt-16">
                <div class="border-black border-1 bg-sky-900 text-white h-3/4 w-max px-4 py-2 mx-auto">
                    残高：{{$user->e_money}}
                </div>
                <div class="border-black border-1 bg-sky-900 text-white h-3/4 w-max px-4 py-2 mx-auto">
                    会得ポイント：{{intval($drink->price * $e_rate)}}p
                </div>
                <div class="border-black border-1 bg-sky-900 text-white h-3/4 w-max px-4 py-2 mx-auto">
                    合計ポイント：{{intval($user->e_point)}}p
                </div>
            </div>
            @endif

            <!-- くじ -->
            <div class="border-black border-1 bg-sky-900 py-4 mt-24 mx-4 w-2/3 mx-auto text-nowrap">
                <p  id="Lucky_num" class="text-white px-10 text-center">Lucky Number：{{$lucky}}</p>
                @if ($lucky%1111==0)
                <p  class="text-white px-10 text-center">大当たり！ ドリンクもう1本</p>
                @endif
            </div>

            <!-- その他ボタン -->
            <div class="py-4 mt-10 h-1/5 w-3/5 fixed flex place-content-end bottom-4 right-4">
                <div class="flex items-center ml-auto">
                    <div class="text-white text-3xl text-right font-serif w-4/6 mx-10 underline select-none">drinker</div>
                    @if ($lucky%1111==0)
                        <a href="/lucky/select">
                            <button type="button" class="bg-slate-600 text-white w-24 h-10 px-2 mr-5 mt-5 text-nowrap" >
                                選択
                            </button>
                        </a>
                    @else
                        <a href="/select">
                            <button type="button" class="bg-slate-600 text-white w-24 h-10 px-2 mr-5 mt-5 text-nowrap" >
                                戻る
                            </button>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    

</body>
 
</html>