/**
 * 媒体查询适配
 * @param width 宽度
 * @param url 跳转路径
 */
function MediaConfigView(width, url) {
    if (window.innerWidth < width) {
        let to = "./index?mediaType=" + url;
        location.href = to
    }
}

/**
 * 是否请求成功
 * @param object res 
 * @returns bool
 */
function isRequestSuccess(res){
    return res.code == 200 ? true : false;
}

/**
 * 验证存储
 * @returns void
 */
function VerStorger(){
    let uuid = localStorage.getItem(StorageUUID)
    if(uuid == undefined || uuid == null){
        location.href = "./login"
    }
}