# 'LG EnerVu Plus' simple WebViewer

This simple PHP-Script serves the content through a webserver which is normally shown within the 'LG EnerVu Plus'-App (https://play.google.com/store/apps/details?id=com.lge.ess)
Tested with a LG Home 8 Inverter (D008KE1N211).

## Motivation
Although the inverter only delivers a angular-website, access is only possible via app (which often has to struggle with connection problems). 
You can't also access the inverter by app through a vpn-connection. 

## Requirements
Apache with PHP7+ (mod_rewrite, curl & https required) 
(ex. raspberry pi, synology nas ...)

## How it works
The script authenticates against the inverter and forward all authentificated requests through the webserver

## How to use
1. Copy the files to a local webserver (root-directory)
2. Edit config.php
3. Open in Browser an choose User or Installer

![Example](/images/screen1.jpg "Example")



