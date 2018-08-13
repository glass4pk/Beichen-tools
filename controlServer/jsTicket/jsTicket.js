var config=require('../serverConfig').wechat;  // 配置文件
var crypto = require('crypto');
const https=require("https");
const http = require('http');
var   type=2;
var   update_time=3600*1.5;
var   host = config.host;

// 创建数据池


// 获取指定长度的随机字符串
function randomString(len) {
　　len = len || 32;
　　var $chars = 'DhghEFGH461686662325IJKLNOPQRSdhjhjefhijkm534nprswxyz';
　　var maxPos = $chars.length;
　　var pwd = '';
	var i=0;
　　for (i = 0; i != len; i++) {
　　　　pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
　　}
　　return pwd;
}

// 签名算法
function getSignature(){
	var sha1 = crypto.createHash('sha1');
	var noncestr=randomString(20);
	var timestamp = (String)(Date.parse(new Date())).substr(0,10);
	var string1 = timestamp+"passcode=d2wenvldj45fhgjJVlfglfstfgdjjslys2gjf7979jlf&timestamp="+noncestr;
	sha1.update(string1);
	var signature = sha1.digest('hex');
	return '?noncestr='+noncestr+'&timestamp='+timestamp+'&signature='+signature;
}

function sendTemplateMessage(){
	var url="http://"+host+"/weixin/flashjsapi"+getSignature();
	console.log(url);
	try{
		http.get(url,(res)=>{
			var data="";
			res.on('data',(d)=>{
				data+=d.toString("ascii");
			});
			res.on("end",()=>{
				var result;
				try{
					result=JSON.parse(data);
					console.log(result);
					console.log('success');
				}
				catch(e){
					console.log('失败');
				}	
			});
		});
    }
    catch (e){
		console.log(e);
    	console.log('第一次出现异常');
    }
}

sendTemplateMessage();

setInterval(function(){
    sendTemplateMessage();
},1000*update_time);
