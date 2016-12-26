<?php
$book_list = array();

array_push($book_list, '9788970754710'); //왕자의특권
array_push($book_list, '9788932027173'); //그것이나만은아니기를
array_push($book_list, '9788954606837'); //흐르는강물처럼
array_push($book_list, '9788954612999'); //브리다
array_push($book_list, '9788956055466'); //책은도끼다
array_push($book_list, '9788965702047'); //내가미친8주간의기록
array_push($book_list, '9788954610971'); //어두운상점들의거리
array_push($book_list, '9788932915708'); //한여자
array_push($book_list, '9788932915678'); //남자의자리
array_push($book_list, '9788961570121'); //어머니의죽음
array_push($book_list, '9788954625173'); //불륜
array_push($book_list, '9788994120997'); //지적대화를위한넓고얕은지식
array_push($book_list, '9791195677108'); //시민의교양
array_push($book_list, '9788954620970'); //아크라문서
array_push($book_list, '9788954616126'); //알레프
array_push($book_list, '9791186560204'); //명견만리1
array_push($book_list, '9791186560167'); //명견만리2
array_push($book_list, '9788954642019'); //스파이
array_push($book_list, '9788937489327'); //무의미의축제
array_push($book_list, '9788965745280'); //카인
array_push($book_list, '9788954640190'); //2016젊은작가상수상작품집
array_push($book_list, '9788932916194'); //창문넘어도망친100세노인
array_push($book_list, '9788954637756'); //개인주의자선언
array_push($book_list, '9788956056609'); //다시책은도끼다
array_push($book_list, '9788954432160'); //마크툽
array_push($book_list, '9791156756552'); //죽여마땅한사람들
array_push($book_list, '9788952235268'); //편의점인간

// print_r($book_list);

$book_shelf = array();

foreach ($book_list as $book) {
  $file = './log/'.$book.'.log';

  //책 정보가 로그로 저장되어있는지 체크
  if (file_exists($file)) {
    $response = file_get_contents($file);
  }
  //책 정보가 없으면 네이버 api를 통해 정보를 가져와서 저장한다.
  else {
    $client_id = "b4Gwe6rfZFvZRqIhDwx3";
    $client_secret = "qKpwtXPDFj";
    $isbn = $book;

    $url = "https://openapi.naver.com/v1/search/book_adv.json?d_isbn=".$isbn;
    $is_post = false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = array();
    $headers[] = "X-Naver-Client-Id: ".$client_id;
    $headers[] = "X-Naver-Client-Secret: ".$client_secret;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec ($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close ($ch);
    if ($status_code == 200) {
      file_put_contents($file, $response);
    } else {
      // echo "Error 내용:".$response;
      continue;
    }
  }

  $obj = json_decode($response);
  $items = $obj->items;

  $image = $items[0]->image;

  $arr = array('isbn'=>$book, 'image'=>$image);
  array_push($book_shelf, $arr);
}
?>
<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <!-- 부트스트랩 -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE8 에서 HTML5 요소와 미디어 쿼리를 위한 HTML5 shim 와 Respond.js -->
    <!-- WARNING: Respond.js 는 당신이 file:// 을 통해 페이지를 볼 때는 동작하지 않습니다. -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    .title {line-height:50px;}
    .book {
    	padding: 10px 0 0 0;
    	margin: auto;
      -webkit-box-shadow: 2px 2px 5px rgba(0,0,0,.6);
      box-shadow: 2px 2px 5px rgba(0,0,0,.6);
    }
    .shelf {
    	background: url('bookshelf.png');
      background-size: 100% 100%;
      height: 90px;
      top: -15px;
      z-index: -1;
    }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <h4 class="col-xs-12 title">책나눔</h4>
      </div>

      <div class="row">
        <?php
        $hidden_num = 0;
        $shelf_num = 0;

        foreach ($book_shelf as $book) {
          ?>
          <div class="col-xs-4 col-md-2">
            <a href="#;" data-toggle="modal" data-target="#bookModal" data-whatever="<?= $book['isbn'];?>"><img src="<?= $book['image'];?>" class="img-responsive book"></a>
         	</div>
          <?php

          $hidden_num ++;
          $shelf_num++;

          if ($hidden_num == 3) {
            echo '<div class="col-xs-12 shelf hidden-md hidden-lg"></div>';
          }
          if ($shelf_num == 6) {
            echo '<div class="col-xs-12 shelf"></div>';
            $hidden_num = 0; $shelf_num = 0;
          }
        }

        if ($shelf_num < 6) echo '<div class="col-xs-12 shelf"></div>';
        ?>
      </div>
    </div>

    <div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="bookModalIsbn">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel"></h4>
          </div>
          <div class="modal-body">
            <form>
              <!-- <div class="form-group">
                <label for="recipient-name" class="control-label">제목</label>
                <div class="info-title"></div>
              </div> -->
              <div class="form-group">
                <label for="" class="control-label">작가</label>
                <div class="info-author"></div>
              </div>
              <div class="form-group">
                <label for="" class="control-label">설명</label>
                <div class="info-description"></div>
              </div>
              <div class="form-group">
                <label for="" class="control-label"><a href='' target='_blank' class='info-link'>네이버에서 정보 더 보기</a></label>
              </div>
              <div class="form-group">
                <label for="" class="control-label">이 책을 받아 보고 싶다면...받는이/주소를 입력해주세요.</label>
                <textarea class="form-control info-msg" rows="3"></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            <button type="button" class="btn btn-primary">예약</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
      $('#bookModal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget)
        var isbn = button.data('whatever')
        var modal = $(this);

        var title = '', author = '', description = '', link = '';
        $.getJSON("./log/"+isbn+".log", function(data) {
          title = data['items'][0]['title'];
          author = data['items'][0]['author'];
          description = data['items'][0]['description'];
          link = data['items'][0]['link'];

          modal.find('.modal-title').text(title);
          // modal.find('.modal-body .info-title').html(title);
          modal.find('.modal-body .info-author').html(author);
          modal.find('.modal-body .info-description').html(description);
          modal.find('.modal-body .info-link').attr('href',link);
        });
      });

      $('.btn-primary').on('click', function(e) {
        var t = $('.modal-title').html();
        var m = $('.info-msg').val();

        $.post('book.php', {t:t,m:m}, function(data) {
          alert('신청되었습니다');
          $('#bookModal').modal('hide')
        });
      });
    </script>
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-18031811-9', 'auto');
    ga('send', 'pageview');

  </script>
  </body>
</html>
