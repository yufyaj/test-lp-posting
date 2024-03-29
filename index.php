<?php
    require 'lib/php/Logger/logger.php';
    require 'lib/php/DB/db.php';

    use lib\Logger as logger;
    use lib\db as db;

    $log = logger\Logger::getInstance();
    $db = db\ConnectDb::getInstance();
    
    //SQLを作成
    $sql = "SELECT state, city FROM cost GROUP BY state, city";
    $rows = $db->fetchAll($sql);
    
    //出力結果をそれぞれの配列に格納
    $states = array_column($rows,'state');
    $cities = array_column($rows,'city'); 
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <link rel="canonical" href="https://posting-m.com/">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta name="format-detection" content="telephone=no">
        <title>ポスティング見積.com -簡単3ステップでお見積！安心・信頼の高品質ポスティングをご提案します-</title>
        <meta name="Description" content="ポスティング見積.comでは、専門の担当者がお客様の要望をヒアリングし、配布エリア・配布単価・配布日程によって、最適な会社での御提案・お見積りをお届けいたします。一括見積のサイトと異なり、複数のポスティング会社から見積もりを取る必要はありません。わずらわしい業務は全てポスティング見積.comにお任せください！">
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:400,700&display=swap" rel="stylesheet">
        <!-- tailwindCss -->
        <link href="./css/output.css" rel="stylesheet">
        <script src="https://posting-m.com/js/jquery-3.4.1.min.js"></script>
        <script src="/lib/js/utils.js"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-161021607-1');
            gtag('config', 'AW-806634571');
        </script>
        <script>
            var rows=<?= json_encode($rows, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) ?>;
            
            function setCityList(select_state) {
                const city_element = document.getElementsByName("city")[0];
                const empty_option = document.createElement('option');
                empty_option.value = "";
                empty_option.innerHTML = "選択してください";

                // 要素全削除
                while(city_element.firstChild) {
                    city_element.removeChild(city_element.firstChild);
                }
                city_element.appendChild(empty_option);

                if (select_state == undefined) {
                    return;
                }

                // 行抽出結果(都道府県で市区町村を抽出する)
                const filter_rows = rows.filter(function(row) {
                    return row.state == select_state;
                });

                filter_rows.forEach((filter_row) => {
                    const option = document.createElement('option');
                    option.value = filter_row.city;
                    option.innerHTML = filter_row.city;
                    city_element.appendChild(option);
                });
            }
        </script>
        <script>
            // 簡易見積算出要素に変更があった際の動作
            function changeCostEstimate() {
                clearCostValue();

                const elementCalcCostButton = document.getElementById('get-cost');
                if (!document.getElementsByName('type')[0].value) {
                    elementCalcCostButton.disabled = true;
                    return;
                }
                if (!document.getElementsByName('state')[0].value) {
                    elementCalcCostButton.disabled = true;
                    return;
                }
                if (!document.getElementsByName('city')[0].value) {
                    elementCalcCostButton.disabled = true;
                    return;
                }
                if (!document.getElementsByName('busu')[0].value) {
                    elementCalcCostButton.disabled = true;
                    return;
                }

                elementCalcCostButton.disabled = false;
            }

            // 価格の初期化
            function clearCostValue() {
                document.getElementById('label_cost').innerText = "---";
                document.getElementsByName('cost')[0].value = "";
            }

            // 見積依頼項目に変更があった際の動作
            function changeRequestElement() {
                const elementRequestButton = document.getElementById('send-request');

                if (!document.getElementsByName('cost')[0].value){
                    elementRequestButton.disabled = true;
                    return;
                }

                if (!document.getElementsByName('company')[0].value) {
                    elementRequestButton.disabled = true;
                    return;
                }
                if (!document.getElementsByName('name')[0].value) {
                    elementRequestButton.disabled = true;
                    return;
                }
                if (!document.getElementsByName('mail')[0].value) {
                    elementRequestButton.disabled = true;
                    return;
                }
                if (!document.getElementsByName('privacy')[0].checked) {
                    elementRequestButton.disabled = true;
                    return;
                }

                elementRequestButton.disabled = false;
            }

            // エラーメッセージを返す
            // 結果を見るか要検討
            function returnErrorMessage(json) {
                var error_message = "";

                if (json.is_empty_type != undefined && json.is_empty_type) {
                    error_message += "区分が未指定です。" + "\n";
                }
                if (json.is_empty_state != undefined && json.is_empty_state) {
                    error_message += "都道府県が未指定です。" + "\n";
                }
                if (json.is_empty_city != undefined && json.is_empty_city) {
                    error_message += "市区町村が未指定です。" + "\n";
                }
                if (json.is_empty_busu != undefined && json.is_empty_busu) {
                    error_message += "部数が未指定です。" + "\n";
                }
                if (json.is_empty_company != undefined && json.is_empty_company) {
                    error_message += "会社名が未入力です。" + "\n";
                }
                if (json.is_empty_name != undefined && json.is_empty_name) {
                    error_message += "担当者名が未入力です。" + "\n";
                }
                if (json.is_empty_mail != undefined && json.is_empty_mail) {
                    error_message += "メールアドレスが未入力です。" + "\n";
                }

                return error_message;
            }

            async function postData() {
                document.getElementById('label_error').innerText = "";

                // FoemDataオブジェクトに要素セレクタを渡して宣言する
                const type = document.getElementsByName('type')[0].value;
                const state = document.getElementsByName('state')[0].value;
                const city = document.getElementsByName('city')[0].value;
                const busu = document.getElementsByName('busu')[0].value;

                const resp = await fetch(`./api/getCost.php?state=${state}&type=${type}&city=${city}&busu=${busu}`);
                if (!resp.ok) {
                    return;
                }
                const resp_json = await resp.json();
                if (resp_json.result) {
                    // 価格の表示
                    document.getElementById('label_cost').innerText = resp_json.cost.toLocaleString();
                    document.getElementsByName('cost')[0].value = resp_json.cost.toLocaleString();
                    $(function(){
                        if ($("#send-info").is(":hidden")) {
                            $("#send-info").slideToggle();
                        }
                    });
                    changeRequestElement();
                } else {
                    // エラーメッセージの表示
                    document.getElementById('label_error').innerText = returnErrorMessage(resp_json);
                }
            }

            window.addEventListener("load", function(event) {
                document.getElementById('mail').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const type = document.getElementsByName('type')[0].value;
                    const state = document.getElementsByName('state')[0].value;
                    const city = document.getElementsByName('city')[0].value;
                    const busu = document.getElementsByName('busu')[0].value;
                    const cost = document.getElementsByName('cost')[0].value;
                    const company = document.getElementsByName('company')[0].value;
                    const name = document.getElementsByName('name')[0].value;
                    const mail = document.getElementsByName('mail')[0].value;
                    const privacy = document.getElementsByName('privacy')[0].checked;
                    const url = document.getElementById('mail').getAttribute("action") + `?type=${type}&state=${state}&city=${city}&busu=${busu}&cost=${cost}&company=${company}&name=${name}&mail=${mail}&privacy=${privacy}`;
                    fetch(url).then((e) => {
                        if (e.redirected) {
                            window.location.href = e.url;
                        } else {
                            console.log(e.json());
                        }
                    })
                })
            })

        </script>
        <script>
            // この状況があり得るか？（確認画面遷移した後はあり得るかな？）
            window.addEventListener("load", function(){
                const type = "<?= array_key_exists('type', $_GET) ? $_GET['type'] : '' ?>";
                const state = "<?= array_key_exists('state', $_GET) ? $_GET['state'] : '' ?>";
                const city = "<?= array_key_exists('city', $_GET) ? $_GET['city'] : '' ?>";
                const busu = "<?= array_key_exists('busu', $_GET) ? $_GET['busu'] : '' ?>";
                const cost = "<?= array_key_exists('cost', $_GET) ? $_GET['cost'] : '' ?>";
                const company = "<?= array_key_exists('company', $_GET) ? $_GET['company'] : '' ?>";
                const name = "<?= array_key_exists('name', $_GET) ? $_GET['name'] : '' ?>";
                const mail = "<?= array_key_exists('mail', $_GET) ? $_GET['mail'] : '' ?>";

                if (type) {
                    set_option_from_value(document.getElementsByName('type')[0], type);
                }
                if (state) {
                    set_option_from_value(document.getElementsByName('state')[0], state);
                    setCityList(state);
                }
                if (city) {
                    set_option_from_value(document.getElementsByName('city')[0], city);
                }
                if (busu) {
                    set_option_from_value(document.getElementsByName('busu')[0], busu);
                }
                if (company) {
                    document.getElementsByName('company')[0].value = company;
                }
                if (name) {
                    document.getElementsByName('name')[0].value = name;
                }
                if (mail) {
                    document.getElementsByName('mail')[0].value = mail;
                }

                changeCostEstimate();
                if (cost) {
                    document.getElementById('label_cost').innerText = cost;
                    document.getElementsByName('cost')[0].value = cost;
                    $("#send-info").slideDown();
                }
                changeRequestElement();
            }, false);
        </script>
        <script>
            // ページアニメーション用
            document.addEventListener('DOMContentLoaded', (event) => {
                // ボタンにクリックイベントリスナーを追加
                document.querySelectorAll('button[data-href]').forEach(button => {
                    button.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-href');
                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            // スムーズスクロールを実行
                            targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });
            });
            $(function(){
              $("a[href^='#']").click(function(){
                const target = $(this.hash);
                const position = $(target).offset().top;
                $('html,body').animate({scrollTop: position}, 400);
                return false;
              });
            });
        </script>

    </head>
    <body>
        <div class="container mx-auto bg-white h-full">
            <!-- ヘッダー -->
            <div class="relative bg-slate-100 h-56">
                <div class="grid grid-cols-1 lg:grid-cols-3 px-10 lg:px-80 gap-2 lg:gap-5 items-center justify-items-center lg:justify-items-start absolute bottom-5 w-full">
                    <button class="bg-sky-400 text-white h-10 w-40 lg:w-full" data-href="#mail" onclick="document.getElementsByName('type')[0].value='1';">PR</button>
                    <button class="bg-sky-400 text-white h-10 w-40 lg:w-full" data-href="#mail" onclick="document.getElementsByName('type')[0].value='2';">集客</button>
                    <button class="bg-sky-400 text-white h-10 w-40 lg:w-full" data-href="#mail" onclick="document.getElementsByName('type')[0].value='3';">求人</button>
                </div>
            </div>

            <!-- メインコンテンツ -->
            <form class="bg-slate-100 my-5 py-2 text-gray-700" id="mail" action="./validate.php" method="get">
                <h1 class="text-4xl py-5 font-bold text-center">簡易見積</h1>
                <div class="bg-white mx-auto rounded px-5 lg:px-10 py-5 w-95/100">
                    <label class="text-red-600" id="label_error"></label>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col lg:flex-row gap-2 lg:items-center">
                            <label class="lg:w-1/5">配布種類</label>
                            <select class="lg:w-4/5 rounded border border-gray-400" name="type" onchange="changeCostEstimate();changeRequestElement();">
                                <option value="">選択してください</option>
                                <option value="1">PR</option>
                                <option value="2">集客</option>
                                <option value="3">求人</option>
                            </select>
                        </div>
                        <div class="flex flex-col lg:flex-row gap-2 lg:items-center">
                            <label class="lg:w-1/5">都道府県</label>
                            <select class="lg:w-4/5 rounded border border-gray-400"name="state" onchange="setCityList(this.value); changeCostEstimate();changeRequestElement();">
                                <option value="">選択してください</option>
                                <?php foreach(array_unique($states) as $state) {
                                    echo "<option value=\"$state\">$state</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="flex flex-col lg:flex-row gap-2 lg:items-center">
                            <label class="lg:w-1/5">市区町村</label>
                            <select class="lg:w-4/5 rounded border border-gray-400" name="city" onchange="changeCostEstimate();changeRequestElement();">
                                <option value="">選択してください</option>
                            </select>
                        </div>
                        <div class="flex flex-col lg:flex-row gap-2 lg:items-center">
                            <label class="lg:w-1/5">配布部数</label>
                            <select class="lg:w-4/5 rounded border border-gray-400" name="busu" onchange="changeCostEstimate();changeRequestElement();">
                                <option value="">選択してください</option>
                                <option value="5000">5,000部～</option>
                                <option value="10000">10,000部～</option>
                                <option value="20000">20,000部～</option>
                                <option value="30000">30,000部～</option>
                                <option value="40000">40,000部～</option>
                                <option value="50000">50,000部～</option>
                                <option value="60000">60,000部～</option>
                                <option value="70000">70,000部～</option>
                                <option value="80000">80,000部～</option>
                                <option value="90000">90,000部～</option>
                                <option value="100000">100,000部以上～</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 py-5 items-center justify-items-center">
                        <input type="hidden" name="cost" />
                        <button class="rounded text-white disabled:text-gray-500 bg-amber-500 disabled:bg-gray-400 h-10 w-52" type="button" id="get-cost" onclick="postData()" disabled>見積を算出する</button>
                        <div>
                            見積金額は
                            <label id="label_cost">---</label>
                            です。
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-4 top-20" id="send-info" style="display:none">
                        <div class="flex flex-col lg:flex-row gap-2 lg:items-center">
                            <label class="lg:w-1/5">貴社名</label>
                            <input class="lg:w-4/5 rounded border border-gray-400" type="text" name="company" value="" onchange="changeRequestElement()" placeholder="例）株式会社○○○○" />
                        </div>
                        <div class="flex flex-col lg:flex-row gap-2 lg:items-center">
                            <label class="lg:w-1/5">ご担当者名</label>
                            <input class="lg:w-4/5 rounded border border-gray-400" type="text" name="name" value="" onchange="changeRequestElement()" placeholder="例）投函太郎" />
                        </div>
                        <div class="flex flex-col lg:flex-row gap-2 lg:items-center">
                            <label class="lg:w-1/5">メールアドレス</label>
                            <input class="lg:w-4/5 rounded border border-gray-400" type="text" name="mail" value="" onchange="changeRequestElement()" placeholder="例）mail@mail.om" />
                        </div>
                        <div class="grid grid-cols-1 py-5 items-center justify-items-center">
                            <div>
                                <input class="rounded" type="checkbox" name="privacy" onclick="changeRequestElement()" />
                                <a href="privacy/" target="_blank">個人情報の取り扱い</a>に同意します。
                            </div>
                            <button class="rounded px-2 text-white disabled:text-gray-500 bg-amber-500 disabled:bg-gray-400 h-10 w-52" type="submit" disabled id="send-request">正式見積を依頼する</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
