<?php

use think\facade\Route;

// 获取卡牌分类，
Route::get("hc/getcardtype","hc/card.CardType/get");
// 传卡牌分类编号，随机获取一张卡牌的信息
Route::get("hc/getrandomcard", "hc/card.Card/getRandom");
// 传卡牌编号，获取该卡牌的信息，
Route::get("hc/getcardbyid", "hc/card.Card/getCardById");
// 传卡牌编号，获取该卡牌的留言列表
Route::get("hc/getmsgbycardid", "hc/comment.Comment/getCommentByCardId");
// 记录游湖留言
Route::any("hc/submitmsg", "hc/comment.Comment/addComment");

/**
 * manage
 */
// 添加card_type
Route::any("api/hc/manage/addcardtype", "hc/manage.CardType/addOne");
// 删除card_type
Route::any("api/hc/manage/deletecardtype", "hc/manage.CardType/deleteById");
// 更新card_type
Route::any("api/hc/manage/changecardtype", "hc/manage.CardType/change");
// 获取card_type列表
Route::get("api/hc/manage/getcardtype", "hc/manage.CardType/getSome");

// 添加card
Route::any("api/hc/manage/addcard", "hc/manage.Card/addOne");
// 删除card
Route::any("api/hc/manage/deletecard", "hc/manage.Card/deleteById");
// 更新card
Route::any("api/hc/manage/changecard", "hc/manage.Card/change");
// 获取card列表
Route::get("api/hc/manage/getcardlist", "hc/manage.Card/getSome");
// 获取card info
Route::get("api/hc/manage/getcardinfo", "hc/manage.Card/getOne");

// 评论
// 删除评论
Route::any("api/hc/manage/deletecomment", "hc/manage.Comment/remove");
// 更改评论的启用状态
Route::any('api/hc/manage/enableAction', 'hc/manage.Comment/enableAction');
// 获取卡牌评论列表
Route::get("api/hc/manage/getcomment", "hc/manage.Comment/getComment");
// 传comment_id获取comment
Route::get("api/hc/manage/getcommentbyid", "hc/manage.Comment/getCommentByCommentId");

// Uload
Route::any("api/hc/manage/uploadpic", "hc/upload.Upload/pic");

// login
Route::any('api/hc/manage/login', 'hc/auth.ManageAuth/login');
Route::any('api/hc/manage/logout', 'hc/auth.ManageAuth/logout');
/**
 * client
 */
// 获取卡牌
Route::get("api/hc/client/getcard", "hc/card.Card/getSome");
// 随机获取卡牌
Route::get("api/hc/client/getcardrandom", "hc/card.Card/getOneRandom");
// 获取卡牌类型
Route::get("api/hc/client/getcardtype", "hc/card.CardType/getSome");
// 获取卡牌评论
Route::get("api/hc/client/getcomment", "hc/comment.Comment/getComment");
// 用户添加卡牌评论
Route::post("api/hc/client/addcomment", "hc/comment.Comment/addComment");
// 传comment_id获取comment
Route::get("api/hc/client/getcommentbyid", "hc/comment.Comment/getCommentByCommentId");
// 传comment_id，添加回复评论
Route::any("api/hc/client/replaycomment", "hc/comment.Comment/replayComment");
// 传comment_id，获取回复评论列表
Route::get("api/hc/client/getreplaycomment", 'hc/comment.Comment/getReplayComment');