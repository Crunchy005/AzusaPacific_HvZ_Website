To DO List:
	- password change on profile page. maybe not.  Going to add to admin_profile page.
	- enter_kill google maps sucks!!!!! Need to fix...this works
	- start working on admin UI layout.
		- manual player registration - REQUIRED FEATURE!!!
		- start game/stop game - create game made. Need a stop game function to kill everyone...i think. ??
		- manage missions - in progress
		- manage vaccinations
	- admin profile page for player setting control. - in progress
	- reveal alphas on main settings page. - add alpha select to main settings page.
	
TO TEST:
	- alpha stuff...reveal and select are done.  Needs testing.

DONE:
	- work on a footer with a link to admin page - added admin link in nav when admin logged in. - DONE
	- start working on admin UI layout.
		- admin page, with its own functions. - admin_functions.php created
		- open/close registration - DONE
		- reset for new game - DONE
	- email players, different choices based on status - DONE
		- email dead - didn't do this, part of everyone
		- email human - DONE
		- email zombie - DONE
		- CC admins/mods - I think this works now.
		- email all - DONE
	- implement unique kill ID in database and signup page. - DONE
	- User profile page. - DONE
	- google maps enter kill page done. - FINALLY!!!!!

Test List:
	- test email activation for player sign-up













<!-- Beautiful Email Validation

//HTML
<div class="newsletter-signup" id="test">
    <input type="email" id="test2" autocomplete="off" style="border: 1px;" />
</div>
<br />
<div id="valid"></div>
//END HTML

//Javascript(jquery)
$('.newsletter-signup input:first').on('keyup', function () {
    $('#test2').css({
        backgroundColor: "#FFF",
        borderLeft: "3px solid #333"
    });
    var valid = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(this.value) && this.value.length;
    /*    valid ? $('#test2').css({backgroundColor: "#FFF", borderLeft: "3px solid #008000"}) : $('#test2').css({backgroundColor: "#FFF", borderLeft: "3px solid #EE0000" });
     */
    valid ? $('#test2').css({
        backgroundColor: "#F9F9F9",
        borderLeft: "3px solid #008000"
    }) : $('#test2').css({
        backgroundColor: "#F9F9F9",
        borderLeft: "3px solid #EE0000"
    });
});
//End javascript

//CSS
input.middle:focus {
    display: inline-block;
    background-color: #F9F9F9;
    width: 300px;
    padding: 8px;
    border: solid 1px #c4c7cb;
    border-radius: 3px;
    box-shadow: inset 0px 1px 1px #e0e4e9;
    outline: none;
}
input {
    display: inline-block;
    background-color: #F9F9F9;
    width: 300px;
    padding: 8px;
    border: solid 1px #c4c7cb;
    border-radius: 3px;
    box-shadow: inset 0px 1px 1px #e0e4e9;
    outline: none;
}
input:focus, select:focus, textarea:focus, button:focus {
    display: inline-block;
    background-color: #F9F9F9;
    width: 300px;
    padding: 8px;
    border: solid 1px #c4c7cb;
    border-radius: 3px;
    box-shadow: inset 0px 1px 1px #e0e4e9;
    outline: none;
}
#test2 {
    border-left: thick solid #333333;
}
//END CSS

//DEMO
http://jsfiddle.net/justinmshep/9EBVX/71/
//END DEMO

-->

http://www.modern.ie/en-us/report#http%3A%2F%2Fhvz.justatechgeek.com

//BUILD A WINDOWS PHONE LIVE TILE
//Required RSS feed
http://www.buildmypinnedsite.com/en