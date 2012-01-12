
	function change_nick(nick){
			$("#nick").html('<form method="post" action="?action=set_nick" id="nick_form"><input type="text" name="nick" value="'+nick+'" size=15> <input type="image" src="icons/disk.png" alt="speichern"></button></form>');
			return false;
		};
	
	function set_new_poll_form()
	{
		var type = $("#new_poll_select").val();
		var form = $("#new_poll_form");
		
		if(type == 'yesno')
			form.html("Keine Optionen");
		else if(type == 'choice')
			form.html('Ein Eintrag pro Zeile:<br /><textarea name="choices"></textarea>');
		else if(type == 'team')
			form.html('Anzahl Teams: <input type="text" name="nr_teams"><!--<br />'+
			'<input type="checkbox" name="allow_new_teams" value="yes"> neue Teams zulassen  <span style="float:right">'+
			'Personen pro Team: <input type="text" name="max_per_team"><small> (0 = keine Beschränkung)</span>-->');
		else
			form.html("unknown: "+type);
	};

$(document).ready(function(){
	set_new_poll_form();
	
	$("#new_poll_select").change(set_new_poll_form);
	
	$('.countdown').each(function(i,obj){
		
		var timestamp = $(obj).attr('timestamp');
		
		var d = new Date(); var now = new Date();
		d.setTime(timestamp*1000);
		
		if(d>now)
		{
			$(obj).countdown({until: d, expiryText: 'abgelaufen', significant: 0,
			layout: '{y<}{yn} Jahre {y>}{o<}{on} Monate {o>}{d<}{dn} Tage, {d>}{h<}{hn} h {h>}{mn} min {snn} sec'});
		}
		else
		{
			$(obj).html('abgelaufen');
		}
	});
	
	$('a.ask_if_sure').click(function(obj){
		if($(this).attr('question'))
			return confirm($(this).attr('question'));
		else
			return confirm('Sicher?');
			
	});
	
	$('.pie_chart').each(function(i,obj){
		var o = $(obj);
		var data = [];
		o.children().each(function(j,opts){
			var val = parseInt($(opts).attr('votes'));
			if(val > 0)
				data.push({name: $(opts).html(), y: val});
		});
		
		var chart = new Highcharts.Chart({
			chart: {
				renderTo: obj,
				type: 'pie'
			},
			series: [{data: data}],
			credits: {enabled: false},
			title: {text: null},
			tooltip: {enabled: false},
			plotOptions: {
				pie: {
					dataLabels: {
						enabled: true,
						style: {fontSize: '12pt'},
						formatter: function() {
							return '<b>'+ this.point.name +'</b>: '+ this.y;
						}
					}
				}
			}
		});
	});
	
	$('.yes_no_pie').each(function(i,obj){
		
		var yes = parseInt($(obj).attr('yes'));
		var no  = parseInt($(obj).attr('no'));
		
		pie_chart = new Highcharts.Chart({
			chart: {
				renderTo: obj,
				type: 'pie'
			},
			credits: {
				enabled: false
			},
			title: {
				text: null
			},
			plotOptions: {
				pie: {
					dataLabels: {
						enabled: true,
						style: {fontSize: '16pt'},
						formatter: function() {
							return '<b>'+ this.point.name +'</b>: '+ this.y;
						}
					}
				}
			},
			series: [{
				data: [
				{name: 'Ja',   y:yes, color: '#8DDB0E',sliced:true},
				{name: 'Nein', y:no , color: '#ED240F'}]
			}],
			tooltip: {
				enabled: false
			}
		});
	});
	
	
	$('.ausklappen').each(function(){
		$(this).children('.new').hide();
		$(this).children('.old').html('<a href="#">'+$(this).children('.old').first().html()+'</a>');
		$(this).children('.old').click(function(){
			$(this).hide();
			$(this).siblings('.new').show();
		});
	});
});
