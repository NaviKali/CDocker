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


  .Home-Body {
    height: 94%;
    display: flex;
    justify-content: left;
    align-items: top;
  }

  .container-list {
    height: 98%;
    width: 25%;
    background-color: white;
    overflow: scroll;
    border-radius: 6px;
    padding: 10px;
  }

  .container-item {
    transition: all 0.2s;
    padding: 10px;
    transform: scale(0.9);
    border-radius: 6px;
    opacity: 0;
    box-shadow: 0px 2px 10px 1px rgb(213, 213, 213);
  }

  .container-item:hover {
    box-shadow: 0px 2px 10px 8px rgb(213, 213, 213);
  }

  .group {
    transition: all 0.3s;
    cursor: copy;
    opacity: 0;
  }
  .group:hover{
    transform: scale(0.9);
  }

  .Home-Body-Right {
    display: flex;
    justify-content: left;
    align-items: top;
    width: 100%;
    height: 98%;
    flex-direction: column;
  }

  .Home-Body-Right-Top {
    height: auto;
  }

  .Home-Body-Right-Bottom {
    height: 60%;

  }

  .PORTS {
    text-wrap: wrap;
  }

  .COMMAND {
    text-wrap: wrap;
  }
</style>
[Start]
$(
//*获取请求路径
t-var("REQUEST_URL",(new tank\Env\env)->get("ADMIN_REQUEST_URL"));
)$
<!-- 顶部导航栏 -->
[includeNavbar]
<main>
  $(
  self::Start("component/alert",[
  "id"=>"homeAlert",
  "content"=>"",
  ]);
  )$
</main>

