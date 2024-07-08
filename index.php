<?php
require_once 'lib/php/Logger/logger.php';
require_once 'lib/php/DB/db.php';
require_once 'model/InputItem/Type.class.php';
require_once 'model/InputItem/EnumType.enum.php';

use lib\Logger as logger;
use lib\db as db;

$log = logger\Logger::getInstance();
$db = db\ConnectDb::getInstance();

//SQLを作成
$sql = "SELECT state, city FROM cost GROUP BY state, city ORDER BY id";
$rows = $db->fetchAll($sql);

//出力結果をそれぞれの配列に格納
$states = array_column($rows, 'state');
$cities = array_column($rows, 'city');

//SQLを作成
$numberOfCopiesSql = "SELECT number_of_copies FROM cost GROUP BY number_of_copies ORDER BY number_of_copies";
$numberOfCopiesRows = $db->fetchAll($numberOfCopiesSql);

//配列に格納
$numberOfCopies = array_column($numberOfCopiesRows, 'number_of_copies');
?>
<!DOCTYPE html>
<html lang="ja" class="bg-sub">

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

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-161021607-1');
        gtag('config', 'AW-806634571');
    </script>
    <script>
        var rows = <?= json_encode($rows, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) ?>;

        function setCityList(select_state) {
            const city_element = document.getElementsByName("city")[0];
            const empty_option = document.createElement('option');
            empty_option.value = "";
            empty_option.innerHTML = "選択してください";

            // 要素全削除
            while (city_element.firstChild) {
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
            document.getElementById('label_cost').innerText = "見積金額は---です。";
            document.getElementsByName('cost')[0].value = "";
        }

        // 見積依頼項目に変更があった際の動作
        function changeRequestElement() {
            const elementRequestButton = document.getElementById('send-request');

            if (!document.getElementsByName('cost')[0].value) {
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

        async function getCost() {
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
            if (resp_json.Result) {
                // 価格の表示
                document.getElementById('label_cost').innerText = (resp_json.Cost == 0) ? '要正式お見積り' : '見積金額は' + Number(resp_json.Cost).toLocaleString() + '円です';
                document.getElementsByName('cost')[0].value = resp_json.Cost.toLocaleString();
                $(function() {
                    if ($("#send-info").is(":hidden")) {
                        $("#send-info").slideToggle();
                    }
                });
                changeRequestElement();
            } else {
                // エラーメッセージの表示
                document.getElementById('label_error').innerText = resp_json.ErrorMessage;
            }
        }

        async function getConfirm() {
            document.getElementById('label_error').innerText = "";

            // FoemDataオブジェクトに要素セレクタを渡して宣言する
            const type = document.getElementsByName('type')[0].value;
            const state = document.getElementsByName('state')[0].value;
            const city = document.getElementsByName('city')[0].value;
            const busu = document.getElementsByName('busu')[0].value;
            const cost = document.getElementsByName('cost')[0].value;
            const company = document.getElementsByName('company')[0].value;
            const name = document.getElementsByName('name')[0].value;
            const mail = document.getElementsByName('mail')[0].value;
            const privacy = document.getElementsByName('privacy')[0].checked;

            const resp = await fetch(`./api/validate.php?state=${state}&type=${type}&city=${city}&busu=${busu}&cost=${cost}&company=${company}&name=${name}&mail=${mail}&privacy=${privacy}`);
            if (!resp.ok) {
                return;
            }
            const resp_json = await resp.json();
            if (resp_json.Result) {
                // ページ遷移
                window.location = `./confirm.php?state=${state}&type=${type}&city=${city}&busu=${busu}&cost=${cost}&company=${company}&name=${name}&mail=${mail}&privacy=${privacy}`;
            } else {
                // エラーメッセージの表示
                document.getElementById('label_error').innerText = resp_json.ErrorMessage;
            }
        }
    </script>
    <script>
        window.addEventListener("load", function() {
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
                document.getElementById('label_cost').innerText = (cost == 0) ? '要正式お見積り' : '見積金額は' + Number(cost).toLocaleString() + '円です';;
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
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
        $(function() {
            $("a[href^='#']").click(function() {
                const target = $(this.hash);
                const position = $(target).offset().top;
                $('html,body').animate({
                    scrollTop: position
                }, 400);
                return false;
            });
        });
    </script>

</head>

<body class="font-body">
    <div class="container mx-auto space-y-5">
        <!-- ヘッダー -->
        <div class="relative bg-main h-96">
            <img src="/image/img_header.svg" class="w-full h-full object-cover object-center">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 px-10 my-5 lg:px-80 gap-2 lg:gap-5 items-center justify-items-center lg:justify-items-start w-full text-xl font-bold">
            <button class="bg-element-bg-main text-white h-10 w-40 lg:w-full border border-solid border-white" data-href="#mail" onclick="document.getElementsByName('type')[0].value='1';">PR</button>
            <button class="bg-element-bg-main text-white h-10 w-40 lg:w-full border border-solid border-white" data-href="#mail" onclick="document.getElementsByName('type')[0].value='2';">集客</button>
            <button disabled class="bg-slate-400 text-white h-10 w-40 lg:w-full border border-solid border-white" data-href="#mail" onclick="document.getElementsByName('type')[0].value='3';">求人</button>
        </div>

        <!-- メインコンテンツ -->
        <!-- ポスティング広告の力 -->
        <div class="bg-main pb-2 pt-2 text-gray-700">
            <div class="bg-white mx-auto rounded px-5 my-5 lg:px-10 py-5 w-95/100 text-center">
                <h1 class="text-2xl mx-auto">手軽に始める効果的な宣伝</h1>
                <h1 class="text-4xl font-bold text-text-main px-auto">ポスティング広告の力</h1>
                <div class="pt-2 space-y-2 mt-5">
                    <!-- 実績1 -->
                    <div class="bg-element-bg-main rounded-3xl lg:rounded-full w-full text-left space-y-3 space-x-3 py-14 lg:py-4">
                        <div class="flex">
                            <div class="w-2/12 lg:w-1/12 my-auto">
                                <img class="w-28 lg:w-40 max-w-none -ml-10" src="/image/achievement_1.svg">
                            </div>
                            <div class="w-8/12 lg:w-10/12 space-y-3 my-auto">
                                <h1 class="text-text-sub font-bold border-text-sub border-b border-dotted text-xl">渋谷区 店舗オープン　イベントチラシ</h1>
                                <div class="text-white">新規開店する携帯キャリアショップのオープニングイベントの告知チラシを店
                                    舗中心に1万部選別に無しに配布。オープン当日に<span class="bg-red-500 text-lg">来場者200名、契約 30件</span>の効果に繋がり、当時のオープニング時最大の来場者となりました。
                                </div>
                                <div class="pr-10 grid grid-cols-1 lg:flex lg:flex-row-reverse">
                                    <span class="w-40 font-bold text-red-500 bg-white rounded-full text-center justify-self-end">・来店者↑</span>
                                    <span class="w-40 font-bold text-red-500 bg-white rounded-full text-center justify-self-end mt-3 lg:mt-0 lg:mr-3">・契約↑</span>
                                </div>
                            </div>
                            <div class="w-2/12 lg:w-1/12 my-auto">
                                <img class="w-20 lg:w-24 max-w-none " src="/image/store.svg">
                            </div>
                        </div>
                    </div>
                    <!-- 実績2 -->
                    <div class="bg-element-bg-main rounded-3xl lg:rounded-full w-full text-left space-y-3 space-x-3 py-14 lg:py-4">
                        <div class="flex">
                            <div class="w-2/12 lg:w-1/12 my-auto">
                                <img class="w-28 lg:w-40 max-w-none -ml-10" src="/image/achievement_2.svg">
                            </div>
                            <div class="w-8/12 lg:w-10/12 space-y-3 my-auto">
                                <h1 class="text-text-sub font-bold border-text-sub border-b border-dotted text-xl">千葉市 大手物流倉庫　求人チラシ</h1>
                                <div class="text-white">新規オープンに伴い、近隣の年収500万円以下の世帯が多いエリアをピック アップ。4万部の求人チラシを配布しました。想定20件の応募想定でしたが、その倍以上の
                                    <span class="bg-red-500 text-lg">44件の応募</span>
                                    を獲得。他媒体と比較でも、全体のうち
                                    <span class="bg-red-500 text-lg">60％が求人チラシからの応募</span>
                                    となり求人への効果を発揮しました。
                                </div>
                                <div class="pr-10 grid grid-cols-1 lg:flex lg:flex-row-reverse">
                                    <span class="w-40 font-bold text-red-500 bg-white rounded-full text-center justify-self-end">・求人応募↑</span>
                                    <span class="w-40 font-bold text-red-500 bg-white rounded-full text-center justify-self-end mt-3 lg:mt-0 lg:mr-3">・他媒体より効果↑</span>
                                </div>
                            </div>
                            <div class="w-2/12 lg:w-1/12 my-auto">
                                <img class="w-20 lg:w-24 max-w-none " src="/image/warehouse.svg">
                            </div>
                        </div>
                    </div>
                    <!-- 実績3 -->
                    <div class="bg-element-bg-main rounded-3xl lg:rounded-full w-full text-left space-y-3 space-x-3 py-14 lg:py-4">
                        <div class="flex">
                            <div class="w-2/12 lg:w-1/12 my-auto">
                                <img class="w-28 lg:w-40 max-w-none -ml-10" src="/image/achievement_3.svg">
                            </div>
                            <div class="w-8/12 lg:w-10/12 space-y-3 my-auto">
                                <h1 class="text-text-sub font-bold border-text-sub border-b border-dotted text-xl">横浜市 スーパー　クーポン付チラシ</h1>
                                <div class="text-white">スーパーの割引クーポン付チラシを店舗周辺に選別無しで5万部配布。
                                    <span class="bg-red-500 text-lg">クーポンの回収率が600枚を超え1.2％の回収率</span>となり、スーパーの売上増に貢献しました。
                                </div>

                                <div class="pr-10 grid grid-cols-1 lg:flex lg:flex-row-reverse">
                                    <span class="w-40 font-bold text-red-500 bg-white rounded-full text-center justify-self-end">・来店者↑</span>
                                    <span class="w-40 font-bold text-red-500 bg-white rounded-full text-center justify-self-end mt-3 lg:mt-0 lg:mr-3">・売上↑</span>
                                </div>
                            </div>
                            <div class="w-2/12 lg:w-1/12 my-auto">
                                <img class="w-20 lg:w-24 max-w-none " src="/image/shop.svg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 「安い!早い!丁寧!」なポスティング -->
        <div class="py-10 text-center">
            <h1 class="mb-10 text-4xl text-text-main px-auto"><span class="font-bold">「安い!早い!丁寧!」</span>なポスティング</h1>
            <!-- 首都圏に多数の拠点&全国ネットワーク -->
            <div class="bg-element-bg-main text-text-sub font-bold text-left my-5 py-3 px-5 lg:w-125 rounded-r-full">首都圏に多数の拠点&全国ネットワーク</div>
            <div class="bg-white rounded py-5 w-95/100 grid grid-cols-1 lg:grid-cols-3 gap-x-8 mx-auto">
                <span class="h-full flex items-center text-left">都内最大の自社配布体制！さらに 長年構築したネットワークを通じて、全国への配布体制を完備。お客様のご要望に寄り添った強力で安心なポスティングサービスをご提供します。</span>
                <img class="h-72 col-span-2" src="/image/tokyo.svg">
            </div>
            <!-- ジーエムピーのポスティングの特徴&強み -->
            <div class="bg-element-bg-main text-text-sub font-bold text-left my-5 py-3 px-5 lg:w-125 rounded-r-full">ジーエムピーのポスティングの特徴&強み</div>
            <ul class="text-text-main font-bold w-full grid grid-cols-1 lg:grid-cols-4 mx-auto">
                <li class="text-center">
                    <img class="mx-auto w-28 h-44" src="/image/good.svg">
                    選んで安心!業界最安級
                </li>
                <li>
                    <img class="mx-auto w-52 h-44" src="/image/map.svg">
                    エリアマーケティングでターゲット選定可能
                </li>
                <li>
                    <img class="mx-auto w-28 h-44" src="/image/flyer.svg">
                    効果的なチラシデザイン
                </li>
                <li>
                    <img class="mx-auto w-36 h-44" src="/image/bike.svg">
                    全国配布可能!
                </li>
            </ul>
        </div>

        <!-- お客様の声 -->
        <div class="bg-main pb-2 pt-2 text-gray-700">
            <h1 class="text-4xl py-5 pl-10 font-bold">お客様の声</h1>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-x-2 px-10">
                <div class="bg-white mx-auto rounded my-5 lg:px-10 py-5 w-full mr-3 pr-5">
                    <div class="grid grid-cols-1 pt-2 space-y-2">
                        準備中
                    </div>
                </div>
                <div class="bg-white mx-auto rounded my-5 lg:px-10 py-5 w-full pr-3">
                    <div class="grid grid-cols-1 pt-2 space-y-2">
                        準備中
                    </div>
                </div>
                <div class="bg-white mx-auto rounded my-5 lg:px-10 py-5 w-full">
                    <div class="grid grid-cols-1 pt-2 space-y-2">
                        準備中
                    </div>
                </div>
                <div class="bg-white mx-auto rounded my-5 lg:px-10 py-5 w-full">
                    <div class="grid grid-cols-1 pt-2 space-y-2">
                        準備中
                    </div>
                </div>
            </div>
        </div>

        <!-- 簡易見積 -->
        <form class="pb-2 text-gray-700" id="mail" action="./validate.php" method="get">
            <h1 class="text-4xl py-5 font-bold text-center">簡易見積</h1>
            <div class="bg-white mx-auto rounded px-5 lg:px-10 py-5 w-95/100">
                <label class="text-red-600" id="label_error"></label>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col lg:flex-row gap-2 lg:items-center">
                        <label class="lg:w-1/5">配布種類</label>
                        <select class="lg:w-4/5 rounded border border-gray-400" name="type" onchange="changeCostEstimate();changeRequestElement();">
                            <option value="">選択してください</option>
                            <?php foreach (EnumType::cases() as $type) {
                                echo "<option value=\"" . $type->value . "\">" . $type->text() . "</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="flex flex-col lg:flex-row gap-2 lg:items-center">
                        <label class="lg:w-1/5">都道府県</label>
                        <select class="lg:w-4/5 rounded border border-gray-400" name="state" onchange="setCityList(this.value); changeCostEstimate();changeRequestElement();">
                            <option value="">選択してください</option>
                            <?php foreach (array_unique($states) as $state) {
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
                            <option value="1000">1,000部</option>
                            <option value="2000">2,000部</option>
                            <option value="3000">3,000部</option>
                            <option value="5000">5,000部</option>
                            <option value="10000">10,000部</option>
                            <option value="30000">30,000部</option>
                            <option value="50000">50,000部</option>
                            <?php /*foreach (array_unique($numberOfCopies) as $numberOfCopy) {
                                echo "<option value=\"$numberOfCopy\">" . number_format($numberOfCopy) . "部</option>";
                            } */ ?>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 py-5 items-center justify-items-center">
                    <input type="hidden" name="cost" />
                    <button class="rounded text-white disabled:text-gray-500 bg-amber-500 disabled:bg-gray-400 h-10 w-52" type="button" id="get-cost" onclick="getCost()" disabled>見積を算出する</button>
                    <label id="label_cost"></label>
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
                    <div class="grid grid-cols-1 py-5 items-center justify-items-center text-center">
                        <div>
                            <div>
                                <input class="rounded" type="checkbox" name="privacy" onclick="changeRequestElement()" title="個人情報の取り扱いを開いてください" disabled />
                                <a class="text-blue-600 underline" href="https://www.gmp-inc.net/company/privacy.html" target="_blank" onclick="document.getElementsByName('privacy')[0].disabled = false">個人情報の取り扱い</a>に同意します。
                            </div>
                            <div class="text-xs">チェックボックスにチェックを入れる前に必ず個人情報の取り扱いを開いてください</div>
                            <button class="rounded my-5 px-2 text-white disabled:text-gray-500 bg-amber-500 disabled:bg-gray-400 h-10 w-52" type="button" onclick="getConfirm()" disabled id="send-request">正式見積を依頼する</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</body>

</html>