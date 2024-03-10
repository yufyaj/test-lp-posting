<?php
    require 'Logger/logger.php';
    
    $log = Logger::getInstance();
    
    // TODO: ここは共通化すべきでは？
    $db['dbname'] = "mariadb";  // データベース名
    $db['user'] = "mariadb";  // ユーザー名
    $db['pass'] = "mariadb";  // ユーザー名のパスワード
    $db['host'] = "127.0.0.1:3306";  // DBサーバのURL
    
    $dsn = sprintf('mysql:host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']); 
    
    try {
        //PDOを使ってMySQLに接続
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        
        //SQLを作成
        $sql = "SELECT state, city FROM price GROUP BY state, city";
        
        //$pdoにあるqueryメソッドを呼び出してSQLを実行
        $stmt = $pdo->query($sql);
    
        //出力結果を$rowに代入
        $rows = $stmt->fetchAll();
        
        //出力結果をそれぞれの配列に格納
        $states = array_column($rows,'state');
        $cities = array_column($rows,'city'); 
    
    } catch (PDOException $e) {
        $errorMessage = 'データベースエラー';
        $log->info($errorMessage);
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TPHC7GV');</script>
        <!-- End Google Tag Manager -->
        <link rel="canonical" href="https://posting-m.com/">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta name="format-detection" content="telephone=no">
        <title>ポスティング見積.com -簡単3ステップでお見積！安心・信頼の高品質ポスティングをご提案します-</title>
        <meta name="Description" content="ポスティング見積.comでは、専門の担当者がお客様の要望をヒアリングし、配布エリア・配布単価・配布日程によって、最適な会社での御提案・お見積りをお届けいたします。一括見積のサイトと異なり、複数のポスティング会社から見積もりを取る必要はありません。わずらわしい業務は全てポスティング見積.comにお任せください！">
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:400,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/init.css">
        <link rel="stylesheet" href="style.css">
        <script src="https://posting-m.com/js/jquery-3.4.1.min.js"></script>
        <script src="https://posting-m.com/js/page.js"></script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-161021607-1"></script>
        <script src="utils.js"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-161021607-1');
            gtag('config', 'AW-806634571');
        </script>
        <!-- <script async src="https://s.yimg.jp/images/listing/tool/cv/ytag.js"></script> -->
        <script>
            window.yjDataLayer = window.yjDataLayer || [];
            function ytag() { yjDataLayer.push(arguments); }
            ytag({"type":"ycl_cookie"});
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
            // 価格の初期化
            function clearPrice() {
                document.getElementById('label_price').innerText = "---";
                document.getElementsByName('price')[0].value = "";
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

            async function postData(event) {
                document.getElementById('label_error').innerText = "";

                // FoemDataオブジェクトに要素セレクタを渡して宣言する
                const type = document.getElementsByName('type')[0].value;
                const state = document.getElementsByName('state')[0].value;
                const city = document.getElementsByName('city')[0].value;
                const busu = document.getElementsByName('busu')[0].value;

                const resp = await fetch(`./getPrice.php?state=${state}&type=${type}&city=${city}&busu=${busu}`);
                if (!resp.ok) {
                    return;
                }
                const resp_json = await resp.json();
                if (resp_json.result) {
                    // 価格の表示
                    document.getElementById('label_price').innerText = resp_json.price.toLocaleString();
                    document.getElementsByName('price')[0].value = resp_json.price.toLocaleString();
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
                    const price = document.getElementsByName('price')[0].value;
                    const company = document.getElementsByName('company')[0].value;
                    const name = document.getElementsByName('name')[0].value;
                    const mail = document.getElementsByName('mail')[0].value;
                    const privacy = document.getElementsByName('privacy')[0].checked;
                    const url = document.getElementById('mail').getAttribute("action") + `?type=${type}&state=${state}&city=${city}&busu=${busu}&price=${price}&company=${company}&name=${name}&mail=${mail}&privacy=${privacy}`;
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
                const price = "<?= array_key_exists('price', $_GET) ? $_GET['price'] : '' ?>";
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
                if (price) {
                    document.getElementById('label_price').innerText = price;
                    document.getElementsByName('price')[0].value = price;
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
            }, false);
        </script>

    </head>
    <body>
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TPHC7GV" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->
        <div class="spmenumsk">
        </div>
        <header>
            <!-- <div class="hwrapper">
                <div class="left">
                    <h1>
                        <a href="">
                            <img src="https://posting-m.com/images/logo.svg" alt="ポスティング見積.com">
                        </a>
                    </h1>
                </div>
                <div class="right">
                    <div class="menu01">
                        <a href="tel:05035570187" class="htel">TEL：050-3550-0735</a>
                        <a href="#form" class="hmitsumori">さっそく見積りを依頼する</a>
                    </div>
                </div>
            </div> -->
        </header>
        <div class="mv fsec">
            <div>
                <img src="https://posting-m.com/images/mv.png" alt="" class="">
                <a href="#form"></a>
            </div>
        </div>
        <button onclick="location.href='#form';document.getElementsByName('type')[0].value='1';">PR</button>
        <button onclick="location.href='#form';document.getElementsByName('type')[0].value='2';">集客</button>
        <button onclick="location.href='#form';document.getElementsByName('type')[0].value='3';">求人</button>
        <div class="fbox">
            <section id="form">
                <form id="mail" action="./validate.php" method="get">
                    <label id="label_error"></label>
                    <select name="type" onchange="clearPrice()">
                        <option value="">選択してください</option>
                        <option value="1">PR</option>
                        <option value="2">集客</option>
                        <option value="3">求人</option>
                    </select>
                    <select name="state" onchange="setCityList(this.value); clearPrice();">
                        <option value="">選択してください</option>
                        <?php foreach(array_unique($states) as $state) {
                            echo "<option value=\"$state\">$state</option>";
                        } ?>
                    </select>
                    <select name="city" onchange="clearPrice()">
                        <option value="">選択してください</option>
                    </select>
                    <select name="busu" onchange="clearPrice()">
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
                    <div id="get_price" onclick="postData()">価格取得</div>
                    <label id="label_price"></label>
                    <input type="hidden" name="price" />
                    <input type="text" name="company" value="" placeholder="例）株式会社○○○○" />
                    <input type="text" name="name" value="" placeholder="例）投函太郎" />
                    <input type="text" name="mail" value="" placeholder="例）mail@mail.om" />
                    <input type="checkbox" name="privacy" />
                    <a href="privacy/" target="_blank">個人情報の取り扱い</a>に同意します。
                    <input type="submit" name="" value="入力内容を確認をする" class="btn2">
                </form>
            </section>
        </div>
        <div class="wrapper">
            <section id="form">
                <h2>お見積はこちらから</h2>
                <p>※お電話でのご相談をご希望の方は
                    <a href="tel:05035570187" class="">050-3550-0735</a>（受付時間 平日10:00～19:00）までお願いいたします。
                </p>
                <div class="fbox">
                    <form class="" action="mail.php" method="post">
                        <div class="forma bb">
                            <h3>お客様情報を入力</h3>
                            <table>
                                <tbody id="slide">
                                    <tr>
                                        <th>
                                            <div>お名前
                                            <span>必須</span>
                                            </div>
                                        </th>
                                        <td>
                                            <input type="text" name="お名前" value="" placeholder="例）投函太郎">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div>ご住所
                                            <span>必須</span>
                                            </div>
                                        </th>
                                        <td>
                                            <input type="text" name="ご住所" value="" placeholder="例）東京都○○区○○町1-2-3">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div>電話番号
                                            <span>必須</span>
                                            </div>
                                        </th>
                                        <td>
                                            <input type="text" name="電話番号" value="" placeholder="例）○○-○○○○-○○○○">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div>メールアドレス
                                            <span>必須</span>
                                            </div>
                                        </th>
                                        <td>
                                            <input type="text" name="メールアドレス" value="" placeholder="例）mail@mail.om">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="nini">会社名
                                            </div>
                                        </th>
                                        <td>
                                            <input type="text" name="会社名" value="" placeholder="例）株式会社○○○○">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="kojin">
                                            <div>個人情報の
                                            <br>取り扱いについて
                                            <span>必須</span>
                                            </div>
                                        </th>
                                        <td class="checkbox">
                                            <label for="r01">
                                                <input type="checkbox" name="個人情報の取り扱いについて" value="個人情報の取り扱いに同意します。" id="r01">
                                                <span></span>
                                                <a href="privacy/" target="_blank">個人情報の取り扱い</a>に同意します。
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="nini">配布希望部数
                                            <!--<span>任意</span>-->
                                            </div>
                                        </th>
                                        <td>
                                            <select name="配布希望部数">
                                                <option value="">選択してください</option>
                                                <option value="5,000部～">5,000部～</option>
                                                <option value="10,000部～">10,000部～</option>
                                                <option value="20,000部～">20,000部～</option>
                                                <option value="30,000部～">30,000部～</option>
                                                <option value="40,000部～">40,000部～</option>
                                                <option value="50,000部～">50,000部～</option>
                                                <option value="60,000部～">60,000部～</option>
                                                <option value="70,000部～">70,000部～</option>
                                                <option value="80,000部～">80,000部～</option>
                                                <option value="90,000部～">90,000部～</option>
                                                <option value="100,000部以上～">100,000部以上～</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="nini hfjk">配布条件
                                            <!--<a>？</a>-->
                                            <!--<span>任意</span>-->
                                            </div>
                                        </th>
                                        <td>
                                            <select name="配布条件">
                                                <option value="">選択してください</option>
                                                <option value="軒並み">軒並み</option>
                                                <option value="集合（アパート・マンションのみ）">集合（アパート・マンションのみ）</option>
                                                <option value="戸建のみ">戸建のみ</option>
                                                <option value="指定マンション">指定マンション</option>
                                                <option value="会社・事務所">会社・事務所</option>
                                                <option value="その他">その他</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="nini">配布希望エリア
                                            </div>
                                        </th>
                                        <td>
                                            <input type="text" name="配布希望エリア" value="" placeholder="例）東京都○○区">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="nini">広告サイズ
                                            </div>
                                        </th>
                                        <td>
                                            <div class="nini">
                                            <select name="広告サイズ">
                                                <option value="">選択してください</option>
                                                <option value="A4">A4</option>
                                                <option value="A3">A3</option>
                                                <option value="B5">B5</option>
                                                <option value="B4">B4</option>
                                                <option value="B3">B3</option>
                                                <option value="その他">その他</option>
                                            </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="nini">配布予定期間
                                            </div>
                                        </th>
                                        <td>
                                            <input type="text" name="配布予定期間" value="" placeholder="例）○○年○○月○○日">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="nini">印刷依頼の有無
                                            </div>
                                        </th>
                                        <td>
                                            <select name="印刷依頼の有無">
                                                <option value="">選択してください</option>
                                                <option value="あり">あり</option>
                                                <option value="なし">なし</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="nini">印刷内容
                                            </div>
                                        </th>
                                        <td>
                                            <input type="text" name="印刷内容" value="" placeholder="例）新規オープンの告知">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="textarea">
                                            <div>備考
                                            </div>
                                        </th>
                                        <td>
                                            <textarea name="備考" value=""></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p style="display: table;margin: 30px auto -10px;">※大変恐れ入りますが、貸金業のお客様に関しましてはご依頼を承ることができませんので、予めご了承ください。</p>
                        <input type="submit" name="" value="入力内容を確認をする" class="btn2">
                    </form>
                </div>
            </section>
            <section class="sec1">
                <div>
                    <p>
                        <span class="yline cblue">ポスティング見積.com</span>では、専門の担当者がお客様のご要望を適切にくみ取り、
                        <br class="pc">最適なポスティング会社を選別して、御提案・お見積もりします。
                        <br class="pc">お客様が複数のポスティング会社からお見積もりを取るお手間は、一切不要です。
                    </p>
                    <p>お客様の開店・開業・新たな販売促進を、ご要望の地域に基づいて、力強くサポートします。</p>
                    <p>ポスティングサービスにおいては、
                        <span class="yline">安くて早いから良いという事は決してありません。</span>
                        <br class="pc">配布依頼して良かったと思える結果となるお客様の満足値や目的を達成できるように
                        <br class="pc">要望にあった予算や配布を行うことが大事なのです。
                    </p>
                    <p>
                        <span class="yline cblue">満足できる結果となるように、ポスティング見積.com にお任せください！</span>
                    </p>
                </div>
            </section>
            <section id="content">
                <div class="posting" id="posting">
                    <h2>ポスティングとは</h2>
                    <ul>
                        <li>
                            <p>様々な販促物（チラシ・試供品）を
                                <span class="yline">ダイレクトに</span>各家庭に配布できます。
                            </p>
                        </li>
                        <li>
                            <p>
                                <span class="yline">日付を指定したりエリアを絞ったり、建物の形状で選別したり</span>するなどの
                                <span class="yline">柔軟性</span>を備えております。
                            </p>
                        </li>
                        <li>
                            <p>大半の方は毎日ポストを確認されますので、
                                <span class="yline">手にとってもらい・見てもらえる</span>訴求力の高い広告手法です。
                            </p>
                        </li>
                        <li>
                            <p>マスメディア（テレビ・ラジオ）と違い、
                                <span class="yline">コストが安く、興味・魅力がある広告物は長く保存される</span>のが特徴です。
                            </p>
                        </li>
                        <li>
                            <p>
                                <span class="yline">高いカバー率（70%～80%）</span>で様々な世帯へ行きわたりますので、
                                <span class="yline">無駄の少ない</span>販売促進活動が実現できます。
                            </p>
                        </li>
                    </ul>
                    <table>
                        <caption>紙媒体を使った販促手法比較</caption>
                        <thead>
                            <tr>
                                <th>手法</th>
                                <th>メリット</th>
                                <th>デメリット</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>ポスティング</th>
                                <td>
                                    <ul>
                                        <li>地域別に配布ができる</li>
                                        <li>世帯カバー率が高い</li>
                                        <li>居住形態別の選別配布が可能</li>
                                        <li>様々な宣伝物の配布ができる</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>新聞折込に比べるとコストが高い</li>
                                        <li>DMほどターゲットを絞れない</li>
                                        <li>クレームがでることがある</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <th>DM</th>
                                <td>
                                    <ul>
                                        <li>手元まで確実に届く</li>
                                        <li>レスポンス率が高い</li>
                                        <li>客層が特定できる</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>コストが高い</li>
                                        <li>配布まで時間がかかる</li>
                                        <li>顧客・ターゲットリストが必要</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <th>新聞折込</th>
                                <td>
                                    <ul>
                                        <li>コストが安い</li>
                                        <li>地域別に配布ができる</li>
                                        <li>一日で大量部数の配布ができる</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>世帯カバー率が低い</li>
                                        <li>居住形態別の選別配布が不可能</li>
                                        <li>広告宣伝物に制限がある</li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
        <footer>
            <div class="yellow">
                <div class="wrapper">
                    <h1>
                        <a href="">ポスティング見積.com</a>
                    </h1>
                    <div class="link">
                    <a href="privacy/index.html">
                        <span>運営会社 / 個人情報の取り扱いについて</span>
                    </a>
                    </div>
                </div>
            </div>
            <div class="blue">
                <span>© 2019ポスティング見積.com </span>
            </div>
        </footer>
        <div id="toPageTop">
            <div>
            </div>
        </div>
        <div id="hfjktc">
            <div>
            <p>配布条件</p>
                <div>
                    <table>
                        <tr>
                            <th>軒並</th>
                            <td>戸建・マンション・アパートのすべてを対象とする最も標準的な配布。通常、世帯数の70～80％を設定数とする。ローラー配布、ベタまきとも呼ばれる。</td>
                        </tr>
                        <tr>
                            <th>集合</th>
                            <td>マンション・アパートを対象とする配布。（※戸建は除く）。</td>
                        </tr>
                        <tr>
                            <th>戸建</th>
                            <td>一軒家を対象とする配布。</td>
                        </tr>
                        <tr>
                            <th>分譲マンション</th>
                            <td>分譲マンションのみ配布（※通常、配布員の目視判断による）。</td>
                        </tr>
                        <tr>
                            <th>賃貸</th>
                            <td>賃貸マンション・アパートのみ配布（※通常、配布員の目視判断による）</td>
                        </tr>
                        <tr>
                            <th>民間集合</th>
                            <td>都営・県営・市営・公団・公社・UR・官舎を除く集合配布。</td>
                        </tr>
                        <tr>
                            <th>民間賃貸</th>
                            <td>都営・県営・市営・公団・公社・UR・官舎を除く賃貸配布。</td>
                        </tr>
                        <tr>
                            <th>社宅</th>
                            <td>社員用アパート・マンションのみ配布。</td>
                        </tr>
                        <tr>
                            <th>全戸</th>
                            <td>全世帯を対象にした配布。</td>
                        </tr>
                        <tr>
                            <th>会社・事務所</th>
                            <td>会社ビル・雑居ビル・事業所・飲食店等を対象にした配布。</td>
                        </tr>
                        <tr>
                            <th>団地</th>
                            <td>都営・県営・市営・公団・公社・UR等を対象にした配布。</td>
                        </tr>
                        <tr>
                            <th>指定物件</th>
                            <td>お客様からのご希望の物件（例えばマンションリストなど）への配布。</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <script>
            window.yjDataLayer = window.yjDataLayer || [];
            function ytag() { yjDataLayer.push(arguments); }
            ytag({
            "type":"yjad_retargeting",
            "config":{
                "yahoo_retargeting_id": "WBPJO6G9BY",
                "yahoo_retargeting_label": "",
                "yahoo_retargeting_page_type": "",
                "yahoo_retargeting_items":[
                {item_id: '', category_id: '', price: '', quantity: ''}
                ]
            }
            });
        </script>
    </body>
</html>
