<style>
    .fixed {
        position: absolute;
        bottom: 40;
        right: 40;
        z-index: 1;

        button {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
        }

        img {
            width: 30px;
        }
    }
</style>

[Start]
<div class="fixed shadow-sm border-bottom">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CommandModal"><img src="IMAGEURL/code.png"
            class="rounded-circle" /></button>
</div>

<!-- Modal -->
<div class="modal fade" id="CommandModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="CommandModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="CommandModalLabel">模拟终端</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="commandModalBody">
                <div class="card card-body bg-black text-white" id="commandBody">
                </div>
            </div>
            <div class="modal-footer">
                <input id="commandInput" type="text" class="form-control" placeholder="请输入命令"
                    onkeydown="handleCommandKeyDown(event)">
            </div>
        </div>
    </div>
</div>
[/Start]
<script>
    let commandInput = document.getElementById("commandInput")
    let commandData = localStorage.getItem("commandData") ?? "";
    let commandBody = document.getElementById("commandBody")
    let commandModalBody = document.getElementById("commandModalBody")
    commandBody.innerHTML = commandData

    function handleCommandKeyDown(event) {
        if (event.keyCode == 13) {
            $.ajax({
                method: "POST",
                url: "$(echo t-get("REQUEST_URL"))$Command/builder",
                data: JSON.stringify({
                    command: btoa(commandInput.value)
                }),
                headers: {
                    "Content-Type": "application/json"
                },
                dataType: "json",
                success: function (successData) {
                    AlertStart(ALERT_ANIMATION_TOP_MOVE, 200, function () {
                        if (isRequestSuccess(successData)) {
                            commandInput.value = "";
                            changeAlertType("alert-success")
                            alertId.innerHTML = successData.msg;
                            commandData += successData.data.return + "<br>";
                            localStorage.setItem("commandData", commandData)
                            commandBody.innerHTML += successData.data.return + "<br>"
                            setTimeout(() => {
                                commandModalBody.scrollTo(0, commandModalBody.scrollHeight);
                            }, 100);
                        } else {
                            changeAlertType("alert-danger")
                            alertId.innerHTML = successData.msg;
                        }
                    })
                }
            })
        } else {
            return
        }
    }
</script>