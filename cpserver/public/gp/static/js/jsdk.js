function wxjsdk() {
    var xhr = XMLHttpRequest();
    var data = {
        url: window.location.href
    };
    xhr.open("POST", "http://dev.yes-go.cn/weixin/getjsapi", true);
    xhr.onload = function(e) {
        if (e.target.status === 200) {
            var result = JSON.parse(e.target.responseText);
            if (result.errcode === 0) {
                alert('获取jsapi成功');
                // wx.config()
            }
        }
        else {
            alert("请求jsapi失败");
        }
    }
    xhr.send(JSON.stringify(data));
}

wxjsdk()