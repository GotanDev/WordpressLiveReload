# WordpressLiveReload
Wordpress Plugin for enabling livereload  of your browser on any theme change when debug mode


### Behavior 

The plugin will check every second that your active Wordpress theme has changed. 
If it has changed, it will reload your browser.  

Really usefull for theme development on local workstation.



### Activation

The plugin is only active when 
* debug mode is enabled. 
You can enable debug mode in `wp-config.php` with `WP_DEBUG` constant value. 
* if you call your wordpress setup from allowed IP address.
  * `127.0.0.1` local address is always allowed
  * You can add other allowed IP addresses in LiveReload Setting menu


### Author & License
* Made with Love from French Alps 🇫🇷 ⛰  by  
  👨 [Damien Cuvillier](https://damiencuvillier.com) - [Gotan](https://gotan.io) 

* If you find this plugin useful,  
  ☕ you can [**buy me a coffee**](https://www.buymeacoffee.com/damq) .
* [GPL V2](https://opensource.org/licenses/Apache-2.0)


