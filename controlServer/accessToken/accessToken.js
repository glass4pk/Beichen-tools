var config=require('../serverConfig').wechat;  // 配置文件
const mysql=require("mysql");
const https=require("https");
const http = require('http');
var   type=1;
var   update_time=3600*1.5;
// 创建数据池


/**
 * 更新数据库的accessToken
 * @param {*} result 
 */
function saveResult(result){
	console.log("accessToken: "  + result);
	let connection=mysql.createConnection({
		host:config['db'],
		port: config['port'],
		user:config['dbuser'],
		password:config['dbpassword'],
		database:config['database']
	});
	connection.connect();
	var sql="update access_token set access_token=\""+result+"\",create_time=now() where type="+type+" ;";
	connection.query(sql,function(err,result){
		 // 结束会话
		if(err) {
		    console.log(Date() + " error: " + err);
		};
		connection.end(); 
	});
}

/**
 * 第一次获取accessToken
 * 
 */
function firstGetAccessToken(){
	var url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid="+config["appid"]+"&secret="+config["appsecret"];
	// console.log(url);
	try{
		https.get(url,(res)=>{
			var data="";
			res.on('data',(d)=>{
				data+=d.toString("ascii");}
			);
			res.on("end",()=>{
				var result=JSON.parse(data).access_token;
				saveResult(result);
			});
		});
		
		console.log(Date() + '第一次结束');
    }
    catch (e){ 
    	console.log(Date() + '第一次出现异常');
    }
}

firstGetAccessToken();

setInterval(function(){
	var url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid="+config["appid"]+"&secret="+config["appsecret"];
	try{
		https.get(url,(res)=>{
			var data="";
			res.on('data',(d)=>{
				data+=d.toString("ascii");}
			);
			res.on("end",()=>{
				var result=JSON.parse(data).access_token;
				saveResult(result);
			});
		});
		console.log(Date() + "success");
    }
    catch (e){
    	console.log(Date() + "出现异常");
    }

},1000*update_time);
