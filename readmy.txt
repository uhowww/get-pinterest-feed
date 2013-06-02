=== Plugin Name ===
Contributors: takumin
Donate link: http://takumin.ddo.jp/
Tags: pinterest,feed,RSS,
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 0.0.3

PinterestのボードをWordPressに表示しよう！

== Description ==
簡単なショートコードを書くだけで、貴方の投稿にPinterestのボードを表示させます。
詳しくはこちら<a href="http://takumin.ddo.jp/wordpress-plugin-get-pinterest-feed-manual/">http://takumin.ddo.jp/wordpress-plugin-get-pinterest-feed-manual/</a>

Just write a simple short code, to display the Pinterest-board to your posts.

== Installation ==

1. Upload `get-pinterest-feed` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Get Pinterest Feed' menu in WordPress
3.Write [GetPinFeed url="http://pinterest.com/USER_NAME/BOARD_NAME/rss"] short code where you want to see the Photos.

== Frequently Asked Questions ==

== Screenshots ==
1.Code written
2.Looks like this

== Changelog ==

= 0.0.1 =
First release.

= 0.0.2 =
Use the built-in SimplePie
SimplePie cache support
Warning measures

= 0.0.3 =
稀にurl抽出が上手く動作しない症状の対処。

== Upgrade Notice ==

= 0.0.1 =
First release.

= 0.0.2 =
Use the built-in SimplePie
SimplePie cache support
Warning measures

= 0.0.3 =
稀にurl抽出が上手く動作しない症状の対処。

== Arbitrary section ==

Option of short code.

解説ページ<a href="http://takumin.ddo.jp/wordpress-plugin-get-pinterest-feed-manual/">http://takumin.ddo.jp/wordpress-plugin-get-pinterest-feed-manual/</a>

Board:[GetPinFeed url="http://pinterest.com/USER_NAME/BOARD_NAME/rss" limit="10" columnwidth="200" gutterwidth="9"]
User:[GetPinFeed url="http://pinterest.com/USER_NAME/feed.rss" limit="10" columnwidth="200" gutterwidth="9"]

url:Pinterest board's Feed URL.
limit:Number of titles to display.
columnwidth: Width of the photo, use it set in px.
gutterwidth: Gap between the next photo, use it set in px.