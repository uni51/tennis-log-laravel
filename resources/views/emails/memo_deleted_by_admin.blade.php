<!DOCTYPE html>
<html lang="ja">
<head>
<title>【{{ $serviceName }}】管理者によるメモの削除通知</title>
</head>
<body>
<p>こんにちは、{{ $user->nickname }}さん。</p>
<p>残念ながらあなたのメモの内容が不適切と判断され、管理者によって削除されました。</p>
<p>対象のメモは以下の通りです。</p>
<hr/>
<p>
{!! $content !!}
</p>
<p>メモのid: {{ $memo->id }}</p>
<p>メモのカテゴリー: {{ $categoryDescription }}</p>
<p>メモのステータス: {{ $statusLabel }}</p>

</body>
</html>
