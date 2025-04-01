<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Drink操作</title>
    @vite('resources/css/app.css')
</head>


<body class="bg-sky-950 h-full min-w-96">
    <!--実質ヘッダー(タイトル)-->
    <div class="my-10 py-2 px-10 text-white text-4xl text-right font-serif">
        drinker
    </div>
    <!--登録フォーム-->
    <form action="/edit/{{$drink->id}}" method="post" autocomplete="off" name="create_drink" class="flex h-full" enctype="multipart/form-data">
        @csrf
        <!--画像変更部分-->
        <div class="w-2/6 h-full my-auto">
            <div class="flex flex-col items-center justify-center h-full text-white py-10">
                <div class="w-80 h-80 flex items-center justify-center">
                    <img src="/storage/{{$drink->image}}" alt="{{$drink->name=="" ? '画像を選択' : $drink->name}}" width=36 height="24" class="mx-auto object-contain w-auto h-auto max-w-full max-h-full text-center">
                </div>
                <!--画像アップローダー-->
                <input type="file" name="drink_image" accept="image/*" class="text-center pt-10 " style="display:none" id="fileElem">
                <button id="fileSelect" type="button" class="border-2 border-black bg-slate-300 text-black mt-5">画像を選択</button>
                <div id="fileSelected"></div>
            </div>
        </div>
        <!--数値関連-->
        <div class="w-3/6 h-full text-white flex flex-col justify-center items-center space-y-8 my-auto py-12">
            <div>
                名前：
                <input type="text" name="drink_name" value="{{$drink->name=="" ? 'ドリンク名を入力' : $drink->name}}" class="bg-sky-900 border-2 border-sky-900">
            </div>
            <div>
                値段：
                <input type="number" name="drink_price" value="{{$drink->price}}" class="bg-sky-900 border-2 border-sky-900">
            </div>
            <!--温度関連-->
            <div class="flex flex-row text-white">
                <!-- ice temperature==0-->
                <div class="text-xl select-none">
                    <input type="radio" class="hidden" name= "drink_temperature" id="drink_ice" value="0" {{$drink->temperature == 0 ? 'checked' : ''}}>
                    <label for="drink_ice" class="items-center cursor-pointer">
                        <div class="text-nowrap   {{$drink->temperature==0 ? 'bg-blue-300' : 'bg-sky-900'}} border-2 border-sky-900 ml-3 px-3">Ice</div>
                    </label>
                </div>
                <!-- hot temperature==1-->
                <div class="ml-5 text-xl select-none">
                    <input type="radio" class="hidden" name="drink_temperature" id="drink_hot" value="1" {{$drink->temperature == 1 ? 'checked' : ''}}>
                    <label for="drink_hot" class="items-center cursor-pointer">
                        <div class="text-nowrap  {{$drink->temperature==1 ? 'bg-red-300' : 'bg-sky-900'}} border-2 border-sky-900 ml-3 px-3">Hot</div>
                    </label>
                </div>
            </div>

            <div>
                在庫：
                <input type="number" name="drink_stock" value="{{$drink->stock}}" class="bg-sky-900 border-2 border-sky-900">
            </div>
        </div>
        <!-- ボタン等 -->
        <div class="flex flex-col justify-between h-3/4  my-auto space-y-16">
            <button type="button" class="bg-green-600 text-white w-24 h-10 mb-2 px-2 hover:animate-bounce" onclick="Cheack('{{$drink->price}}','{{$drink->stock}}')">
            {{$drink->name=="" ? '作成' : '更新'}}
            </button>
            <button type="button" class="bg-red-600   hover:animate-bounce text-white w-24 h-10 mb-2 px-2" onclick="Delete('{{$drink->id}}')">
                削除
            </button>
            @if($drink->name != "")
            <a href="/admin">
                <button type="button" class="bg-slate-600 hover:bg-slate-400 text-white w-24 h-10 mb-2 px-2">
                    戻る
                </button>
            </a>
            @endif
        </div>
    </form>
</body>

<script>
    //チェックボックスが変わったらラジオボタンの見た目を変える関数を起動
    const Drink_temp =<?php echo $drink->temperature;?>;
    const Ice = document.getElementById('drink_ice');
    const Hot = document.getElementById('drink_hot');
    //画像を選択した際に選択した画像が表示されるための定数
    const fileSelect = document.getElementById("fileSelect");
    const fileElem = document.getElementById("fileElem");
    const fileSelected = document.getElementById('fileSelected');


    fileElem.addEventListener('change', function() {
        const fileName = this.files[0].name;
        fileSelected.innerHTML = `${fileName}`;
    });

    fileSelect.addEventListener("click", (e) => {
        if (fileElem) {
            fileElem.click();
        }
    }, false);


    document.getElementsByName('drink_temperature').forEach(radioButton => {
        radioButton.addEventListener('change', function() {
            toggleChange('drink_temperature');       
        });
    });


    fileInput.addEventListener('change', function() {
        const fileName = this.files[0].name;
        fileSelected.innerHTML = `Selected File: ${fileName}`;
    });

    //色を変える
    function toggleChange(radioButtonName) {
        const radioButtons = document.getElementsByName(radioButtonName);
        radioButtons.forEach(radioButton => {
            const radioButtonContainer = radioButton.nextElementSibling.querySelector('.text-nowrap');
            if (radioButton.checked) {
                radioButtonContainer.classList.remove('bg-sky-900');
                if(Drink_temp==0){
                    if(Hot.checked){
                        radioButtonContainer.classList.add('bg-red-300');
                    }if(Ice.checked){
                        radioButtonContainer.classList.add('bg-blue-300');
                    }
                }else if(Drink_temp==1){
                    if(Hot.checked){
                        radioButtonContainer.classList.add('bg-red-300');
                    }if(Ice.checked){
                        radioButtonContainer.classList.add('bg-blue-300');
                    }
                }
            } else {
                radioButtonContainer.classList.add('bg-sky-900');
                radioButtonContainer.classList.remove('bg-red-300');
                radioButtonContainer.classList.remove('bg-blue-300');
            }
        });
    }

    //エラーチェック
    function Cheack(Price,Stock){

        let price_new = document.getElementsByName('drink_price')[0];
        let stock_new = document.getElementsByName('drink_stock')[0];

        if(price_new.value%10 != 0){
            alert('飲み物の値段は10円単位で設定してください');
        } else if(stock_new.value <= 0){
            alert('在庫数は1つ以上にしてください');
        } else{
            alert('更新しました');
        document.forms["create_drink"].submit();
        }
        };

    //削除前の警告
    function Delete(drink_id){
        if (confirm('本当に削除しますか？')) {
            return window.location.href = "/delete/" + drink_id;
        }
    }
</script>