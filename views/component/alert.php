$(
//*设置默认值
$TK["isDefaultNone"] = true;
$TK["alertType"] = "alert-success";
)$
<style>
    #$(echo $TK['id'])$ {
        position: absolute;
        top: 20px;
        transition: all 0.3s !important;
        z-index: 100000 !important;
    }
</style>
[Start]
<div class="alert $(echo $TK['alertType'])$" role="alert" id="$(echo $TK['id'])$">
    $(echo $TK["content"])$
</div>
[/Start]
<script>
    let alertId = document.getElementById("$(echo $TK['id'])$");
    const ALERT_ANIMATION_TOP_MOVE = "top_move"

    if ("$(echo empty($TK['isDefaultNone']) ? false : $TK['isDefaultNone'])$") {
        alertId.style.display = "none"
    }

    function AlertStart(animation = ALERT_ANIMATION_TOP_MOVE, timeout = 200, clouse = () => { }) {
        alertId.style.display = "none"

        switch (animation) {
            case ALERT_ANIMATION_TOP_MOVE:
                alertId.style.top = "-100px";
                alertId.style.display = "block"
                setTimeout(async () => {
                    alertId.style.top = "20px"
                    await clouse()
                    await setTimeout(async () => {
                        alertId.style.top = "-100px";
                        await setTimeout(() => {
                            alertId.style.display = "none"
                        }, 500)
                    }, 3000);
                }, timeout);
                break;
            default:
                break;
        }
    }

    function changeAlertType(type) {
        alertId.classList = `alert ${type}`
    }

</script>