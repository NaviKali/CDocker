@link{CSSURL/bootstrap.min.css}link@
@link{CSSURL/tank.css}link@
<style>
    body {
        background-color: hsl(210, 16.7%, 97.6%) !important;
    }

    main {
        width: 100%;
        position: absolute;
        display: flex;
        top: 0;
        justify-content: center;
        align-items: center;
        align-content: center;
    }

    .imagesList {
        display: grid;
        grid-template-columns: auto auto auto auto;
        justify-content: space-around;

    }

    .imagesItem {
        border-radius: 6px;
        flex-wrap: wrap;
        align-items: center;

        img {

            width: 80%;
        }
    }
</style>
[Start]
$(
//*获取请求路径
t-var("REQUEST_URL",(new tank\Env\env)->get("ADMIN_REQUEST_URL"));
)$
[includeNavbar]

<main>
    $(
    self::Start("component/alert",[
    "id"=>"homeAlert",
    "content"=>"",
    ]);
    )$
</main>

<nav aria-label="breadcrumb" class="m-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./home">首页</a></li>
    <li class="breadcrumb-item active" aria-current="page">镜像</li>
    <li class="breadcrumb-item active" aria-current="page">镜像列表</li>
  </ol>
</nav>


<div class="imagesList">
    @for($i =1 ; $i < sizeof($TK["imagesData"]["getImagesList"]);$i++){ echo '
    <div class="card imagesItem m-2 p-2 shadow-sm" style="width: 18rem;">
        <img src="IMAGEURL/' .$TK['imagesData']['getImagesList'][$i][0].'.png" class="card-img-top " alt="...">
        <div class="card-body">
            <h5 class="card-title">
                '.$TK["imagesData"]["getImagesList"][$i][0].":".$TK["imagesData"]["getImagesList"][$i][1].'</h5>
            <p class="card-text">
                <strong>'.$TK["imagesData"]["getImagesList"][0][2].'</strong>: <span>'.$TK["imagesData"]["getImagesList"][$i][2].'</span>
                <strong>'.$TK["imagesData"]["getImagesList"][0][3].'</strong>: <span>'.$TK["imagesData"]["getImagesList"][$i][3].'</span>
                <strong>'.$TK["imagesData"]["getImagesList"][0][4].'</strong>: <span>'.$TK["imagesData"]["getImagesList"][$i][4].'</span>
            </p>
            <button type="button" class="btn btn-danger" onclick="handleDeleteImage(`'.$TK['imagesData']['getImagesList'][$i][2].'`)">删除</button>
        </div>
    </div>
';
}for@
</div>


[/Start]
@script{JSURL/bootstrap.bundle.min.js}script@
@script{JSURL/jquery.min.js}script@
@script{JSURL/tank.js}script@
@script{JSURL/tankConst.js}script@
@script{JSURL/tankConst.js}script@
<script>


function handleDeleteImage(id){
    $.ajax({
      method: "POST",
      url: "$(echo t-get("REQUEST_URL"))$Docker/deleteImage",
      data: JSON.stringify({
        image: btoa(id)
      }),
      headers: {
        "Content-Type": "application/json"
      },
      dataType: "json",
      success: function (successData) {
        AlertStart(ALERT_ANIMATION_TOP_MOVE, 200, function () {
          if (isRequestSuccess(successData)) {
            changeAlertType("alert-success")
            alertId.innerHTML = successData.msg;
            setTimeout(() => {
              location.reload()
            }, 2000);
          } else {
            changeAlertType("alert-danger")
            alertId.innerHTML = successData.msg;
          }
        })
      }
    })
}

</script>