<div class="Home-Body">
  <!-- 容器列表 -->
  <div id="container-list" class="container-list mt-1 ms-3 shadow">
    <div class="d-flex">
      <div class="me-auto p-1">
        <h5>运行容器</h>
      </div>
      <a class="btn btn-primary align-items-right" data-bs-toggle="collapse" href="#collapseExample" role="button"
        aria-expanded="false" aria-controls="collapseExample">
        操作
      </a>
    </div>
    <div class="collapse mt-2" id="collapseExample">
        <div class="card d-flex flex-row">
          <button type="button" class="btn btn-danger m-2" onclick="handleAction(Action_Close)">关闭</button>
          <button type="button" class="btn btn-warning m-2" onclick="handleAction(Action_Restart)">重启</button>
      </div>
    </div>
    <hr>
    <!-- 如果运行容器未空时候，提示暂无数据 -->
    @if(sizeof($TK["dockerData"]["getContainerList"]) <= 1){ self::Start("component/nodata",[ "nodata_content"=>
      "暂无运行容器!"
      ],[]);
      }if@
      @for($i = 1;$i < sizeof($TK["dockerData"]["getContainerList"]);$i++){ $body='' ;
        foreach($TK["dockerData"]["getContainerList"][0] as $k=> $v){
        $body .= '<strong>'.$v.'</strong>:<span
          class="'.$v.' text-truncate">'.$TK["dockerData"]["getContainerList"][$i][$k].'</span><br>';
        };

        echo '
        <div class="container-item mt-3">
          <div class="container-item-title d-flex p-2"
            onclick="selectItem('.$i.',`'.$TK['dockerData']['getContainerList'][$i][6].'`)">
            <p class="me-auto">'.$TK["dockerData"]["getContainerList"][$i][6].' <span
                class="badge bg-primary">'.$TK["dockerData"]["getContainerList"][$i][1].'</span></p>
            <p class="align-items-right" style="font-size:12px">'.$TK["dockerData"]["getContainerList"][$i][4].'</p>
          </div>
          <div class="container-item-body">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed shadow-sm" type="button" data-bs-toggle="collapse"
                  data-bs-target="#collapseOne'.$i.'" aria-expanded="true" aria-controls="collapseOne'.$i.'">
                  查看信息
                </button>
              </h2>
              <div id="collapseOne'.$i.'" class="accordion-collapse collapse" aria-labelledby="headingOne"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  '.
                  $body
                  .'
                </div>
              </div>
            </div>
          </div>
        </div>
        ';
        }for@
  </div>
  <div class="Home-Body-Right mt-1">
    <div class="Home-Body-Right-Top p-3 card card-body ms-3 me-3 shadow">
      <!-- Echart图表 -->
      <div id="ContainerEchart" style="width:100%;height:300px"></div>
      <div class="d-flex h-3 flex-row mb-3" style="height:auto">
        [ResetRoute]
        [Clouse]
        [ClouseParam][/ClouseParam]
        [ClouseUse]$TK[/ClouseUse]
        [ClouseBody]
        return $TK["menus"]["镜像"]["children"]["镜像列表"]["name"];
        [/ClouseBody]
        [/Clouse]
        [ClouseSetParam][/ClouseSetParam]
        [/ResetRoute]
        [Route]
        <div class="group p-3 shadow rounded-pill bg-info text-white">
          镜像总数:$( $count = count($TK["dockerData"]["getImagesList"])-1;echo '<span
            class="badge bg-secondary">'.$count.'</span>')$
        </div>
        [/Route]
        [ResetRoute]
        [Clouse]
        [ClouseParam][/ClouseParam]
        [ClouseUse]$TK[/ClouseUse]
        [ClouseBody]
        return $TK["menus"]["容器"]["children"]["容器列表"]["name"];
        [/ClouseBody]
        [/Clouse]
        [ClouseSetParam][/ClouseSetParam]
        [/ResetRoute]
        [Route]
        <div class="group p-3 shadow ms-3 rounded-pill bg-secondary bg-opacity-75 text-white">
          运行容器总数:$($count = count($TK["dockerData"]["getContainerList"])-1;echo '<span
            class="badge bg-secondary">'.$count.'</span>')$
        </div>
        [/Route]
        [ResetRoute]
        [Clouse]
        [ClouseParam][/ClouseParam]
        [ClouseUse]$TK[/ClouseUse]
        [ClouseBody]
        return $TK["menus"]["容器"]["children"]["容器列表"]["name"];
        [/ClouseBody]
        [/Clouse]
        [ClouseSetParam][/ClouseSetParam]
        [/ResetRoute]
        [Route]
        <div class="group p-3 shadow ms-3 rounded-pill bg-success text-black bg-opacity-75 text-white">
          容器总数:$($count = count($TK["dockerData"]["getContainerAllList"])-1;echo '<span
            class="badge bg-secondary">'.$count.'</span>')$
        </div>
        [/Route]
      </div>
    </div>
    <h class="Home-Body-Right-Bottom p-3 card card-body mt-1 ms-3 me-3 shadow">
      <h5>功能列表</h5>
      <hr>
      <div class="d-flex">
        $(
        self::Start("uses/deploy_nginx",[],[]);
        )$
      </div>
  </div>
</div>
</div>



[/Start]
@script{JSURL/bootstrap.bundle.min.js}script@
@script{JSURL/jquery.min.js}script@
@script{JSURL/tank.js}script@
@script{JSURL/tankConst.js}script@
@script{JSURL/echarts.min.js}script@
<script>

  let selectItemArr = [];
  let selectItemNameArr = [];
  let containerItems = document.getElementsByClassName("container-item")

  var ContainerEchartchartDom = document.getElementById('ContainerEchart');
  var myContainerEchartchartDomChart = echarts.init(ContainerEchartchartDom);
  var option;

  option = {
    xAxis: {
      type: 'category',
      data: `$(echo($TK["echart"]["data"]);)$`.split(",")
    },
    yAxis: {
      type: 'value'
    },
    tooltip: {
      trigger: 'item'
    },
    series: [
      {
        data: `$(echo($TK["echart"]["count"]);)$`.split(","),
        type: 'line',
        smooth: true
      }
    ]
  };


  /**
   * 初始化
   */
  function init() {
    VerStorger()
    containerItemAnimation()
    groupAnimation()
    //*图表加载
    option && myContainerEchartchartDomChart.setOption(option);
  }


  /**
   * 组块动画
   */
  function groupAnimation() {
    let groups = document.getElementsByClassName("group")
    let index = 0;
    let animation = function (index) {
      if (index >= groups.length)
        return;
      groups[index].style.opacity = "1"
      setTimeout(() => {
        animation(index + 1)
      }, 250);
    }
    animation(index)

  }
  /**
   * 容器列表详情动画
   */
  function containerItemAnimation() {
    let containeritems = document.getElementsByClassName("container-item")
    if (containerItems.length == 0)
      return;
    for (let i = 0; i < containeritems.length; i++) {
      containeritems[i].style.transition = "all 0.5s";
    }
    let index = 0;
    let animation = function (index) {
      if (index >= containerItems.length)
        return;
      containerItems[index].style.opacity = "1"
      setTimeout(() => {
        animation(index + 1)
      }, 250);
    }
    animation(index)
  }


  console.log(`$(var_dump($TK["echart"]);)$`);

  /**
   * 处理操作
   */
  function handleAction(action) {
    if (selectItemArr.length == 0) {
      alertId.innerHTML = "请选择容器";
      changeAlertType("alert-danger")
      AlertStart();
    } else {

      switch (action) {
        case Action_Close:
          hanldeActionClose()
          break;
        case Action_Restart:
          handleActionRestart()
          break;
      }
    }
  }

  function handleActionRestart() {
    $.ajax({
      method: "POST",
      url: "$(echo t-get("REQUEST_URL"))$Docker/restartContainer",
      data: JSON.stringify({
        container: btoa(selectItemNameArr.join(","))
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

  function hanldeActionClose() {
    $.ajax({
      method: "POST",
      url: "$(echo t-get("REQUEST_URL"))$Docker/closeContainer",
      data: JSON.stringify({
        container: btoa(selectItemNameArr.join(","))
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



  /**
   * 选择单条
   */
  function selectItem(index, name) {
    if (!selectItemArr.includes(index)) {
      selectItemArr.push(index)
      selectItemNameArr.push(name)
      containerItems[index - 1].style.transform = "scale(1)"
    } else {
      selectItemArr.splice(selectItemArr.findIndex(v => v == index), 1)
      selectItemNameArr.splice(selectItemNameArr.findIndex(v => v == name), 1)
      containerItems[index - 1].style.transform = "scale(0.9)"
    }
  }

  document.body.onload = function () {
    init()
  }
</script>