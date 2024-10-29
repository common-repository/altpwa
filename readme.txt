=== Plugin Name ===
Contributors: radukn
Donate link: http://www.cnet.ro/wordpress/altpwa/
Tags: picasa, gallery, albums, google, pictures
Requires at least: 2.5
Tested up to: 2.6
Stable tag: 1.1.2

altPWA is a plugin that will allow you to easily embed a Picasa Web Album in your pages.

== Description ==

altPWA is a plugin that will allow you to easily embed a Picasa Web Album in your pages.

Do you have a Picasa account? You can host there 1GB of pictures and publish easily with Picasa program. But how to have them online? With WordPress and this plugin you only need one line for embedding.

[altpwa user=...write-your-username-here... album=...write-the-album-here...]

Example: http://picasaweb.google.com/foto.capan/BratislavaZiuaI
become [altpwa user=foto.capan album=BratislavaZiuaI]

Not working unless you use PHP5 (json issue).

== Installation ==

The plugin is simple to install:

1. Download the zip file
1. Unpack the zip. You should have a directory called `altpwa`, containing several files and folders
1. Upload the `altpwa` directory to the `wp-content/plugins` directory on your WordPress installation. It is important you retain the `altpwa` directory structure
1. Activate plugin

To use it just to that. If you want to have a page to list and show all your albums from Picasa Web Albums, wrote in a blank page

[altpwa user=...]

and instead of ... indicate your Google username from Picasa Web Albums.

If you want to show only one album, in a post for example, wrote in a blank post

[altpwa user=... album=...]

where of course the ... are replaced with your username and the album. For example, I have an album like that

http://picasaweb.google.com/foto.capan/BratislavaZiuaI

Than to embed it on a post I will use

[altpwa user=foto.capan album=BratislavaZiuaI]

That's all!

See the configuration page of the plugin where are several useful options.

== Frequently Asked Questions ==

= I have some troubles with this plugin... =

First of all, go in settings, in the administration area of WordPress, and make sure you've saved at least once.

= I still have problems... =

Read further and than see the plugin page also.

= I encounter an error, related to JSON... =

Well, this plugin use JSON, which I think it's available only with PHP5.

= Do I have to provide my Google password? =

No. This plugin use the API provided by Google and receive the public albums for the account you specify.

= How do I insert an album in a post? =

Simply: [altpwa user=... album=...] where you put instead of ... the username and the album you want.

= How do I insert all my albums in a page? =

Like above, just forget the "album" parameter. Just [altpwa user=...]

= How will be the picture show? =

It's up to you. You can specify the size and the method: inside the page or "above" using Lightbox.

= Lightbox mode is not working! =

Well, Lightbox is JavaScript. If the plugin works without Lightbox, it means the plugin is... working. The issues with Lightbox can be caused by different other plugins or the theme you use.

= Lightbox close and loading graphics? =

Yep, not showing, no? No problem. Just edit js/lightbox.js and replace www.yourwebsite.com with... of course, your website address.

== Screenshots ==

1. In the first screenshot you see a page which list all the albums from a Picasa account. I just wrote in that page [altpwa user=...]
2. In this second screenshot, you can see just one album, after I click on that album in the list you've seen it above. But I can easily embed only one album if I want, using [altpwa user=... album=...]
3. Now it's time to see the photos. If the design is thin, maybe you will prefer to use Lightbox.
4. But there is always the option to have the picture inside the page, as above.
5. And finally, take a look at the administration page. There are several useful option here.

== Documentation ==

Full documentation can be found on the [altPWA](http://www.cnet.ro/wordpress/altpwa/) page.

== Demo ==

If you want to see the plugin in action, there are some sites which use it. Those pages are in Romanian, but pictures are pictures ;) .

* [Photo albums of a Parish](http://www.manastur1vest.cnet.ro/albume-foto/)
* [Just a photo album from the same Parish](http://www.manastur1vest.cnet.ro/2008/06/foto-prima-impartasanie-de-rusalii/)
* [Photo albums of an Eparchy](http://www.bru.ro/cluj-gherla/albume-foto/)
* [Just a photo album from the same Eparchy](http://www.bru.ro/cluj-gherla/foto-hram-si-invesmantare-la-manastirea-macrina-a-osbm/)

== Translation ==

Now the plugin is available in English and Romanian. There is a PO file available for translation, if you like the plugin and want to help.

== Changelog ==

1.1.3 [August 11, 2008]
- solved the images (close, next, prev) issue related to Lightbox
- fixed the date of the album
- fixed some small issues

1.1.1 [August 11, 2008]
- plugin fixed if WordPress is installed in a subdirectory
- fixed an issue with Lightbox and Firefox 3

1.1 [August 10, 2008]
- first official release

1.0 [August 2007]
- plugin was done for me and my friends, never had time to make it public
