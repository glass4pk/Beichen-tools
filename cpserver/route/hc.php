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
