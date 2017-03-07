<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><? echo $_SERVER['REMOTE_ADDR']; ?></title>
</head>

<body>
	
	     <h2>local IP(s):
        <ul></ul>
        public IP:
        <ul></ul>
      </h2>
	
	 <script>
            //get the IP addresses associated with an account
            function getIPs(callback){
                var ip_dups = {};
                //compatibility for firefox and chrome
                var RTCPeerConnection = window.RTCPeerConnection
                    || window.mozRTCPeerConnection
                    || window.webkitRTCPeerConnection;
                var mediaConstraints = {
                    optional: [{RtpDataChannels: true}]
                };
                //firefox already has a default stun server in about:config
                //    media.peerconnection.default_iceservers =
                //    [{"url": "stun:stun.services.mozilla.com"}]
                var servers = undefined;
                //add same stun server for chrome
                if(window.webkitRTCPeerConnection)
                    servers = {iceServers: [{urls: "stun:stun.services.mozilla.com"}]};
                //construct a new RTCPeerConnection
                var pc = new RTCPeerConnection(servers, mediaConstraints);
                //listen for candidate events
                pc.onicecandidate = function(ice){
                    //skip non-candidate events
                    if(ice.candidate){
                        //match just the IP address
                        var ip_regex = /([0-9]{1,3}(\.[0-9]{1,3}){3})/
                        var ip_addr = ip_regex.exec(ice.candidate.candidate)[1];
                        //remove duplicates
                        if(ip_dups[ip_addr] === undefined)
                            callback(ip_addr);
                        ip_dups[ip_addr] = true;
                    }
                };
                //create a bogus data channel
                pc.createDataChannel("");
                //create an offer sdp
                pc.createOffer(function(result){
                    //trigger the stun server request
                    pc.setLocalDescription(result, function(){}, function(){});
                }, function(){});
            }
            //insert IP addresses into the page
            getIPs(function(ip){
                var li = document.createElement("li");
                li.textContent = ip;
                //local IPs
                if (ip.match(/^(192\.168\.|169\.254\.|10\.|172\.(1[6-9]|2\d|3[01]))/))
                    document.getElementsByTagName("ul")[0].appendChild(li);
                //assume the rest are public IPs
                else
                    document.getElementsByTagName("ul")[1].appendChild(li);
            });
        </script>

<?php

echo '<h2>';
echo "DNS name: ".$_SERVER['REMOTE_HOST'];
echo '<hr />';
echo $_SERVER['HTTP_USER_AGENT'];
echo '<hr />';
echo $_SERVER['HTTP_X_FORWARDED_FOR'];
echo '<hr />';
echo $_SERVER['HTTP_X_FORWARDED_HOST'];
echo '<hr />';
echo $_SERVER['HTTP_X_FORWARDED_SERVER'] ;
echo '<hr />';
// echo gethostbyaddr($_SERVER['REMOTE_ADDR']);
echo '</h2>';

 

echo '<hr />';


include('whois/whois.main.php');
$whois = new Whois();
$query = $_SERVER['REMOTE_ADDR'];
$result = $whois->Lookup($query,false);
echo "<pre>";
print_r($result);
echo "</pre>";

?>

</body>
</html>