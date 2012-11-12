<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>VM - UBUNTU</title>
	<link rel="stylesheet" href="../css/docs.css">
</head>
<body>
<h3>
Instructions for installing developer's tools on Ubuntu:
</h3><center>
  <table class="ITS_version" summary="ITS versions">
	    <!--------------------------------------------------------------------->
			<tr><th>ITEM</th><th>ACTION</th></tr>
	    <!--------------------------------------------------------------------->
	    <tr>
		  <td>GIT<br>SETUP</td>
			<td class="list">
			  <ol>
				<li><b>Download:</b> <a href="ubuntu.com">ubuntu.com</a> Ubuntu server</li>
				<li>Install: <br><code>sudo apt-get install git gitg qgit gitk gitgui</code></li>
				<li>Turn colors on:<br><code>git config --global color.ui auto</code></li>			
				<li>Include editor:<br><code>git config --global core.editor /usr/big/geany</code></li>	
        </ol>
			</td>
			</tr>
			<tr>
				<td>GIT<br>REMOTE</td>
			<td class="list">
			  <ul>
				<li><b>DEV5 ( ssh -X [username]@itsdev5.vip.gatech.edu )</b></li>
				<code>cd /home/[username]<br>
				git clone [username]@itsdev5.vip.gatech.edu:/opt/git/ITS-GT.git html<br>			
				cd html<br>	
				git branch dev</code>
				<li><b>LOCAL</b><br>
				<code>cd /var/www<br>
				git clone [username]@itsdev5.vip.gatech.edu:/home/[username]/html<br>			
				cd html</code><br>	
				[change file(s)]<br>	
				<code>git add *<br>		
				git commit -am "first message"<br>	
				git push origin --delete dev<br>
				git push origin HEAD:dev</code>
				<li><b>DEV5</b></li>
<code>cd /home/[username]/html<br>
git checkout master<br>
git merge dev</code>
        </ul>
			</td>			
		  </tr>
	    <!--------------------------------------------------------------------->			
  </table></center>
</body>
</html>
