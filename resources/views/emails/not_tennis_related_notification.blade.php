<!DOCTYPE html>
<html lang="ja">
<head>
    <title>テニスに関連しないメモの投稿通知</title>
</head>
<body>
<h1>テニスに関連しないメモが投稿されました。</h1>
<p>{!! $content !!}</p>
@if($memo)
    <p>{{ "メモの種別: 更新" }}</p>
    <p>メモのid: {{ $memo->id }}</p>
    <p>{{ "投稿者のnickname: " . $user->nickname }}</p>
@else
    <p>{{ "メモの種別: 新規作成" }}</p>
    <p>{{ "投稿者のnickname: " . $user->nickname }}</p>
@endif
</body>
</html>
