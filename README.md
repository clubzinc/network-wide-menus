Jason Roman's WordPress Multiple Network-Wide Menus
=================

This package is forked from the <a href="https://github.com/rrennick/network-wide-menu">network-wide-menu</a> package.

The original WordPress plugin only supports a single network-wide menu across sites.  This fork allows you to have as many menus as you want loaded across each network site.  This could be useful for a site with multiple menus where you have a subdomain that needs all of the same menus.

Simply unzip this package in the wp-plugins folder and then network activate it.  The plugin caches copies of the navigation menus on the network's main site, then these replace the corresponding menus on each sub site.  The sub-sites must have menus registered (with at least one menu item) in the same corresponding slots.
