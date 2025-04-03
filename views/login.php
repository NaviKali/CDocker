@link{CSSURL/bootstrap.min.css}link@
@link{CSSURL/tank.css}link@
<style>
    body {
        overflow: hidden;
    }

    main {
        background-image: url("IMAGEURL/04.jpg");
        background-position: center top;
        background-size: cover;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        align-content: center;

    }

    .Login {
        backdrop-filter: blur(50px);
        width: 50%;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s;
        position: relative;
    }

    .Login-title {
        font-size: 25px;
    }
</style>
[Start]
$(
//*获取请求路径
t-var("REQUEST_URL",(new tank\Env\env)->get("ADMIN_REQUEST_URL"));
//*获取登录类型
t-var("LOGINTYPE",(new tank\Env\env)->get("LOGIN_TYPE"));
)$
<main>
    $(
    self::Start("component/alert",[
    "id"=>"asd",
    "content"=>"",
    ]);
    )$
    <div class="Login border-top border-bottom" id="LoginDialog">
        <div class="shadow-lg p-3 mb-5 bg-body-tertiary rounded border-start border-end">
            <p class="text-primary Login-title">$(echo tank\getAPPJSON()->PROJECT_NAME)$ (v$(echo tank\getAPPJSON()->PROJECT_VERSION)$)</p>
            <!-- 标注古诗词 -->
            <span class="text-end">[长风破浪会有时，直挂云帆济沧海]</span>
        </div>
        <div class="shadow-lg p-3 mb-5 bg-body-tertiary rounded border-bottom">
            <p class="text-primary Login-title">登录类型:$(echo t-get("LOGINTYPE"))$</p>

            @if((new tank\Env\env)->get("LOGIN_TYPE") == \app\admin\controller\Login::TYPE_PASSWORD){
            echo '
            <div class="mb-3">
                <label class="form-label">密码</label>
                <input type="text" class="form-control" id="data">
            </div>';
            }if@
            @if((new tank\Env\env)->get("LOGIN_TYPE") == \app\admin\controller\Login::TYPE_MDDATE){
            echo '
            <div class="mb-3">
                <label class="form-label">md5日期</label>
                <input type="text" class="form-control" id="data">
            </div>';
            }if@
            @if((new tank\Env\env)->get("LOGIN_TYPE") == \app\admin\controller\Login::TYPE_NONE){
            echo '
            <div class="mb-3" style="display:none">
                <label class="form-label">md5日期</label>
                <input type="text" class="form-control" id="data" value="nonepassword">
            </div>';
            }if@

            <button type="button" class="btn btn-success" onclick="handleLogin()">登录</button>
        </div>
    </div>
</main>
[/Start]
@script{JSURL/bootstrap.bundle.min.js}script@
@script{JSURL/jquery.min.js}script@
@script{JSURL/tank.js}script@
@script{JSURL/tankConst.js}script@
<script>
    let LoginDialog = document.getElementById("LoginDialog")
    let data = document.getElementById("data")


    function init() {
        LoginDialog.style.top = "1000px"
    }

    document.body.onload = async function () {
        init()
        await setTimeout(async () => {
            await (LoginDialog.style.top = "0px")
        }, 500)

    }

    function handleLogin() {
        $.ajax({
            method: "POST",
            url: "$(echo t-get("REQUEST_URL"))$Login/Login",
            data: JSON.stringify({
                data: btoa(data.value ?? "")
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
                        localStorage.setItem(StorageUUID,successData.data.uuid)
                        setTimeout(() => {
                            location.href = "./home"
                        }, 1000);
                    } else {
                        changeAlertType("alert-danger")
                        alertId.innerHTML = successData.msg;
                    }
                })
            }
        })
    }



</script>