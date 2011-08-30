/*
 * tweetable 1.3 - jQuery twitter feed generator plugin
 *
 * Copyright (c) 2009 Philip Beel (http://www.theodin.co.uk/)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) 
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Revision: $Id: jquery.tweetable.js 2010-08-09 $ 
 *
 */
(function($){
		$.fn.tweetable=function(options){
			var defaults={
				limit:5,
				username:'philipbeel',
				time:false,
				replies:false
			};
			var options=$.extend(defaults,options);
			return this.each(function(options){
				var act=$(this);
				var api="http://api.twitter.com/1/statuses/user_timeline.json?screen_name=";
				var count="&count=";
				$.getJSON(api+defaults.username+count+defaults.limit+"&callback=?",act,function(data){
					$.each(data,function(i,item){
						if(i==0){
							$(act).prepend('<ul class="tweetList">');
						}
						if(defaults.replies === false) {
							if(item.in_reply_to_status_id === null){
								$('.tweetList').append('<li class="tweet_content_'+i+'"><span class="tweet_link_'+i+'">'+item.text.replace(/#(.*?)(\s|$)/g,'<span class="hash">#$1 </span>').replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig,'<a href="$&">$&</a> ').replace(/@(.*?)(\s|\(|\)|$)/g,'<a href="http://twitter.com/$1">@$1 </a>$2'));
								if(defaults.time==true){
									$('.tweet_content_'+i).append('<small> '+item.created_at.substr(0,20)+'</small>');
								}								
							}							
						} else {
							$('.tweetList').append('<li class="tweet_content_'+i+'"><span class="tweet_link_'+i+'">'+item.text.replace(/#(.*?)(\s|$)/g,'<span class="hash">#$1 </span>').replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig,'<a href="$&">$&</a> ').replace(/@(.*?)(\s|\(|\)|$)/g,'<a href="http://twitter.com/$1">@$1 </a>$2'));
							if(defaults.time==true){
								$('.tweet_content_'+i).append('<small> '+item.created_at.substr(0,20)+'</small>');
							}								
						}
					});
				});
			});
		}
	})(jQuery);