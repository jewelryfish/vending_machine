<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>自販機</title>
    @vite('resources/css/app.css')
</head>
 
<body class="bg-sky-950 flex flex-col">
    <!-- 所謂ヘッダー部分 -->
    <div class="w-full h-15 text-white bg-sky-950 flex flex-row pt-5">
        <div class="mx-auto text-xl">
        お好きな飲み物をお選びください
        </div>
    </div>

    <!-- ここに飲み物が並ぶ予定-->
    <div class="mt-8 mx-8 ">
        <div class="flex flex-wrap">
            <!--自販機の中身の数だけループ　飲み物の説明を表記していく-->
            @foreach ($drinks as $drink)      
                <div class="border-2 border-sky-800 rounded-lg bg-gradient-to-b from-sky-950 to-sky-900 px-4 py-5 mx-3 my-3 w-36 ">
                    <div class="w-24 h-24">
                        <img src="/storage/{{$drink->image}}" alt="{{$drink->name}}" class="mx-auto object-contain w-auto h-auto max-w-full max-h-full">
                    </div>
                    <p class="text-white text-sm py-2 select-none">{{$drink->name}}</p>
                    <button type="button" class="{{ $drink->stock>0 ? ($drink->temperature==0 ? 'bg-gradient-to-b from-blue-600 to-blue-300 hover:from-blue-400 hover:to-blue-100' : 'bg-gradient-to-b from-red-600 to-red-400  hover:from-red-400 hover:to-red-100') : 'bg-gray-400 ' }} border rounded-full text-white w-24 h-9 text-nowrap" onclick="Select('{{$drink->stock}}','{{$drink->id}}','{{$drink->name}}')">
                        <!--売り切れなら売り切れ　選べる場合は値段ではなく選択可能とかく-->
                        @if ($drink->stock>0)
                            選択可能
                        @else
                            売り切れ
                        @endif
                    </button>
                </div>
            @endforeach
            <div class="border-2 border-sky-800 rounded-lg bg-gradient-to-b from-sky-950 to-sky-900 px-4 py-5 mx-3 my-3 w-36 ">
                    <div class="w-24 h-24">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto object-contain w-auto h-auto max-w-full max-h-full" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#a8d3ff" d="M96 0C82.7 0 72 10.7 72 24s10.7 24 24 24c4.4 0 8 3.6 8 8v64.9c0 12.2-7.2 23.1-17.2 30.1C53.7 174.1 32 212.5 32 256V448c0 35.3 28.7 64 64 64H224c35.3 0 64-28.7 64-64V256c0-43.5-21.7-81.9-54.8-105c-10-7-17.2-17.9-17.2-30.1V56c0-4.4 3.6-8 8-8c13.3 0 24-10.7 24-24s-10.7-24-24-24l-8 0 0 0 0 0H104l0 0 0 0L96 0zm64 382c-26.5 0-48-20.1-48-45c0-16.8 22.1-48.1 36.3-66.4c6-7.8 17.5-7.8 23.5 0C185.9 288.9 208 320.2 208 337c0 24.9-21.5 45-48 45z"/></svg>
                    </div>
                    <p class="text-white text-sm py-2 select-none">売り切れ時選択</p>
                    <button type="button" class="bg-gradient-to-b from-gray-600 to-gray-400 hover:from-gray-400 hover:to-gray-100 border rounded-full text-white w-24 h-9 text-nowrap" onclick="Skip()">
                        スキップ
                    </button>
                </div>
        </div>
    </div>
    
    <script>

        //購入選択がされた時の確認
        function Select(stock,id,name){
            if(stock<=0){
                alert('売り切れです');
            }else{
                if (confirm('確定していいですか？　選択した飲み物:'+name)) {
                    alert('良い日をお過ごしください！');
                    return window.location.href = "/lucky/" + id;
                }  
            }
        };

        function Skip(){
            if (confirm('スキップでもよろしいですか？(電子ポイントを少量付与されます)')) {
                return window.location.href = "/lucky/point";
            }  
        }
    </script>

    
 
</body>
 
</html>